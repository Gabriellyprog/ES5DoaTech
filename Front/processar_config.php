<?php
session_start();
require_once __DIR__ . '/flash.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';
$id_usuario = (int) $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tipo_usuario = (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] === 'ong') ? 'ong' : 'usuario';
    $telefone = trim($_POST['telefone'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    $localizacao = trim($_POST['localizacao'] ?? '');
    $area_atuacao = trim($_POST['area_atuacao'] ?? '');
    $descricao_ong = trim($_POST['descricao_ong'] ?? '');

    if (empty($nome) || empty($email)) {
        redirect_with_flash('perfil.php?aba=config', 'Nome e e-mail são obrigatórios.', 'warning');
    }

    $stmt_email = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND id <> ? LIMIT 1");
    $stmt_email->bind_param("si", $email, $id_usuario);
    $stmt_email->execute();
    $resultado_email = $stmt_email->get_result();

    if ($resultado_email && $resultado_email->num_rows > 0) {
        $stmt_email->close();
        $conn->close();
        redirect_with_flash('perfil.php?aba=config', 'Esse e-mail já está sendo usado por outra conta.', 'error');
    }
    $stmt_email->close();

    $sql = "UPDATE usuarios SET nome = ?, email = ?, tipo_usuario = ?, telefone = ?, documento = ?, localizacao = ?, area_atuacao = ?, descricao_ong = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $nome, $email, $tipo_usuario, $telefone, $documento, $localizacao, $area_atuacao, $descricao_ong, $id_usuario);

    if (!$stmt->execute()) {
        redirect_with_flash('perfil.php?aba=config', 'Não foi possível atualizar seu perfil agora. Tente novamente.', 'error');
    }

    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['tipo_usuario'] = $tipo_usuario;

    if (!empty($_POST['nova_senha'])) {
        $nova_senha = $_POST['nova_senha'];
        $confirma_senha = $_POST['confirma_senha'] ?? '';

        if ($nova_senha !== $confirma_senha) {
            redirect_with_flash('perfil.php?aba=config', 'As senhas não coincidem. Verifique e tente novamente.', 'warning');
        }

        $senha_cripto = password_hash($nova_senha, PASSWORD_DEFAULT);
        $sql_senha = "UPDATE usuarios SET senha = ? WHERE id = ?";
        $stmt_senha = $conn->prepare($sql_senha);
        $stmt_senha->bind_param("si", $senha_cripto, $id_usuario);
        $stmt_senha->execute();
        $stmt_senha->close();
    }

    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $pasta_destino = "uploads/";
        if (!is_dir($pasta_destino)) {
            mkdir($pasta_destino, 0777, true);
        }

        $extensao = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($extensao, $extensoes_permitidas, true)) {
            $novo_nome_foto = "user_" . $id_usuario . "_" . time() . "." . $extensao;
            $caminho_completo = $pasta_destino . $novo_nome_foto;

            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho_completo)) {
                $sql_foto = "UPDATE usuarios SET foto = ? WHERE id = ?";
                $stmt_foto = $conn->prepare($sql_foto);
                $stmt_foto->bind_param("si", $novo_nome_foto, $id_usuario);
                $stmt_foto->execute();
                $stmt_foto->close();
            }
        }
    }

    redirect_with_flash('perfil.php?aba=config', 'Perfil atualizado com sucesso!', 'success');

    $stmt->close();
    $conn->close();
}
?>
