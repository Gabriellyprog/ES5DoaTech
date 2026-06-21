<?php 
// Inicia a sessão e protege a página: se não estiver logado, manda para o login
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

include('conexao.php'); 

// 1. Busca os dados atualizados do usuário logado
$id_usuario = $_SESSION['usuario_id'];
$query_user = $conn->prepare("SELECT nome, email, telefone, localizacao, foto FROM usuarios WHERE id = ?");
$query_user->bind_param("i", $id_usuario);
$query_user->execute();
$dados_usuario = $query_user->get_result()->fetch_assoc();

// 2. Busca a quantidade real de doações que este usuário já fez
$query_count = $conn->prepare("SELECT COUNT(*) as total FROM doacoes WHERE id_usuario = ?");
$query_count->bind_param("i", $id_usuario);
$query_count->execute();
$total_doacoes = $query_count->get_result()->fetch_assoc()['total'];
$query_config = $conn->query("SELECT * FROM configuracoes WHERE id = 1");
$config = $query_config->fetch_assoc();

// Lógica para definir qual aba está ativa na navegação
$aba = isset($_GET['aba']) ? $_GET['aba'] : 'perfil';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>DoaTech - Perfil</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include('tema.php'); ?>
</head>
<body class="perfil-body-layout">

    <?php include('header.php'); ?>

    <div class="perfil-wrapper">
        <aside class="perfil-sidebar">
            <div class="perfil-user-header">
                <div class="perfil-avatar-frame" style="background-image: url('uploads/<?php echo !empty($dados_usuario['foto']) ? $dados_usuario['foto'] : 'default.png'; ?>'); background-size: cover; background-position: center;">
                    <?php if(empty($dados_usuario['foto'])): ?>
                        <i class="fa-solid fa-user" style="font-size: 40px; color: #64748b;"></i>
                    <?php endif; ?>
                </div>
                <h3><?php echo htmlspecialchars($dados_usuario['nome']); ?></h3>
                <p class="p-status">Doador Ativo</p>
                <p class="p-status-sub">(Status)</p>
            </div>
            
            <nav class="perfil-nav-menu">
                <a href="perfil.php?aba=perfil" class="p-nav-item <?php echo ($aba == 'perfil') ? 'active' : ''; ?>">
                    <i class="fa-regular fa-user"></i> Perfil
                </a>
                <a href="perfil.php?aba=doacoes" class="p-nav-item <?php echo ($aba == 'doacoes') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-box-archive"></i> Minhas Doações
                </a>
                <a href="perfil.php?aba=mensagens" class="p-nav-item <?php echo ($aba == 'mensagens') ? 'active' : ''; ?>">
                    <i class="fa-regular fa-comment"></i> Mensagens
                </a>
                <a href="perfil.php?aba=config" class="p-nav-item <?php echo ($aba == 'config') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-gear"></i> Configurações
                </a>
                <a href="perfil.php?aba=gerenciamento" class="p-nav-item <?php echo ($aba == 'gerenciamento') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-sliders"></i> Painel Gerenciamento
                </a>
            </nav>
        </aside>

        <main class="perfil-main-container">
            
            <?php if ($aba == 'perfil'): ?>
                <div class="p-top-hero">
                    <div class="p-top-hero">
                    <div class="p-hero-circle" style="background-image: url('uploads/<?php echo !empty($dados_usuario['foto']) ? $dados_usuario['foto'] : 'default.png'; ?>'); background-size: cover; background-position: center;"></div>
                </div>
                </div>

                <div class="perfil-card-box">
                    <h2 class="p-section-title-blue">Estatísticas de impacto</h2>
                    <div class="p-stats-grid">
                        <div class="p-stat-box">
                            <span class="p-label">Doações Realizadas</span>
                            <p class="p-val-green"><?php echo $total_doacoes; ?></p>
                        </div>
                        <div class="p-v-line"></div>
                        <div class="p-stat-box">
                            <span class="p-label">Vidas Impactadas</span>
                            <p class="p-val-blue"><?php echo $total_doacoes * 4; // Simulação de impacto ?></p>
                        </div>
                        <div class="p-v-line"></div>
                        <div class="p-stat-box">
                            <span class="p-label">Pontuação de Impacto</span>
                            <div class="p-impact-icon">💙</div>
                        </div>
                    </div>
                </div>

                <div class="p-info-row">
                    <div class="perfil-card-box">
                        <h2 class="p-section-title-blue">Informações Pessoais</h2>
                        <div class="p-data-field">
                            <label>Nome</label>
                            <p><?php echo htmlspecialchars($dados_usuario['nome']); ?></p>
                        </div>
                        <div class="p-data-field">
                            <label>Email</label>
                            <p><?php echo htmlspecialchars($dados_usuario['email']); ?></p>
                        </div>
                    </div>

                    <div class="perfil-card-box">
                        <h2 class="p-section-title-green">Dados de Contato</h2>
                        <div class="p-data-field">
                            <label>Telefone</label>
                            <p><?php echo !empty($dados_usuario['telefone']) ? htmlspecialchars($dados_usuario['telefone']) : 'Não informado'; ?></p>
                        </div>
                        <div class="p-data-field">
                            <label>Localização</label>
                            <p><?php echo !empty($dados_usuario['localizacao']) ? htmlspecialchars($dados_usuario['localizacao']) : 'Não informada'; ?></p>
                        </div>
                    </div>
                </div>

            <?php elseif ($aba == 'doacoes'): ?>
                <div class="perfil-card-box">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                        <h2 class="p-section-title-blue" style="margin-bottom: 0;">MINHAS DOAÇÕES</h2>
                    </div>

                    <div class="p-donation-list">
                        <?php
                        // Puxa as doações reais feitas por este usuário do banco de dados
                        $query_doacoes = $conn->prepare("SELECT titulo, status, DATE_FORMAT(data_cadastro, '%d %b %Y') as data_formatada FROM doacoes WHERE id_usuario = ? ORDER BY id DESC");
                        $query_doacoes->bind_param("i", $id_usuario);
                        $query_doacoes->execute();
                        $lista_doacoes = $query_doacoes->get_result();

                        if ($lista_doacoes->num_rows > 0):
                            while($doacao = $lista_doacoes->fetch_assoc()):
                                // Define a cor da badge dinamicamente dependendo do status
                                $badge_class = 'badge-gray';
                                if($doacao['status'] == 'Concluído') $badge_class = 'badge-green';
                                if($doacao['status'] == 'Em trânsito') $badge_class = 'badge-blue';
                        ?>
                            <div class="p-donation-row">
                                <div class="p-donation-info">
                                    <span class="p-donation-icon">📦</span>
                                    <div>
                                        <strong class="p-donation-item"><?php echo htmlspecialchars($doacao['titulo']); ?></strong>
                                        <span class="p-donation-dest">Plataforma DoaTech</span>
                                    </div>
                                </div>
                                <div class="p-donation-status-container">
                                    <span class="p-status-badge <?php echo $badge_class; ?>"><?php echo strtoupper($doacao['status']); ?></span>
                                    <p class="p-donation-date"><?php echo $doacao['data_formatada']; ?></p>
                                </div>
                            </div>
                        <?php 
                            endwhile;
                        else: 
                        ?>
                            <p style="color: #64748b; text-align: center; padding: 20px;">Você ainda não cadastrou nenhuma doação.</p>
                        <?php endif; ?>
                    </div>
                </div>

            <?php elseif ($aba == 'mensagens'): ?>
                <?php 
                $contato_id = isset($_GET['contato_id']) ? intval($_GET['contato_id']) : 0;
                $nome_contato = isset($_GET['nome_contato']) ? htmlspecialchars($_GET['nome_contato']) : 'Selecione uma conversa';
                
                // Busca o histórico de mensagens entre você e o contato selecionado
                $mensagens_chat = [];
                if ($contato_id > 0) {
                    $meu_id = $_SESSION['usuario_id'];
                    $sql_msg = "SELECT * FROM mensagens 
                                WHERE (id_remetente = ? AND id_destinatario = ?) 
                                   OR (id_remetente = ? AND id_destinatario = ?) 
                                ORDER BY data_envio ASC";
                    $stmt_msg = $conn->prepare($sql_msg);
                    $stmt_msg->bind_param("iiii", $meu_id, $contato_id, $contato_id, $meu_id);
                    $stmt_msg->execute();
                    $resultado_msg = $stmt_msg->get_result();
                    while ($row = $resultado_msg->fetch_assoc()) {
                        $mensagens_chat[] = $row;
                    }
                }
                ?>
                
                <div class="p-messages-wrapper">
                    
                    <aside class="p-chat-sidebar">
                        <div class="p-chat-sidebar-header">
                            <h2 class="p-section-title-blue" style="margin: 0; font-size: 18px;">CONVERSAS</h2>
                        </div>
                        <div class="p-chat-list">
                            <?php if ($contato_id > 0): ?>
                                <div class="p-chat-user active">
                                    <div class="p-chat-avatar" style="background: #38bdf8; color: black;"><i class="fa-solid fa-hand-holding-heart"></i></div>
                                    <div class="p-chat-info">
                                        <strong><?php echo $nome_contato; ?></strong>
                                        <p>Conversa ativa</p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p style="color: #94a3b8; padding: 20px; font-size: 14px;">Inicie um chat através da página de Projetos.</p>
                            <?php endif; ?>
                        </div>
                    </aside>

                    <section class="p-chat-window">
                        <header class="p-chat-header">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div class="p-chat-avatar-small" style="background: #38bdf8; color: black;"><i class="fa-solid fa-hand-holding-heart"></i></div>
                                <div>
                                    <strong style="color: white; font-size: 16px; display: block;"><?php echo $nome_contato; ?></strong>
                                    <?php if ($contato_id > 0): ?>
                                        <span style="color: #4ade80; font-size: 12px;">Online agora</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </header>

                        <div class="p-chat-messages" id="chat-box" style="overflow-y: auto; display: flex; flex-direction: column;">
                            <?php if ($contato_id > 0): ?>
                                <?php if (count($mensagens_chat) > 0): ?>
                                    <?php foreach ($mensagens_chat as $msg): 
                                        $classe_msg = ($msg['id_remetente'] == $_SESSION['usuario_id']) ? 'msg sent' : 'msg received';
                                        $hora = date('H:i', strtotime($msg['data_envio']));
                                    ?>
                                        <div class="<?php echo $classe_msg; ?>">
                                            <p><?php echo htmlspecialchars($msg['mensagem']); ?></p>
                                            <span><?php echo $hora; ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="msg sent" style="opacity: 0.7;">
                                        <p>Envie a primeira mensagem para <?php echo $nome_contato; ?>!</p>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div style="text-align: center; color: #94a3b8; margin-top: auto; margin-bottom: auto;">
                                    <i class="fa-regular fa-comments" style="font-size: 40px; margin-bottom: 10px;"></i>
                                    <p>Selecione um contato para começar a conversar.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($contato_id > 0): ?>
                        <form action="processar_mensagem.php" method="POST" style="margin: 0; padding: 20px; background: #05070a; border-top: 1px solid #1e293b; display: flex; gap: 15px; align-items: center;">
                            <input type="hidden" name="id_destinatario" value="<?php echo $contato_id; ?>">
                            <input type="hidden" name="nome_contato" value="<?php echo $nome_contato; ?>">
                            
                            <div class="input-wrapper" style="flex: 1; position: relative;">
                                <input type="text" name="mensagem" placeholder="Escreva sua mensagem aqui..." required autocomplete="off" style="width: 100%; padding: 12px 15px; background: #161b22; border: 1px solid #1e293b; border-radius: 8px; color: white;">
                            </div>
                            <button type="submit" class="p-chat-send-btn" style="background: #38bdf8; border: none; width: 45px; height: 45px; border-radius: 8px; color: #05070a; cursor: pointer; transition: 0.3s;">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </section>
                </div>
                
                <script>
                    var chatBox = document.getElementById("chat-box");
                    if(chatBox){
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                </script>
                
            <?php elseif ($aba == 'config'): ?>
                <div class="perfil-card-box">
                    <h2 class="p-section-title-blue">Configurações da Conta</h2>

                    <form action="processar_config.php" method="POST" enctype="multipart/form-data" class="p-settings-form">
                        
                        <div class="p-settings-avatar-section">
                            <div class="p-settings-avatar" style="background-image: url('uploads/<?php echo !empty($dados_usuario['foto']) ? $dados_usuario['foto'] : 'default.png'; ?>'); background-size: cover; background-position: center;">
                            </div>
                            <div class="p-settings-avatar-actions">
                                <h3 style="color: white; font-size: 16px; margin-bottom: 8px;">Sua Foto de Perfil</h3>
                                <div style="display: flex; gap: 10px;">
                                    <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" style="display:none;">
                                    <label for="foto_perfil" class="p-btn-outline" style="cursor:pointer; padding: 10px 15px; border-radius:8px;"><i class="fa-solid fa-upload"></i> Escolher Foto</label>
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="p-section-title-green" style="margin-top: 30px; font-size: 16px;">Dados Pessoais</h3>
                        <div class="p-form-grid">
                            <div class="p-form-group">
                                <label>Nome Completo</label>
                                <input type="text" name="nome" value="<?php echo htmlspecialchars($dados_usuario['nome']); ?>" class="p-form-input" required>
                            </div>
                            <div class="p-form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($dados_usuario['email']); ?>" class="p-form-input" required>
                            </div>
                            <div class="p-form-group">
                                <label>Telefone</label>
                                <input type="text" name="telefone" value="<?php echo htmlspecialchars($dados_usuario['telefone']); ?>" class="p-form-input">
                            </div>
                            <div class="p-form-group">
                                <label>Localização</label>
                                <input type="text" name="localizacao" value="<?php echo htmlspecialchars($dados_usuario['localizacao']); ?>" class="p-form-input">
                            </div>
                        </div>

                        <h3 class="p-section-title-blue" style="margin-top: 40px; font-size: 16px;">Segurança</h3>
                        <div class="p-form-grid">
                            <div class="p-form-group">
                                <label>Nova Senha (Deixe em branco para manter a atual)</label>
                                <input type="password" name="nova_senha" placeholder="Digite a nova senha" class="p-form-input">
                            </div>
                            <div class="p-form-group">
                                <label>Confirmar Nova Senha</label>
                                <input type="password" name="confirma_senha" placeholder="Repita a nova senha" class="p-form-input">
                            </div>
                        </div>

                        <div style="margin-top: 40px; text-align: right; border-top: 1px solid #1e293b; padding-top: 25px;">
                            <button type="submit" class="p-btn-save">
                                <i class="fa-solid fa-floppy-disk"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>

            <?php elseif ($aba == 'gerenciamento'): ?>
                <div class="perfil-card-box">
                    <div style="margin-bottom: 30px;">
                        <h2 class="p-section-title-blue" style="margin-bottom: 5px;">Painel de Gerenciamento</h2>
                        <p style="color: #94a3b8; font-size: 14px;">Personalize a identidade visual global do sistema (Cores, Fontes e Logos).</p>
                    </div>

                    <form action="processar_gerenciamento.php" method="POST" enctype="multipart/form-data" class="p-settings-form">
                        <h3 class="p-section-title-blue" style="font-size: 16px; margin-bottom: 15px;">Identidade Visual (Logos)</h3>
                        <div class="p-form-grid" style="margin-bottom: 30px;">
                            <div class="p-form-group">
                                <label>Logo Principal (Header)</label>
                                <input type="file" id="input-logo-principal" name="logo_principal" accept="image/*" class="p-form-input" style="padding: 10px; cursor: pointer;">
                                <small style="color: #64748b; font-size: 12px; margin-top: 5px;">Recomendado: PNG com fundo transparente.</small>
                            </div>
                            <div class="p-form-group">
                                <label>Ícone do Navegador (Favicon)</label>
                                <input type="file" name="favicon" accept="image/*" class="p-form-input" style="padding: 10px; cursor: pointer;">
                            </div>
                        </div>

                        <h3 class="p-section-title-green" style="font-size: 16px; margin-bottom: 15px;">Paleta de Cores Global</h3>

<div class="p-form-grid" style="margin-bottom: 30px;">
    <div class="p-form-group">
        <label>Cor Primária</label>
        <input type="color" name="cor_primaria" value="<?php echo htmlspecialchars($config['cor_primaria']); ?>" class="p-form-input">
    </div>

    <div class="p-form-group">
        <label>Cor Secundária</label>
        <input type="color" name="cor_secundaria" value="<?php echo htmlspecialchars($config['cor_secundaria']); ?>" class="p-form-input">
    </div>

    <div class="p-form-group">
        <label>Cor de Fundo</label>
        <input type="color" name="cor_fundo" value="<?php echo htmlspecialchars($config['cor_fundo']); ?>" class="p-form-input">
    </div>

    <div class="p-form-group">
        <label>Cor dos Cards</label>
        <input type="color" name="cor_card" value="<?php echo htmlspecialchars($config['cor_card']); ?>" class="p-form-input">
    </div>

    <div class="p-form-group">
        <label>Cor do Texto Principal</label>
        <input type="color" name="cor_texto" value="<?php echo htmlspecialchars($config['cor_texto']); ?>" class="p-form-input">
    </div>

    <div class="p-form-group">
        <label>Cor do Texto Secundário</label>
        <input type="color" name="cor_texto_secundario" value="<?php echo htmlspecialchars($config['cor_texto_secundario']); ?>" class="p-form-input">
    </div>

    <div class="p-form-group">
        <label>Cor das Bordas</label>
        <input type="color" name="cor_borda" value="<?php echo htmlspecialchars($config['cor_borda']); ?>" class="p-form-input">
    </div>

    <div class="p-form-group">
        <label>Cor dos Inputs</label>
        <input type="color" name="cor_input" value="<?php echo htmlspecialchars($config['cor_input']); ?>" class="p-form-input">
    </div>

    <div class="p-form-group">
        <label>Cor do Header</label>
        <input type="color" name="cor_header" value="<?php echo htmlspecialchars($config['cor_header']); ?>" class="p-form-input">
    </div>

    <div class="p-form-group">
        <label>Fonte Principal</label>
        <select name="fonte_principal" class="p-form-input">
            <option value="Inter" <?php echo ($config['fonte_principal'] == 'Inter') ? 'selected' : ''; ?>>Inter</option>
            <option value="Arial" <?php echo ($config['fonte_principal'] == 'Arial') ? 'selected' : ''; ?>>Arial</option>
            <option value="Poppins" <?php echo ($config['fonte_principal'] == 'Poppins') ? 'selected' : ''; ?>>Poppins</option>
            <option value="Roboto" <?php echo ($config['fonte_principal'] == 'Roboto') ? 'selected' : ''; ?>>Roboto</option>
        </select>
    </div>
</div>

                        <div style="margin-top: 40px; display: flex; justify-content: flex-end; gap: 15px; border-top: 1px solid #1e293b; padding-top: 25px;">
                            
                            <a href="resetar_tema.php" onclick="return confirm('Tem certeza que deseja restaurar o tema padrão? Isso apagará a logo atual e voltará as cores originais.');" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; padding: 12px 25px; border-radius: 8px; color: #ef4444; border: 1px solid #ef4444; background: transparent; font-weight: bold; cursor: pointer; transition: 0.3s;">
                                <i class="fa-solid fa-rotate-left" style="margin-right: 8px;"></i> Voltar de Fábrica
                            </a>

                            <button type="submit" class="p-btn-save" style="background: linear-gradient(90deg, #38bdf8, #4ade80); color: #05070a; font-weight: bold; padding: 12px 25px; border-radius: 8px; border: none; cursor: pointer;">
                                <i class="fa-solid fa-arrows-rotate" style="margin-right: 8px;"></i> Aplicar Tema Global
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputLogo = document.getElementById('input-logo-principal');
            const logoHeaderLink = document.getElementById('logo-header-link');
            if (inputLogo && logoHeaderLink) {
                inputLogo.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            logoHeaderLink.innerHTML = `<img src="${e.target.result}" alt="Logo" style="max-height: 40px; display: block; object-fit: contain;">`;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</body>
</html>