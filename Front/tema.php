<?php
if (!isset($conn)) {
    include_once('conexao.php');
}

$tema = [
    'cor_primaria' => '#4ade80',
    'cor_secundaria' => '#38bdf8',
    'cor_fundo' => '#05070a',
    'cor_card' => '#161b22',
    'cor_texto' => '#ffffff',
    'cor_texto_secundario' => '#94a3b8',
    'cor_borda' => '#30363d',
    'cor_input' => '#05070a',
    'cor_header' => '#0b0e14',
    'fonte_principal' => 'Inter'
];

$resultado_tema = $conn->query("SELECT * FROM configuracoes WHERE id = 1");

if ($resultado_tema && $resultado_tema->num_rows > 0) {
    $tema = array_merge($tema, $resultado_tema->fetch_assoc());
}
?>

<style>
:root {
    --cor-primaria: <?php echo htmlspecialchars($tema['cor_primaria']); ?>;
    --cor-secundaria: <?php echo htmlspecialchars($tema['cor_secundaria']); ?>;
    --cor-fundo: <?php echo htmlspecialchars($tema['cor_fundo']); ?>;
    --cor-card: <?php echo htmlspecialchars($tema['cor_card']); ?>;
    --cor-texto: <?php echo htmlspecialchars($tema['cor_texto']); ?>;
    --cor-texto-secundario: <?php echo htmlspecialchars($tema['cor_texto_secundario']); ?>;
    --cor-borda: <?php echo htmlspecialchars($tema['cor_borda']); ?>;
    --cor-input: <?php echo htmlspecialchars($tema['cor_input']); ?>;
    --cor-header: <?php echo htmlspecialchars($tema['cor_header']); ?>;

    --neon-green: var(--cor-primaria);
    --neon-blue: var(--cor-secundaria);
    --bg-color: var(--cor-fundo);
    --bg-dark: var(--cor-fundo);
    --card-bg: var(--cor-card);
    --border: var(--cor-borda);
}

body, h1, h2, h3, p, a, input, button, select, textarea {
    font-family: '<?php echo htmlspecialchars($tema['fonte_principal']); ?>', sans-serif !important;
}

body,
.doar-body,
.receber-body,
.proj-body,
.sobre-body,
.perfil-body-layout {
    background-color: var(--cor-fundo) !important;
    color: var(--cor-texto) !important;
}

.main-header {
    background-color: var(--cor-header) !important;
    border-color: var(--cor-borda) !important;
}

.perfil-card-box,
.doar-card,
.receber-card,
.proj-card,
.sobre-card,
.proj-filters,
.p-chat-sidebar,
.p-chat-window,
.auth-box {
    background-color: var(--cor-card) !important;
    border-color: var(--cor-borda) !important;
}

input,
select,
textarea,
.p-form-input {
    background-color: var(--cor-input) !important;
    color: var(--cor-texto) !important;
    border-color: var(--cor-borda) !important;
}

.txt-green,
.p-section-title-green,
.p-val-green {
    color: var(--cor-primaria) !important;
}

.txt-blue,
.p-section-title-blue,
.p-val-blue {
    color: var(--cor-secundaria) !important;
}

p,
small,
label,
.p-label {
    color: var(--cor-texto-secundario);
}
</style>