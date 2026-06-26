<?php
session_start();
require_once __DIR__ . '/flash.php';

if (!isset($_SESSION['usuario_id'])) {
    redirect_with_flash('login.php', 'Você precisa estar logado para fazer uma doação.', 'warning');
}

include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = (int) $_SESSION['usuario_id'];
    $titulo = trim($_POST['titulo']);
    $categoria = trim($_POST['categoria']);
    $estado_conservacao = trim($_POST['estado_conservacao']);
    $descricao = trim($_POST['descricao']);
    $status = 'Disponível';
    $nome_foto = null;

    if (isset($_FILES['foto_item']) && $_FILES['foto_item']['error'] === UPLOAD_ERR_OK) {
        $pasta_destino = __DIR__ . "/uploads/";
        if (!is_dir($pasta_destino)) {
            mkdir($pasta_destino, 0777, true);
        }

        $extensao = strtolower(pathinfo($_FILES['foto_item']['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($extensao, $extensoes_permitidas, true)) {
            $nome_foto = "item_" . time() . "_" . rand(100, 999) . "." . $extensao;
            $caminho_final = $pasta_destino . $nome_foto;

            if (!move_uploaded_file($_FILES['foto_item']['tmp_name'], $caminho_final)) {
                $nome_foto = null;
            }
        }
    }

    $sql = "INSERT INTO doacoes (id_usuario, titulo, categoria, estado_conservacao, descricao, fotos, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $id_usuario, $titulo, $categoria, $estado_conservacao, $descricao, $nome_foto, $status);

    if ($stmt->execute()) {
        redirect_with_flash('perfil.php?aba=doacoes', 'Doação cadastrada com sucesso! Obrigado pela sua generosidade.', 'success');
    } else {
        redirect_with_flash('doar.php', 'Erro ao cadastrar doação. Tente novamente.', 'error');
    }

    $stmt->close();
    $conn->close();
}
?>
