<?php
// logout.php
session_start(); // Acessa a sessão atual
session_destroy(); // Destrói todas as informações de login
header("Location: home.php"); // Redireciona o usuário de volta para a Home (ou index.php)
exit();
?>