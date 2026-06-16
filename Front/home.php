<?php
// Inicia a sessão e conecta ao banco de dados ANTES de carregar o HTML
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'conexao.php'; 

// 1. Busca o ÚLTIMO PEDIDO CADASTRADO (Para o banner verde)
$sql_projeto = "SELECT p.*, u.nome AS ong_nome, u.localizacao 
                FROM pedidos p 
                JOIN usuarios u ON p.id_usuario = u.id 
                WHERE p.status = 'Aberto' 
                ORDER BY p.data_cadastro DESC LIMIT 1";
$resultado_projeto = $conn->query($sql_projeto);
$projeto_destaque = $resultado_projeto ? $resultado_projeto->fetch_assoc() : null;

// 2. Busca as ÚLTIMAS 2 DOAÇÕES CADASTRADAS (Para a parte debaixo)
$sql_doacoes = "SELECT d.*, u.nome AS doador_nome, u.localizacao 
                FROM doacoes d 
                JOIN usuarios u ON d.id_usuario = u.id 
                WHERE d.status = 'Disponível' 
                ORDER BY d.data_cadastro DESC LIMIT 2";
$resultado_doacoes = $conn->query($sql_doacoes);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>DoaTech - Home</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include('header.php'); ?>

<main class="container">
    
    <section>
        <h2 style="color: var(--neon-blue);">QUERO DOAR</h2>
        
        <div class="dark-card">
            <p style="color: #ffffff; font-size: 14px;">Acompanhe suas doações</p>
            <div class="progress-container">
                <div class="progress-bar" style="width: 77%;">
                    
                </div>
            </div>
        </div>

        <div class="banner-urgencia">
            <?php if ($projeto_destaque): ?>
                <h3 style="font-size: 22px; line-height: 1.4;"><?php echo htmlspecialchars($projeto_destaque['titulo']); ?></h3>
                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                    <span style="color: #4ade80; font-weight: bold; font-size: 13px;">DESTAQUES</span>
                    <a href="projetos.php?busca=<?php echo urlencode($projeto_destaque['titulo']); ?>" class="btn-link-ajudar">Ajudar agora</a>
                </div>
            <?php else: ?>
                <h3 style="font-size: 22px; line-height: 1.4;">Nenhum projeto urgente no momento</h3>
                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                    <span style="color: #4ade80; font-weight: bold; font-size: 13px;">DESTAQUES</span>
                    <a href="projetos.php" class="btn-link-ajudar">Ver projetos</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section>
        <h2 style="color: var(--neon-green);">QUERO RECEBER</h2>
        
        <div class="dark-card">
            <p style="color: #ffffff; font-size: 14px; margin-bottom: 20px;">Sua lista de Desejos</p>
            
            <div style="margin-bottom: 25px;">
                <small style="display: block; margin-bottom: 8px;">Computador para Escola</small>
                <div class="progress-container" style="height: 10px;">
                    <div class="progress-bar" style="width: 100%;"></div>
                </div>
            </div>

            <div>
                <small style="display: block; margin-bottom: 8px;">Teclado para Escola</small>
                <div class="progress-container" style="height: 10px;">
                    <div class="progress-bar" style="width: 50%; background: var(--neon-blue);"></div>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 50px; margin-top: 20px;">
            <div>
                <span style="color: #05ff59; font-size: 14px;">Vidas transformadas</span>
                <p style="font-size: 28px; font-weight: bold; color: var(--neon-green); margin-top: 5px;">1.250</p>
            </div>
            <div>
                <span style="color: #24558d; font-size: 14px;">Dispositivos Doados</span>
                <p style="font-size: 28px; font-weight: bold; color: var(--neon-blue); margin-top: 5px;">450</p>
            </div>
        </div>
    </section>

    <section class="destaques-area">
        <h2 style="font-size: 22px; margin-bottom: 30px;">
            DESTAQUES <span style="color: var(--neon-blue);">DE DOAÇÕES</span>
        </h2>

        <div class="destaque-grid">
            <?php if ($resultado_doacoes && $resultado_doacoes->num_rows > 0): ?>
                <?php while($doacao = $resultado_doacoes->fetch_assoc()): 
                    // Escolhe um ícone genérico dependendo do nome do item
                    $icone_doacao = (stripos($doacao['titulo'], 'computador') !== false || stripos($doacao['titulo'], 'monitor') !== false) ? '💻' : '📦';
                    if(stripos($doacao['titulo'], 'ferramenta') !== false) $icone_doacao = '🔧';
                    if(stripos($doacao['titulo'], 'escolar') !== false || stripos($doacao['titulo'], 'livro') !== false) $icone_doacao = '📘';
                ?>
                    <div class="item-card">
                        <div class="item-icon"><?php echo $icone_doacao; ?></div>
                        <div class="item-info">
                            <strong>Doação: <?php echo htmlspecialchars($doacao['titulo']); ?></strong>
                            <small>Disponível por <?php echo htmlspecialchars($doacao['doador_nome']); ?> em <?php echo !empty($doacao['localizacao']) ? htmlspecialchars($doacao['localizacao']) : 'Local não informado'; ?></small>
                            <div class="btn-group">
                                <a href="projetos.php?busca=<?php echo urlencode($doacao['titulo']); ?>" class="btn-green" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Demonstrar interesse</a>
                                <button class="btn-outline">Ver Detalhes</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #94a3b8; font-size: 14px;">Nenhuma doação disponível no momento.</p>
            <?php endif; ?>
        </div>
    </section>

</main>

</body>
</html>