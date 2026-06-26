<?php
session_start();
require_once __DIR__ . '/flash.php';
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
    redirect_with_flash('perfil.php?aba=gerenciamento', 'Tema restaurado para os padrões de fábrica com sucesso!', 'success');
} else {
    redirect_with_flash('perfil.php?aba=gerenciamento', 'Erro ao restaurar o tema. Tente novamente.', 'error');
}

$conn->close();
?>
