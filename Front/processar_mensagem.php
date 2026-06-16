<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_remetente = $_SESSION['usuario_id'];
    $id_destinatario = intval($_POST['id_destinatario']);
    $mensagem = trim($_POST['mensagem']);
    $nome_contato = $_POST['nome_contato']; // Para não perder o nome na URL ao redirecionar

    if (!empty($mensagem) && $id_destinatario > 0) {
        $sql = "INSERT INTO mensagens (id_remetente, id_destinatario, mensagem) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $id_remetente, $id_destinatario, $mensagem);
        $stmt->execute();
        $stmt->close();
    }

    // Redireciona de volta para a mesma conversa
    header("Location: perfil.php?aba=mensagens&contato_id=" . $id_destinatario . "&nome_contato=" . urlencode($nome_contato));
    exit();
}
?>