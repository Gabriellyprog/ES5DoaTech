<?php
// conexao.php
$servidor = "localhost";
$usuario = "root"; // Padrão do XAMPP
$senha = ""; // Padrão do XAMPP geralmente é vazio
$banco = "doatech_db";

// Cria a conexão
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o Banco de Dados: " . $conn->connect_error);
}

// Define o padrão de caracteres para evitar erros de acentuação (ç, ã, etc)
$conn->set_charset("utf8");
?>