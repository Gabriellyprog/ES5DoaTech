<?php
// processar_doacao.php
session_start();

// Proteção: Se o usuário não estiver logado, não pode doar
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Você precisa estar logado para fazer uma doação!'); window.location.href = 'login.php';</script>";
    exit();
}

include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['usuario_id'];
    $titulo = $_POST['titulo'];
    $categoria = $_POST['categoria'];
    $estado_conservacao = $_POST['estado_conservacao'];
    $descricao = $_POST['descricao'];
    $status = 'Disponível'; // Status inicial de toda doação

    $nome_foto = null;

    // Lógica para upload da foto do item (se houver)
    if (isset($_FILES['foto_item']) && $_FILES['foto_item']['error'] == 0) {
        $pasta_destino = "uploads/";
        if (!is_dir($pasta_destino)) { mkdir($pasta_destino, 0777, true); }

        $extensao = pathinfo($_FILES['foto_item']['name'], PATHINFO_EXTENSION);
        $nome_foto = "item_" . time() . "_" . rand(100, 999) . "." . $extensao;
        
        move_uploaded_file($_FILES['foto_item']['tmp_name'], $pasta_destino . $nome_foto);
    }

    // Inserir no banco de dados
    $sql = "INSERT INTO doacoes (id_usuario, titulo, categoria, estado_conservacao, descricao, fotos, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $id_usuario, $titulo, $categoria, $estado_conservacao, $descricao, $nome_foto, $status);

    if ($stmt->execute()) {
        // Redireciona direto para a aba de doações no perfil!
        echo "<script>
                alert('Doação cadastrada com sucesso! Obrigado pela sua generosidade.');
                window.location.href = 'home.php?aba=doacoes';
              </script>";
    } else {
        echo "<script>alert('Erro ao cadastrar doação. Tente novamente.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>