<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST['acao'] ?? '') === 'marcar_mensagens_lidas') {
    $id_usuario = (int) $_SESSION['usuario_id'];
    $stmt = $conn->prepare("UPDATE mensagens SET lida = 1 WHERE id_destinatario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->close();
}

$destino = $_SERVER['HTTP_REFERER'] ?? 'perfil.php?aba=mensagens';
header("Location: " . $destino);
exit();
?>
