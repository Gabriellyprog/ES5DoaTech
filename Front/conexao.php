<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "doatech_db";

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Nao foi possivel conectar ao banco de dados agora. Tente novamente em alguns instantes.");
}

$conn->set_charset("utf8");
?>
