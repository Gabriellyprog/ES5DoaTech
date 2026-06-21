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
        cor_card = '#161b22',
        cor_texto = '#ffffff',
        cor_texto_secundario = '#94a3b8',
        cor_borda = '#30363d',
        cor_input = '#05070a',
        cor_header = '#0b0e14',
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