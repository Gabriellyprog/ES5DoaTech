<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Atualiza a tabela configuracoes (id=1) para os valores padrão do nosso código
$sql = "UPDATE configuracoes SET 
        logo_principal = '', 
        favicon = '', 
        cor_primaria = '#4ade80', 
        cor_secundaria = '#38bdf8', 
        cor_fundo = '#05070a', 
        fonte_principal = 'Inter' 
        WHERE id = 1";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('Tema restaurado para os padrões de fábrica com sucesso!');
            window.location.href = 'perfil.php?aba=gerenciamento';
          </script>";
} else {
    echo "<script>
            alert('Erro ao restaurar o tema. Tente novamente.');
            window.history.back();
          </script>";
}

$conn->close();
?>