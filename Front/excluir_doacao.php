<?php
session_start();
require_once __DIR__ . '/flash.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: perfil.php?aba=doacoes");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$id_doacao = isset($_POST['id_doacao']) ? intval($_POST['id_doacao']) : 0;

if ($id_doacao <= 0) {
    redirect_with_flash('perfil.php?aba=doacoes', 'Doação inválida.', 'error');
}

$sql_busca = "SELECT fotos FROM doacoes WHERE id = ? AND id_usuario = ?";
$stmt_busca = $conn->prepare($sql_busca);
$stmt_busca->bind_param("ii", $id_doacao, $id_usuario);
$stmt_busca->execute();
$resultado = $stmt_busca->get_result();

if ($resultado->num_rows === 0) {
    redirect_with_flash('perfil.php?aba=doacoes', 'Doação não encontrada ou você não tem permissão para excluir.', 'error');
}

$doacao = $resultado->fetch_assoc();
$stmt_busca->close();

$sql_delete = "DELETE FROM doacoes WHERE id = ? AND id_usuario = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("ii", $id_doacao, $id_usuario);

if ($stmt_delete->execute()) {
    if (!empty($doacao['fotos'])) {
        $caminho_foto = __DIR__ . "/uploads/" . basename($doacao['fotos']);

        if (is_file($caminho_foto)) {
            unlink($caminho_foto);
        }
    }

    redirect_with_flash('perfil.php?aba=doacoes', 'Doação excluída com sucesso!', 'success');
} else {
    redirect_with_flash('perfil.php?aba=doacoes', 'Erro ao excluir doação. Tente novamente.', 'error');
}

$stmt_delete->close();
$conn->close();
?>
