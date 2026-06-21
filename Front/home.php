<?php
// Inicia a sessão e conecta ao banco de dados ANTES de carregar o HTML
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'conexao.php'; 

// ==========================================
// 1. LÓGICA DA VITRINE (Banners)
// ==========================================
$sql_doacao_destaque = "SELECT d.*, u.nome AS doador_nome, u.localizacao FROM doacoes d JOIN usuarios u ON d.id_usuario = u.id WHERE d.status = 'Disponível' ORDER BY d.data_cadastro DESC LIMIT 1";
$resultado_doacao_destaque = $conn->query($sql_doacao_destaque);
$doacao_destaque = $resultado_doacao_destaque ? $resultado_doacao_destaque->fetch_assoc() : null;

$sql_pedidos = "SELECT p.*, u.nome AS ong_nome, u.localizacao FROM pedidos p JOIN usuarios u ON p.id_usuario = u.id WHERE p.status = 'Aberto' ORDER BY p.data_cadastro DESC LIMIT 2";
$resultado_pedidos = $conn->query($sql_pedidos);

// ==========================================
// 2. LÓGICA DO PROGRESSO DO USUÁRIO
// ==========================================
$id_usuario_logado = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;

// Progresso de Doações (Meta de 5 doações)
$progresso_doacoes = 0;
$qtd_doacoes_usuario = 0;
if ($id_usuario_logado > 0) {
    $sql_minhas_doacoes = "SELECT COUNT(*) as total FROM doacoes WHERE id_usuario = $id_usuario_logado";
    $qtd_doacoes_usuario = $conn->query($sql_minhas_doacoes)->fetch_assoc()['total'];
    $progresso_doacoes = min(100, ($qtd_doacoes_usuario / 5) * 100); // Enche a barra até 100%
}

// Lista de Desejos (Meus Pedidos)
$meus_pedidos = [];
if ($id_usuario_logado > 0) {
    $sql_meus_pedidos = "SELECT titulo, status FROM pedidos WHERE id_usuario = $id_usuario_logado ORDER BY data_cadastro DESC LIMIT 2";
    $res_meus_pedidos = $conn->query($sql_meus_pedidos);
    if ($res_meus_pedidos) {
        while($row = $res_meus_pedidos->fetch_assoc()) {
            $meus_pedidos[] = $row;
        }
    }
}

// ==========================================
// 3. ESTATÍSTICAS GLOBAIS DA PLATAFORMA
// ==========================================
$total_dispositivos = 0;
$res_stats = $conn->query("SELECT COUNT(*) as total FROM doacoes");
if ($res_stats) {
    $total_dispositivos = $res_stats->fetch_assoc()['total'];
}
$vidas_transformadas = $total_dispositivos * 4; // Exemplo: cada doação impacta 4 vidas
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>DoaTech - Home</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include('tema.php'); ?>
</head>
<body>

<?php include('header.php'); ?>

<main class="container">
    
    <section>
        <h2 style="color: var(--neon-blue);">QUERO DOAR</h2>
        
        <div class="dark-card">
            <p style="color: #ffffff; font-size: 14px; margin-bottom: 5px;">Acompanhe suas doações</p>
            <small style="color: #94a3b8; display: block; margin-bottom: 15px;">
                <?php echo $id_usuario_logado > 0 ? "$qtd_doacoes_usuario de 5 doações para a meta Ouro" : "Faça login para ver sua meta"; ?>
            </small>
            <div class="progress-container">
                <div class="progress-bar" style="width: <?php echo $progresso_doacoes; ?>%;"></div>
            </div>
        </div>

        <div class="banner-urgencia">
            <?php if ($doacao_destaque): ?>
                <div class="banner-doacao-content">
                    <?php if (!empty($doacao_destaque['fotos'])): ?>
                        <div class="banner-doacao-photo-wrap">
                            <img class="banner-doacao-photo js-expand-image" src="uploads/<?php echo htmlspecialchars($doacao_destaque['fotos']); ?>" alt="Foto da doação">
                        </div>
                    <?php endif; ?>

                    <div class="banner-doacao-info">
                        <h3 style="font-size: 22px; line-height: 1.4;"><?php echo htmlspecialchars($doacao_destaque['titulo']); ?></h3>
                        <p style="color: #ffffff; font-size: 14px; margin-bottom: 15px; opacity: 0.9;">Ofertado por: <?php echo htmlspecialchars($doacao_destaque['doador_nome']); ?></p>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                    <span style="color: #4ade80; font-weight: bold; font-size: 13px;">ITEM DISPONÍVEL</span>
                    <a href="perfil.php?aba=mensagens&contato_id=<?php echo $doacao_destaque['id_usuario']; ?>&nome_contato=<?php echo urlencode($doacao_destaque['doador_nome']); ?>" class="btn-link-ajudar">Quero este item</a>
                </div>
            <?php else: ?>
                <h3 style="font-size: 22px; line-height: 1.4;">Nenhuma doação no momento</h3>
                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                    <span style="color: #4ade80; font-weight: bold; font-size: 13px;">NOVIDADES</span>
                    <a href="doar.php" class="btn-link-ajudar">Faça uma doação</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section>
        <h2 style="color: var(--neon-green);">QUERO RECEBER</h2>
        
        <div class="dark-card">
            <p style="color: #ffffff; font-size: 14px; margin-bottom: 20px;">Sua lista de Desejos</p>
            
            <?php if ($id_usuario_logado > 0 && count($meus_pedidos) > 0): ?>
                <?php foreach($meus_pedidos as $index => $pedido): 
                    // Regra de % visual
                    $pct = 25; 
                    if(strtolower($pedido['status']) == 'em trânsito') $pct = 75;
                    if(strtolower($pedido['status']) == 'concluído') $pct = 100;
                    
                    // Alterna as cores da barra para ficar bonito
                    $cor = ($index == 0) ? 'linear-gradient(90deg, var(--neon-green), var(--neon-blue))' : 'var(--neon-blue)';
                ?>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <small style="color: white;"><?php echo htmlspecialchars($pedido['titulo']); ?></small>
                            <small style="color: #94a3b8; font-size: 11px;"><?php echo strtoupper($pedido['status']); ?></small>
                        </div>
                        <div class="progress-container" style="height: 10px;">
                            <div class="progress-bar" style="width: <?php echo $pct; ?>%; background: <?php echo $cor; ?>;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php elseif ($id_usuario_logado == 0): ?>
                <p style="color: #94a3b8; font-size: 13px;">Faça login para ver a sua lista de pedidos.</p>
            <?php else: ?>
                <p style="color: #94a3b8; font-size: 13px;">Você ainda não solicitou nenhum equipamento.</p>
            <?php endif; ?>
        </div>

        <div style="display: flex; gap: 50px; margin-top: 20px;">
            <div>
                <span style="color: #05ff59; font-size: 14px;">Vidas transformadas</span>
                <p style="font-size: 28px; font-weight: bold; color: var(--neon-green); margin-top: 5px;"><?php echo number_format($vidas_transformadas, 0, ',', '.'); ?></p>
            </div>
            <div>
                <span style="color: #24558d; font-size: 14px;">Dispositivos Doados</span>
                <p style="font-size: 28px; font-weight: bold; color: var(--neon-blue); margin-top: 5px;"><?php echo number_format($total_dispositivos, 0, ',', '.'); ?></p>
            </div>
        </div>
    </section>

    <section class="destaques-area">
        <h2 style="font-size: 22px; margin-bottom: 30px;">
            DESTAQUES <span style="color: var(--neon-blue);">DE PROJETOS</span>
        </h2>

        <div class="destaque-grid">
            <?php if ($resultado_pedidos && $resultado_pedidos->num_rows > 0): ?>
                <?php while($pedido = $resultado_pedidos->fetch_assoc()): 
                    $icone_pedido = '🤝';
                    if(stripos($pedido['categoria'], 'inform') !== false || stripos($pedido['titulo'], 'computador') !== false) $icone_pedido = '💻';
                    if(stripos($pedido['categoria'], 'ferramenta') !== false) $icone_pedido = '🔧';
                    if(stripos($pedido['categoria'], 'escolar') !== false || stripos($pedido['titulo'], 'livro') !== false) $icone_pedido = '📘';
                ?>
                    <div class="item-card">
                        <div class="item-icon"><?php echo $icone_pedido; ?></div>
                        <div class="item-info">
                            <strong>Projeto: <?php echo htmlspecialchars($pedido['titulo']); ?></strong>
                            <small>Necessidade de <?php echo htmlspecialchars($pedido['ong_nome']); ?> em <?php echo !empty($pedido['localizacao']) ? htmlspecialchars($pedido['localizacao']) : 'Local não informado'; ?></small>
                            <div class="btn-group">
                                <a href="projetos.php?busca=<?php echo urlencode($pedido['titulo']); ?>" class="btn-green" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Ajudar Projeto</a>
                                <button class="btn-outline">Ver Detalhes</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #94a3b8; font-size: 14px;">Nenhum projeto buscando ajuda no momento.</p>
            <?php endif; ?>
        </div>
    </section>

</main>

</body>
</html>
