<?php
// Garante que a sessão está iniciada apenas uma vez
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/flash.php';
include_once('conexao.php'); 

// 1. Busca as configurações globais do sistema (Cores e Logo)
$sys_config = null;
if (isset($conn)) {
    $sql_config = "SELECT * FROM configuracoes WHERE id = 1";
    $resultado_config = $conn->query($sql_config);
    if ($resultado_config && $resultado_config->num_rows > 0) {
        $sys_config = $resultado_config->fetch_assoc();
    }
}

// 2. Lógica para puxar a foto do usuário logado para o cabeçalho
$foto_header = 'uploads/default.png'; // Caminho padrão caso não tenha foto
$tipo_usuario_header = 'usuario';
if (isset($_SESSION['usuario_id']) && isset($conn)) {
    $stmt_header = $conn->prepare("SELECT foto, tipo_usuario FROM usuarios WHERE id = ?");
    $stmt_header->bind_param("i", $_SESSION['usuario_id']);
    $stmt_header->execute();
    $resultado_header = $stmt_header->get_result();
    
    if ($resultado_header->num_rows > 0) {
        $usuario_header = $resultado_header->fetch_assoc();
        if (!empty($usuario_header['foto'])) {
            $foto_header = 'uploads/' . $usuario_header['foto'];
        }
        $tipo_usuario_header = $usuario_header['tipo_usuario'] ?? 'usuario';
    }
    $stmt_header->close();
}

$notificacoes_header = [];
$total_notificacoes_header = 0;
if (isset($_SESSION['usuario_id']) && isset($conn)) {
    $id_header = (int) $_SESSION['usuario_id'];

    $stmt_count_not = $conn->prepare("SELECT COUNT(*) AS total FROM mensagens WHERE id_destinatario = ? AND lida = 0");
    $stmt_count_not->bind_param("i", $id_header);
    $stmt_count_not->execute();
    $total_notificacoes_header = (int) $stmt_count_not->get_result()->fetch_assoc()['total'];
    $stmt_count_not->close();

    $stmt_msgs_not = $conn->prepare("SELECT m.id_remetente, m.mensagem, m.data_envio, u.nome
                                     FROM mensagens m
                                     JOIN usuarios u ON u.id = m.id_remetente
                                     WHERE m.id_destinatario = ? AND m.lida = 0
                                     ORDER BY m.data_envio DESC
                                     LIMIT 4");
    $stmt_msgs_not->bind_param("i", $id_header);
    $stmt_msgs_not->execute();
    $msgs_not = $stmt_msgs_not->get_result();
    while ($msg_not = $msgs_not->fetch_assoc()) {
        $notificacoes_header[] = [
            'icone' => 'fa-message',
            'cor' => 'blue',
            'titulo' => 'Nova mensagem de ' . $msg_not['nome'],
            'texto' => $msg_not['mensagem'],
            'link' => 'perfil.php?aba=mensagens&contato_id=' . (int) $msg_not['id_remetente']
        ];
    }
    $stmt_msgs_not->close();

    $stmt_match_pedido = $conn->prepare("SELECT d.titulo, u.nome AS nome_usuario, d.id_usuario
                                         FROM pedidos p
                                         JOIN doacoes d ON d.categoria = p.categoria AND d.id_usuario <> p.id_usuario AND d.status LIKE 'Dispon%'
                                         JOIN usuarios u ON u.id = d.id_usuario
                                         WHERE p.id_usuario = ? AND p.status = 'Aberto'
                                         ORDER BY d.data_cadastro DESC
                                         LIMIT 2");
    $stmt_match_pedido->bind_param("i", $id_header);
    $stmt_match_pedido->execute();
    $matches_pedido = $stmt_match_pedido->get_result();
    while ($match = $matches_pedido->fetch_assoc()) {
        $notificacoes_header[] = [
            'icone' => 'fa-bolt',
            'cor' => 'green',
            'titulo' => 'Item compativel encontrado',
            'texto' => $match['titulo'] . ' de ' . $match['nome_usuario'],
            'link' => 'perfil.php?aba=mensagens&contato_id=' . (int) $match['id_usuario']
        ];
    }
    $stmt_match_pedido->close();

    $stmt_match_doacao = $conn->prepare("SELECT p.titulo, u.nome AS nome_usuario, p.id_usuario
                                         FROM doacoes d
                                         JOIN pedidos p ON p.categoria = d.categoria AND p.id_usuario <> d.id_usuario AND p.status = 'Aberto'
                                         JOIN usuarios u ON u.id = p.id_usuario
                                         WHERE d.id_usuario = ? AND d.status LIKE 'Dispon%'
                                         ORDER BY p.data_cadastro DESC
                                         LIMIT 2");
    $stmt_match_doacao->bind_param("i", $id_header);
    $stmt_match_doacao->execute();
    $matches_doacao = $stmt_match_doacao->get_result();
    while ($match = $matches_doacao->fetch_assoc()) {
        $notificacoes_header[] = [
            'icone' => 'fa-hand-holding-heart',
            'cor' => 'blue',
            'titulo' => 'Projeto precisa do seu item',
            'texto' => $match['nome_usuario'] . ' pediu ' . $match['titulo'],
            'link' => 'perfil.php?aba=mensagens&contato_id=' . (int) $match['id_usuario']
        ];
    }
    $stmt_match_doacao->close();
}
?>

<?php if ($sys_config): ?>
<style>
    /* Força o sistema inteiro a usar essas variáveis de cor e fonte */
    :root {
        --neon-green: <?php echo htmlspecialchars($sys_config['cor_primaria']); ?> !important;
        --neon-blue: <?php echo htmlspecialchars($sys_config['cor_secundaria']); ?> !important;
        --bg-color: <?php echo htmlspecialchars($sys_config['cor_fundo']); ?> !important;
    }
    
    body, h1, h2, h3, p, a, input, button, select {
        font-family: '<?php echo htmlspecialchars($sys_config['fonte_principal']); ?>', sans-serif !important;
    }
    
    body, .container, .perfil-body-layout {
        background-color: var(--bg-color) !important;
    }
</style>
<?php endif; ?>

<header class="main-header">
    <div class="logo">
        <a href="home.php" id="logo-header-link" style="text-decoration: none;">
            <?php if (!empty($sys_config['logo_principal'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($sys_config['logo_principal']); ?>" alt="Logo Sistema" style="max-height: 40px;">
            <?php else: ?>
                <span style="color: var(--neon-blue);">DOA</span><span style="color: var(--neon-green);">TECH</span>
            <?php endif; ?>
        </a>
    </div>
    
    <nav class="nav-links">
        <a href="doar.php">Doar</a>
        <a href="receber.php">Receber</a>
        <a href="projetos.php">Projetos</a>
        <a href="sobre.php">Sobre</a>
    </nav>

    <div class="header-right" style="position: relative; display: flex; align-items: center; gap: 20px;">
        <div class="notification-container">
            <button type="button" id="notification-btn" class="notification-btn" aria-label="Abrir notificacoes">
                <i class="fa-regular fa-bell"></i>
                <?php if ($total_notificacoes_header > 0): ?>
                    <span class="notification-badge"><?php echo $total_notificacoes_header > 9 ? '9+' : $total_notificacoes_header; ?></span>
                <?php endif; ?>
            </button>

            <div id="notification-menu" class="notification-menu">
                <div class="notification-title-row">
                    <strong>Notificacoes</strong>
                    <?php if(isset($_SESSION['usuario_id']) && $total_notificacoes_header > 0): ?>
                        <form action="processar_notificacoes.php" method="POST">
                            <button type="submit" name="acao" value="marcar_mensagens_lidas">Marcar lidas</button>
                        </form>
                    <?php endif; ?>
                </div>

                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <?php if(count($notificacoes_header) > 0): ?>
                        <?php foreach(array_slice($notificacoes_header, 0, 7) as $notificacao): ?>
                            <a class="notification-item" href="<?php echo htmlspecialchars($notificacao['link']); ?>">
                                <span class="notification-icon <?php echo htmlspecialchars($notificacao['cor']); ?>">
                                    <i class="fa-solid <?php echo htmlspecialchars($notificacao['icone']); ?>"></i>
                                </span>
                                <span>
                                    <strong><?php echo htmlspecialchars($notificacao['titulo']); ?></strong>
                                    <small><?php echo htmlspecialchars(mb_strimwidth($notificacao['texto'], 0, 70, '...')); ?></small>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="notification-empty">
                            <i class="fa-regular fa-circle-check"></i>
                            <p>Tudo em dia por aqui.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="notification-empty">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <p>Entre na conta para ver mensagens e oportunidades.</p>
                        <a href="login.php">Fazer login</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <span style="cursor: pointer; font-size: 18px;">🔔</span>

        <?php if(isset($_SESSION['usuario_id'])): ?>
            <div class="user-dropdown-container" style="position: relative;">
                
                <div id="profile-btn" style="width: 40px; height: 40px; border-radius: 50%; background-image: url('<?php echo htmlspecialchars($foto_header); ?>'); background-size: cover; background-position: center; cursor: pointer; border: 2px solid var(--neon-blue); transition: 0.3s;">
                </div>

                <div id="dropdown-menu" style="display: none; position: absolute; top: 55px; right: 0; background: #11141d; border: 1px solid #1e293b; border-radius: 12px; width: 160px; box-shadow: 0 10px 30px rgba(0,0,0,0.8); z-index: 9999; overflow: hidden;">
                    <a href="perfil.php" class="dropdown-item" style="display: block; padding: 12px 16px; color: white; text-decoration: none; border-bottom: 1px solid #1e293b; font-size: 14px;">
                        <i class="fa-solid fa-user" style="margin-right: 10px; color: var(--neon-blue);"></i> Meu Perfil
                    </a>
                    <a href="logout.php" class="dropdown-item" style="display: block; padding: 12px 16px; color: #ef4444; text-decoration: none; font-size: 14px;">
                        <i class="fa-solid fa-right-from-bracket" style="margin-right: 10px;"></i> Sair
                    </a>
                </div>

            </div>
        <?php else: ?>
            <a href="login.php" class="perfil-tag" style="text-decoration: none; background-color: var(--neon-green); color: #05070a; padding: 10px 25px; border-radius: 25px; font-weight: bold; font-family: 'Inter', sans-serif;">Entrar</a>
        <?php endif; ?>

    </div>
</header>

<?php render_flash_message(); ?>

<style>
    .dropdown-item:hover {
        background-color: #1e293b;
    }
    #profile-btn:hover {
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
    }
    .header-right > span {
        display: none;
    }
    .notification-container {
        position: relative;
    }
    .notification-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 1px solid #1e293b;
        background: #11141d;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        transition: 0.2s ease;
    }
    .notification-btn:hover,
    .notification-btn.active {
        border-color: var(--neon-blue);
        color: var(--neon-blue);
        box-shadow: 0 0 14px rgba(56, 189, 248, 0.22);
    }
    .notification-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        border-radius: 999px;
        background: #ef4444;
        color: #ffffff;
        font-size: 10px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #0b0e14;
    }
    .notification-menu {
        display: none;
        position: absolute;
        top: 55px;
        right: 0;
        width: min(360px, calc(100vw - 30px));
        background: #11141d;
        border: 1px solid #1e293b;
        border-radius: 14px;
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.75);
        z-index: 9999;
        overflow: hidden;
    }
    .notification-menu.active {
        display: block;
    }
    .notification-title-row {
        padding: 16px;
        border-bottom: 1px solid #1e293b;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .notification-title-row strong {
        color: #ffffff;
        font-size: 14px;
    }
    .notification-title-row form {
        margin: 0;
    }
    .notification-title-row button,
    .notification-empty a {
        border: none;
        background: transparent;
        color: var(--neon-blue);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
    }
    .notification-item {
        padding: 14px 16px;
        display: flex;
        gap: 12px;
        text-decoration: none;
        border-bottom: 1px solid #1e293b;
        transition: 0.2s ease;
    }
    .notification-item:hover {
        background: rgba(56, 189, 248, 0.08);
    }
    .notification-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        flex: 0 0 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .notification-icon.blue {
        color: #38bdf8;
        background: rgba(56, 189, 248, 0.1);
    }
    .notification-icon.green {
        color: #4ade80;
        background: rgba(74, 222, 128, 0.1);
    }
    .notification-item strong {
        color: #ffffff;
        display: block;
        font-size: 13px;
        margin-bottom: 4px;
    }
    .notification-item small {
        color: #94a3b8;
        display: block;
        font-size: 12px;
        line-height: 1.35;
    }
    .notification-empty {
        padding: 26px 18px;
        color: #94a3b8;
        text-align: center;
    }
    .notification-empty i {
        color: var(--neon-green);
        font-size: 28px;
        margin-bottom: 10px;
    }
    .notification-empty p {
        margin: 0 0 10px;
        font-size: 13px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileBtn = document.getElementById('profile-btn');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const notificationBtn = document.getElementById('notification-btn');
        const notificationMenu = document.getElementById('notification-menu');

        if (notificationBtn && notificationMenu) {
            notificationBtn.addEventListener('click', function(event) {
                event.stopPropagation();
                notificationMenu.classList.toggle('active');
                notificationBtn.classList.toggle('active', notificationMenu.classList.contains('active'));
                if (dropdownMenu) {
                    dropdownMenu.style.display = 'none';
                }
            });
        }

        if (profileBtn && dropdownMenu) {
            profileBtn.addEventListener('click', function(event) {
                event.stopPropagation(); 
                if (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') {
                    dropdownMenu.style.display = 'block';
                    if (notificationMenu) {
                        notificationMenu.classList.remove('active');
                    }
                    if (notificationBtn) {
                        notificationBtn.classList.remove('active');
                    }
                } else {
                    dropdownMenu.style.display = 'none';
                }
            });

            document.addEventListener('click', function(event) {
                if (!profileBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.style.display = 'none';
                }
                if (notificationBtn && notificationMenu && !notificationBtn.contains(event.target) && !notificationMenu.contains(event.target)) {
                    notificationMenu.classList.remove('active');
                    notificationBtn.classList.remove('active');
                }
            });
        } else if (notificationBtn && notificationMenu) {
            document.addEventListener('click', function(event) {
                if (!notificationBtn.contains(event.target) && !notificationMenu.contains(event.target)) {
                    notificationMenu.classList.remove('active');
                    notificationBtn.classList.remove('active');
                }
            });
        }

        const expandableImages = document.querySelectorAll('.js-expand-image');

        if (expandableImages.length > 0) {
            const lightbox = document.createElement('div');
            lightbox.className = 'image-lightbox';
            lightbox.innerHTML = `
                <button type="button" class="image-lightbox-close" aria-label="Fechar imagem ampliada">&times;</button>
                <img class="image-lightbox-img" src="" alt="Imagem ampliada">
            `;
            document.body.appendChild(lightbox);

            const lightboxImg = lightbox.querySelector('.image-lightbox-img');
            const closeButton = lightbox.querySelector('.image-lightbox-close');

            function openLightbox(image) {
                lightboxImg.src = image.getAttribute('src');
                lightboxImg.alt = image.getAttribute('alt') || 'Imagem ampliada';
                lightbox.classList.add('active');
                document.body.classList.add('lightbox-open');
                closeButton.focus();
            }

            function closeLightbox() {
                lightbox.classList.remove('active');
                document.body.classList.remove('lightbox-open');
                lightboxImg.src = '';
            }

            expandableImages.forEach(function(image) {
                image.setAttribute('tabindex', '0');
                image.setAttribute('title', 'Clique para ampliar');

                image.addEventListener('click', function() {
                    openLightbox(image);
                });

                image.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        openLightbox(image);
                    }
                });
            });

            lightbox.addEventListener('click', function(event) {
                if (event.target === lightbox || event.target === closeButton) {
                    closeLightbox();
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && lightbox.classList.contains('active')) {
                    closeLightbox();
                }
            });
        }
    });
</script>
