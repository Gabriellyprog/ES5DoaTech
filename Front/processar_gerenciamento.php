<?php
session_start();
include 'conexao.php';

// Proteção básica: aqui num sistema real você checaria se o usuário é "Admin"
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $cor_primaria = $_POST['cor_primaria'];
$cor_secundaria = $_POST['cor_secundaria'];
$cor_fundo = $_POST['cor_fundo'];
$cor_card = $_POST['cor_card'];
$cor_texto = $_POST['cor_texto'];
$cor_texto_secundario = $_POST['cor_texto_secundario'];
$cor_borda = $_POST['cor_borda'];
$cor_input = $_POST['cor_input'];
$cor_header = $_POST['cor_header'];
$fonte_principal = $_POST['fonte_principal'];

$sql = "UPDATE configuracoes 
        SET cor_primaria = ?, 
            cor_secundaria = ?, 
            cor_fundo = ?, 
            cor_card = ?, 
            cor_texto = ?, 
            cor_texto_secundario = ?, 
            cor_borda = ?, 
            cor_input = ?, 
            cor_header = ?, 
            fonte_principal = ? 
        WHERE id = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssssss",
    $cor_primaria,
    $cor_secundaria,
    $cor_fundo,
    $cor_card,
    $cor_texto,
    $cor_texto_secundario,
    $cor_borda,
    $cor_input,
    $cor_header,
    $fonte_principal
);
$stmt->execute();
    $pasta_destino = "uploads/";
    if (!is_dir($pasta_destino)) { mkdir($pasta_destino, 0777, true); }

    // 2. Upload da Logo Principal
    if (isset($_FILES['logo_principal']) && $_FILES['logo_principal']['error'] == 0) {
        $ext = pathinfo($_FILES['logo_principal']['name'], PATHINFO_EXTENSION);
        $nome_logo = "logo_sys_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES['logo_principal']['tmp_name'], $pasta_destino . $nome_logo)) {
            $conn->query("UPDATE configuracoes SET logo_principal = '$nome_logo' WHERE id = 1");
        }
    }

    // 3. Upload do Favicon
    if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] == 0) {
        $ext = pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION);
        $nome_favicon = "fav_sys_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES['favicon']['tmp_name'], $pasta_destino . $nome_favicon)) {
            $conn->query("UPDATE configuracoes SET favicon = '$nome_favicon' WHERE id = 1");
        }
    }

    echo "<script>
            alert('Identidade Visual atualizada com sucesso! As mudanças já estão ativas.');
            window.location.href = 'perfil.php?aba=gerenciamento';
          </script>";
    exit();
}
?>