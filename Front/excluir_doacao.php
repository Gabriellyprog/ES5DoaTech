<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: perfil.php?aba=doacoes');
    exit();
}

$id_usuario = (int) $_SESSION['usuario_id'];
$id_doacao = isset($_POST['id_doacao']) ? (int) $_POST['id_doacao'] : 0;

if ($id_doacao <= 0) {
    echo "<script>
            alert('Doação inválida.');
            window.location.href = 'perfil.php?aba=doacoes';
          </script>";
    exit();
}

$sql_busca = 'SELECT fotos FROM doacoes WHERE id = ? AND id_usuario = ?';
$stmt_busca = $conn->prepare($sql_busca);
$stmt_busca->bind_param('ii', $id_doacao, $id_usuario);
$stmt_busca->execute();
$resultado = $stmt_busca->get_result();

if ($resultado->num_rows === 0) {
    echo "<script>
            alert('Doação não encontrada ou você não tem permissão para excluir.');
            window.location.href = 'perfil.php?aba=doacoes';
          </script>";
    exit();
}

$doacao = $resultado->fetch_assoc();
$stmt_busca->close();

$sql_delete = 'DELETE FROM doacoes WHERE id = ? AND id_usuario = ?';
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param('ii', $id_doacao, $id_usuario);

if ($stmt_delete->execute()) {
    if (!empty($doacao['fotos'])) {
        $caminho_foto = __DIR__ . '/uploads/' . basename($doacao['fotos']);

        if (is_file($caminho_foto)) {
            unlink($caminho_foto);
        }
    }

    echo "<script>
            alert('Doação excluída com sucesso!');
            window.location.href = 'perfil.php?aba=doacoes';
          </script>";
} else {
    echo "<script>
            alert('Erro ao excluir doação.');
            window.location.href = 'perfil.php?aba=doacoes';
          </script>";
}

$stmt_delete->close();
$conn->close();
