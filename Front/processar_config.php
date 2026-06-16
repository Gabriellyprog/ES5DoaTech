<?php
// processar_config.php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';
$id_usuario = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $localizacao = $_POST['localizacao'];

    // 1. Atualização dos dados textuais básicos
    $sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, localizacao = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nome, $email, $telefone, $localizacao, $id_usuario);
    $stmt->execute();

    // Atualiza o nome armazenado na sessão também
    $_SESSION['usuario_nome'] = $nome;

    // 2. Lógica para alteração de senha (se digitou alguma nova)
    if (!empty($_POST['nova_senha'])) {
        $nova_senha = $_POST['nova_senha'];
        $confirma_senha = $_POST['confirma_senha'];

        if ($nova_senha === $confirma_senha) {
            $senha_cripto = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql_senha = "UPDATE usuarios SET senha = ? WHERE id = ?";
            $stmt_senha = $conn->prepare($sql_senha);
            $stmt_senha->bind_param("si", $senha_cripto, $id_usuario);
            $stmt_senha->execute();
        } else {
            echo "<script>alert('As senhas não coincidem!'); window.history.back();</script>";
            exit();
        }
    }

    // 3. Lógica para upload e alteração da Foto de Perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $pasta_destino = "uploads/";
        
        // Garante que a pasta uploads existe no seu computador
        if (!is_dir($pasta_destino)) {
            mkdir($pasta_destino, 0777, true);
        }

        $extensao = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        $novo_nome_foto = "user_" . $id_usuario . "_" . time() . "." . $extensao;
        $caminho_completo = $pasta_destino . $novo_nome_foto;

        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho_completo)) {
            $sql_foto = "UPDATE usuarios SET foto = ? WHERE id = ?";
            $stmt_foto = $conn->prepare($sql_foto);
            $stmt_foto->bind_param("si", $novo_nome_foto, $id_usuario);
            $stmt_foto->execute();
        }
    }

    // Redireciona de volta para a aba de configurações exibindo uma mensagem de sucesso
    echo "<script>
            alert('Perfil atualizado com sucesso!');
            window.location.href = 'perfil.php?aba=config';
          </script>";
    
    $stmt->close();
    $conn->close();
}
?>