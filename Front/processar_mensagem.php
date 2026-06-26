<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_remetente = (int) $_SESSION['usuario_id'];
    $id_destinatario = intval($_POST['id_destinatario'] ?? 0);
    $mensagem = trim($_POST['mensagem'] ?? '');

    if (!empty($mensagem) && $id_destinatario > 0 && $id_destinatario !== $id_remetente) {
        $stmt_usuario = $conn->prepare("SELECT id FROM usuarios WHERE id = ? LIMIT 1");
        $stmt_usuario->bind_param("i", $id_destinatario);
        $stmt_usuario->execute();
        $destinatario_existe = $stmt_usuario->get_result()->num_rows > 0;
        $stmt_usuario->close();

        if ($destinatario_existe) {
            $sql = "INSERT INTO mensagens (id_remetente, id_destinatario, mensagem) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $id_remetente, $id_destinatario, $mensagem);
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: perfil.php?aba=mensagens&contato_id=" . $id_destinatario);
    exit();
}
?>
