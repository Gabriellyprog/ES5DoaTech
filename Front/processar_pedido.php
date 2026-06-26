<?php
// processar_pedido.php
session_start();
require_once __DIR__ . '/flash.php';

if (!isset($_SESSION['usuario_id'])) {
    redirect_with_flash('login.php', 'Você precisa estar logado para solicitar uma doação.', 'warning');
}

include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['usuario_id'];
    $titulo = $_POST['titulo'];
    $categoria = $_POST['categoria'];
    $urgencia = $_POST['urgencia'];
    $historia = $_POST['historia'];
    $status = 'Aberto'; // O pedido nasce em aberto

    $nome_comprovante = null;

    // Upload de documento ou foto da ONG
    if (isset($_FILES['comprovantes']) && $_FILES['comprovantes']['error'] == 0) {
        $pasta_destino = "uploads/";
        if (!is_dir($pasta_destino)) { mkdir($pasta_destino, 0777, true); }

        $extensao = pathinfo($_FILES['comprovantes']['name'], PATHINFO_EXTENSION);
        $nome_comprovante = "doc_" . time() . "_" . rand(100, 999) . "." . $extensao;
        
        move_uploaded_file($_FILES['comprovantes']['tmp_name'], $pasta_destino . $nome_comprovante);
    }

    $sql = "INSERT INTO pedidos (id_usuario, titulo, categoria, urgencia, historia, comprovantes, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $id_usuario, $titulo, $categoria, $urgencia, $historia, $nome_comprovante, $status);

    if ($stmt->execute()) {
        redirect_with_flash('projetos.php', 'Pedido registrado com sucesso! Ele aparecerá no mural em breve.', 'success');
    } else {
        redirect_with_flash('receber.php', 'Erro ao registrar o pedido. Tente novamente.', 'error');
    }

    $stmt->close();
    $conn->close();
}
?>
