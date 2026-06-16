<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>DoaTech - Perfil</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="perfil-body-layout">

    <?php 
    include('header.php'); 
    // Lógica para definir qual aba está ativa
    $aba = isset($_GET['aba']) ? $_GET['aba'] : 'perfil';
    ?>

    <div class="perfil-wrapper">
        <aside class="perfil-sidebar">
            <div class="perfil-user-header">
                <div class="perfil-avatar-frame"></div>
                <h3>Nome Usuario</h3>
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
                    <div class="p-hero-circle"></div>
                </div>

                <div class="perfil-card-box">
                    <h2 class="p-section-title-blue">Estatísticas de impacto</h2>
                    <div class="p-stats-grid">
                        <div class="p-stat-box">
                            <span class="p-label">Doações Realizadas</span>
                            <p class="p-val-green">25</p>
                        </div>
                        <div class="p-v-line"></div>
                        <div class="p-stat-box">
                            <span class="p-label">Vidas Impactadas</span>
                            <p class="p-val-blue">89</p>
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
                            <p>Nome do Usuário</p>
                        </div>
                        <div class="p-data-field">
                            <label>Email</label>
                            <p>usuario@email.com</p>
                        </div>
                    </div>

                    <div class="perfil-card-box">
                        <h2 class="p-section-title-green">Doador Principal</h2>
                        <div class="p-data-field">
                            <label>Nome</label>
                            <p>Nome Sobrenome</p>
                        </div>
                        <div class="p-data-field">
                            <label>Telefone</label>
                            <p>(11) 123456789</p>
                        </div>
                        <div class="p-data-field">
                            <label>Localização</label>
                            <p>Rua 123</p>
                        </div>
                    </div>
                </div>

            <?php elseif ($aba == 'doacoes'): ?>
                <div class="perfil-card-box">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                        <h2 class="p-section-title-blue" style="margin-bottom: 0;">MINHAS DOAÇÕES</h2>
                    </div>

                    <div class="p-donation-list">
                        <div class="p-donation-row">
                            <div class="p-donation-info">
                                <span class="p-donation-icon">🔧</span>
                                <div>
                                    <strong class="p-donation-item">Kit de Ferramentas Pro</strong>
                                    <span class="p-donation-dest">Destino: ONG Salvador</span>
                                </div>
                            </div>
                            <div class="p-donation-status-container">
                                <span class="p-status-badge badge-green">CONCLUÍDO</span>
                                <p class="p-donation-date">12 Jan 2026</p>
                            </div>
                        </div>

                        <div class="p-donation-row">
                            <div class="p-donation-info">
                                <span class="p-donation-icon">💻</span>
                                <div>
                                    <strong class="p-donation-item">Monitor LED 24'</strong>
                                    <span class="p-donation-dest">Destino: Escola Técnica SP</span>
                                </div>
                            </div>
                            <div class="p-donation-status-container">
                                <span class="p-status-badge badge-blue">EM TRÂNSITO</span>
                                <p class="p-donation-date">08 Jan 2026</p>
                            </div>
                        </div>

                        <div class="p-donation-row">
                            <div class="p-donation-info">
                                <span class="p-donation-icon">📚</span>
                                <div>
                                    <strong class="p-donation-item">Material Escolar</strong>
                                    <span class="p-donation-dest">Destino: Biblioteca Comunitária</span>
                                </div>
                            </div>
                            <div class="p-donation-status-container">
                                <span class="p-status-badge badge-gray">AGUARDANDO COLETA</span>
                                <p class="p-donation-date">05 Jan 2026</p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php elseif ($aba == 'mensagens'): ?>
                <div class="p-messages-wrapper">
                    
                    <aside class="p-chat-sidebar">
                        <div class="p-chat-sidebar-header">
                            <h2 class="p-section-title-blue" style="margin: 0; font-size: 18px;">CONVERSAS</h2>
                        </div>
                        <div class="p-chat-list">
                            <div class="p-chat-user active">
                                <div class="p-chat-avatar" style="background: #38bdf8; color: black;"><i class="fa-solid fa-hand-holding-heart"></i></div>
                                <div class="p-chat-info">
                                    <strong>ONG Vida Nova</strong>
                                    <p>Olá! O kit chegou...</p>
                                </div>
                            </div>
                            <div class="p-chat-user">
                                <div class="p-chat-avatar" style="background: #4ade80; color: black;"><i class="fa-solid fa-school"></i></div>
                                <div class="p-chat-info">
                                    <strong>Escola Técnica SP</strong>
                                    <p>Obrigado pela doação!</p>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <section class="p-chat-window">
                        <header class="p-chat-header">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div class="p-chat-avatar-small" style="background: #38bdf8; color: black;"><i class="fa-solid fa-hand-holding-heart"></i></div>
                                <div>
                                    <strong style="color: white; font-size: 16px; display: block;">ONG Vida Nova</strong>
                                    <span style="color: #4ade80; font-size: 12px;">Online agora</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-ellipsis-vertical" style="color: #94a3b8; cursor: pointer; font-size: 20px;"></i>
                        </header>

                        <div class="p-chat-messages">
                            <div class="msg received">
                                <p>Olá, Nome Usuario! Gostaria de confirmar se o Kit de Ferramentas já foi enviado?</p>
                                <span>14:20</span>
                            </div>
                            <div class="msg sent">
                                <p>Olá! Sim, foi enviado hoje pela manhã. O código de rastreio está no seu painel.</p>
                                <span>14:25</span>
                            </div>
                            <div class="msg received">
                                <p>Perfeito! Muito obrigado pela generosidade. Vai ajudar muito nossas oficinas.</p>
                                <span>14:30</span>
                            </div>
                        </div>

                        <footer class="p-chat-input-area">
                            <div class="input-wrapper">
                                <i class="fa-solid fa-paperclip attach-icon"></i>
                                <input type="text" placeholder="Escreva sua mensagem aqui...">
                            </div>
                            <button class="p-chat-send-btn"><i class="fa-solid fa-paper-plane"></i></button>
                        </footer>
                    </section>
                </div>
                
            <?php elseif ($aba == 'config'): ?>
                <div class="perfil-card-box">
                    <h2 class="p-section-title-blue">Configurações da Conta</h2>

                    <div class="p-settings-avatar-section">
                        <div class="p-settings-avatar">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                        <div class="p-settings-avatar-actions">
                            <h3 style="color: white; font-size: 16px; margin-bottom: 8px;">Sua Foto de Perfil</h3>
                            <div style="display: flex; gap: 10px;">
                                <button class="p-btn-outline"><i class="fa-solid fa-upload"></i> Trocar Foto</button>
                                <button class="p-btn-text"><i class="fa-solid fa-trash"></i> Remover</button>
                            </div>
                        </div>
                    </div>

                    <form action="#" method="POST" class="p-settings-form">
                        
                        <h3 class="p-section-title-green" style="margin-top: 30px; font-size: 16px;">Dados Pessoais</h3>
                        <div class="p-form-grid">
                            <div class="p-form-group">
                                <label>Nome Completo</label>
                                <input type="text" value="Nome Usuario" class="p-form-input">
                            </div>
                            <div class="p-form-group">
                                <label>Email</label>
                                <input type="email" value="usuario@email.com" class="p-form-input">
                            </div>
                            <div class="p-form-group">
                                <label>Telefone</label>
                                <input type="text" value="(11) 123456789" class="p-form-input">
                            </div>
                            <div class="p-form-group">
                                <label>Localização</label>
                                <input type="text" value="Rua 123" class="p-form-input">
                            </div>
                        </div>

                        <h3 class="p-section-title-blue" style="margin-top: 40px; font-size: 16px;">Segurança</h3>
                        <div class="p-form-grid">
                            <div class="p-form-group">
                                <label>Nova Senha</label>
                                <input type="password" placeholder="Digite a nova senha" class="p-form-input">
                            </div>
                            <div class="p-form-group">
                                <label>Confirmar Nova Senha</label>
                                <input type="password" placeholder="Repita a nova senha" class="p-form-input">
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

                    <form action="#" method="POST" enctype="multipart/form-data" class="p-settings-form">
                        
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
                                <label>Cor Primária (Atual: Verde Neon)</label>
                                <div style="display: flex; gap: 15px; align-items: center; background: #05070a; border: 1px solid #1e293b; padding: 5px 15px; border-radius: 12px;">
                                    <input type="color" name="cor_primaria" value="#4ade80" style="width: 40px; height: 40px; border: none; background: none; cursor: pointer;">
                                    <span style="color: white; font-family: monospace;">#4ade80</span>
                                </div>
                            </div>

                            <div class="p-form-group">
                                <label>Cor Secundária (Atual: Azul Neon)</label>
                                <div style="display: flex; gap: 15px; align-items: center; background: #05070a; border: 1px solid #1e293b; padding: 5px 15px; border-radius: 12px;">
                                    <input type="color" name="cor_secundaria" value="#38bdf8" style="width: 40px; height: 40px; border: none; background: none; cursor: pointer;">
                                    <span style="color: white; font-family: monospace;">#38bdf8</span>
                                </div>
                            </div>

                            <div class="p-form-group">
                                <label>Cor de Fundo Principal</label>
                                <div style="display: flex; gap: 15px; align-items: center; background: #05070a; border: 1px solid #1e293b; padding: 5px 15px; border-radius: 12px;">
                                    <input type="color" name="cor_fundo" value="#05070a" style="width: 40px; height: 40px; border: none; background: none; cursor: pointer;">
                                    <span style="color: white; font-family: monospace;">#05070a</span>
                                </div>
                            </div>
                        </div>

                        <h3 class="p-section-title-blue" style="font-size: 16px; margin-bottom: 15px;">Tipografia (Fontes)</h3>
                        <div class="p-form-grid">
                            <div class="p-form-group">
                                <label>Fonte Principal do Sistema</label>
                                <select name="fonte_principal" class="p-form-input" style="cursor: pointer;">
                                    <option value="Inter" selected>Inter (Padrão Atual)</option>
                                    <option value="Roboto">Roboto</option>
                                    <option value="Poppins">Poppins</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Open Sans">Open Sans</option>
                                </select>
                            </div>
                        </div>

                        <div style="margin-top: 40px; text-align: right; border-top: 1px solid #1e293b; padding-top: 25px;">
                            <button type="submit" class="p-btn-save" style="background: linear-gradient(90deg, #38bdf8, #4ade80); color: #05070a; font-weight: bold; padding: 12px 25px; border-radius: 8px; border: none; cursor: pointer;">
                                <i class="fa-solid fa-arrows-rotate"></i> Aplicar Tema Global
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
                            // Substitui os <span> do texto pela tag <img> da nova logo
                            // max-height: 40px garante que a imagem não quebre o tamanho do header
                            logoHeaderLink.innerHTML = `<img src="${e.target.result}" alt="Logo Personalizada" style="max-height: 40px; display: block; object-fit: contain;">`;
                        }
                        
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</body>
</html>