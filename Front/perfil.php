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
$query_user = $conn->prepare("SELECT nome, email, telefone, documento, localizacao, tipo_usuario, area_atuacao, descricao_ong, foto FROM usuarios WHERE id = ?");
$query_user->bind_param("i", $id_usuario);
$query_user->execute();
$dados_usuario = $query_user->get_result()->fetch_assoc();

// 2. Busca a quantidade real de doações que este usuário já fez
$query_count = $conn->prepare("SELECT COUNT(*) as total FROM doacoes WHERE id_usuario = ?");
$query_count->bind_param("i", $id_usuario);
$query_count->execute();
$total_doacoes = $query_count->get_result()->fetch_assoc()['total'];
$query_count_pedidos = $conn->prepare("SELECT COUNT(*) as total FROM pedidos WHERE id_usuario = ?");
$query_count_pedidos->bind_param("i", $id_usuario);
$query_count_pedidos->execute();
$total_pedidos = $query_count_pedidos->get_result()->fetch_assoc()['total'];
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
                <p class="p-status"><?php echo ($dados_usuario['tipo_usuario'] === 'ong') ? 'ONG Cadastrada' : 'Doador Ativo'; ?></p>
                <p class="p-status-sub"><?php echo ($dados_usuario['tipo_usuario'] === 'ong') ? 'Conta institucional' : 'Conta pessoal'; ?></p>
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
                            <span class="p-label">Pedidos Publicados</span>
                            <p class="p-val-blue"><?php echo $total_pedidos; ?></p>
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
                        <div class="p-data-field">
                            <label>Tipo de Conta</label>
                            <p><?php echo ($dados_usuario['tipo_usuario'] === 'ong') ? 'ONG / Projeto Social' : 'Usuário Normal'; ?></p>
                        </div>
                        <?php if ($dados_usuario['tipo_usuario'] === 'ong'): ?>
                            <div class="p-data-field">
                                <label>Área de Atuação</label>
                                <p><?php echo !empty($dados_usuario['area_atuacao']) ? htmlspecialchars($dados_usuario['area_atuacao']) : 'Não informada'; ?></p>
                            </div>
                            <div class="p-data-field">
                                <label>Sobre a ONG</label>
                                <p><?php echo !empty($dados_usuario['descricao_ong']) ? htmlspecialchars($dados_usuario['descricao_ong']) : 'Não informado'; ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="perfil-card-box">
                        <h2 class="p-section-title-green">Dados de Contato</h2>
                        <?php if ($dados_usuario['tipo_usuario'] === 'ong'): ?>
                            <div class="p-data-field">
                                <label>CNPJ</label>
                                <p><?php echo !empty($dados_usuario['documento']) ? htmlspecialchars($dados_usuario['documento']) : 'Não informado'; ?></p>
                            </div>
                        <?php endif; ?>
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
                      $query_doacoes = $conn->prepare("SELECT id, titulo, status, fotos, DATE_FORMAT(data_cadastro, '%d %b %Y') as data_formatada FROM doacoes WHERE id_usuario = ? ORDER BY id DESC"); 
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
                                   <?php if (!empty($doacao['fotos'])): ?>
    <img class="p-donation-photo js-expand-image" src="uploads/<?php echo htmlspecialchars($doacao['fotos']); ?>" alt="Foto da doação">
<?php else: ?>
    <span class="p-donation-icon"><i class="fa-solid fa-box"></i></span>
<?php endif; ?>
                                    <div>
                                        <strong class="p-donation-item"><?php echo htmlspecialchars($doacao['titulo']); ?></strong>
                                        <span class="p-donation-dest">Plataforma DoaTech</span>
                                    </div>
                                </div>
                                <div class="p-donation-status-container">
                                    <span class="p-status-badge <?php echo $badge_class; ?>"><?php echo strtoupper($doacao['status']); ?></span>
                                    <p class="p-donation-date"><?php echo $doacao['data_formatada']; ?></p>
                                   <form class="p-delete-doacao-form js-confirm-form" action="excluir_doacao.php" method="POST" data-confirm-title="Excluir doa&ccedil;&atilde;o?" data-confirm-message="Essa a&ccedil;&atilde;o remove &quot;<?php echo htmlspecialchars($doacao['titulo'], ENT_QUOTES, 'UTF-8'); ?>&quot; do seu perfil. Voc&ecirc; poder&aacute; cadastrar novamente se precisar." data-confirm-label="Excluir doa&ccedil;&atilde;o" data-confirm-icon="fa-trash">
    <input type="hidden" name="id_doacao" value="<?php echo (int)$doacao['id']; ?>">
    <button type="submit" class="p-btn-delete-doacao">
        <i class="fa-solid fa-trash"></i>
        <span>Excluir</span>
    </button>
</form>
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

                $meu_id = (int) $_SESSION['usuario_id'];
                $conversas = [];
                $sql_conversas = "SELECT u.id, u.nome, u.foto, u.tipo_usuario,
                                         MAX(m.data_envio) AS ultima_data,
                                         SUBSTRING_INDEX(GROUP_CONCAT(m.mensagem ORDER BY m.data_envio DESC SEPARATOR '||'), '||', 1) AS ultima_mensagem,
                                         SUM(CASE WHEN m.id_destinatario = ? AND m.lida = 0 THEN 1 ELSE 0 END) AS nao_lidas
                                  FROM mensagens m
                                  JOIN usuarios u ON u.id = CASE WHEN m.id_remetente = ? THEN m.id_destinatario ELSE m.id_remetente END
                                  WHERE m.id_remetente = ? OR m.id_destinatario = ?
                                  GROUP BY u.id, u.nome, u.foto, u.tipo_usuario
                                  ORDER BY ultima_data DESC";
                $stmt_conversas = $conn->prepare($sql_conversas);
                $stmt_conversas->bind_param("iiii", $meu_id, $meu_id, $meu_id, $meu_id);
                $stmt_conversas->execute();
                $resultado_conversas = $stmt_conversas->get_result();
                while ($conversa = $resultado_conversas->fetch_assoc()) {
                    $conversas[(int) $conversa['id']] = $conversa;
                }
                $stmt_conversas->close();

                if ($contato_id === 0 && count($conversas) > 0) {
                    $primeira_conversa = reset($conversas);
                    $contato_id = (int) $primeira_conversa['id'];
                }

                $contato_atual = null;
                if ($contato_id > 0) {
                    $stmt_contato = $conn->prepare("SELECT id, nome, foto, tipo_usuario FROM usuarios WHERE id = ?");
                    $stmt_contato->bind_param("i", $contato_id);
                    $stmt_contato->execute();
                    $contato_atual = $stmt_contato->get_result()->fetch_assoc();
                    $stmt_contato->close();

                    if ($contato_atual && !isset($conversas[$contato_id])) {
                        $conversas[$contato_id] = [
                            'id' => $contato_atual['id'],
                            'nome' => $contato_atual['nome'],
                            'foto' => $contato_atual['foto'],
                            'tipo_usuario' => $contato_atual['tipo_usuario'],
                            'ultima_data' => null,
                            'ultima_mensagem' => 'Nova conversa',
                            'nao_lidas' => 0
                        ];
                    }
                }

                $nome_contato = $contato_atual ? htmlspecialchars($contato_atual['nome']) : 'Selecione uma conversa';
                $mensagens_chat = [];
                if ($contato_id > 0 && $contato_atual) {
                    $stmt_ler = $conn->prepare("UPDATE mensagens SET lida = 1 WHERE id_remetente = ? AND id_destinatario = ?");
                    $stmt_ler->bind_param("ii", $contato_id, $meu_id);
                    $stmt_ler->execute();
                    $stmt_ler->close();
                    if (isset($conversas[$contato_id])) {
                        $conversas[$contato_id]['nao_lidas'] = 0;
                    }

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
                    $stmt_msg->close();
                }
                ?>
                
                <div class="p-messages-wrapper">
                    
                    <aside class="p-chat-sidebar">
                        <div class="p-chat-sidebar-header">
                            <h2 class="p-section-title-blue" style="margin: 0; font-size: 18px;">CONVERSAS</h2>
                        </div>
                        <div class="p-chat-list">
                            <?php if (count($conversas) > 0): ?>
                                <?php foreach ($conversas as $conversa):
                                    $conversa_id = (int) $conversa['id'];
                                    $ativa = ($conversa_id === $contato_id) ? 'active' : '';
                                    $nao_lidas = (int) $conversa['nao_lidas'];
                                    $icone_conversa = ($conversa['tipo_usuario'] === 'ong') ? 'fa-hand-holding-heart' : 'fa-user';
                                ?>
                                    <a href="perfil.php?aba=mensagens&contato_id=<?php echo $conversa_id; ?>" class="p-chat-user <?php echo $ativa; ?>">
                                        <div class="p-chat-avatar" style="background: #38bdf8; color: black;">
                                            <i class="fa-solid <?php echo $icone_conversa; ?>"></i>
                                        </div>
                                        <div class="p-chat-info">
                                            <strong><?php echo htmlspecialchars($conversa['nome']); ?></strong>
                                            <p><?php echo htmlspecialchars($conversa['ultima_mensagem'] ?: 'Nova conversa'); ?></p>
                                        </div>
                                        <?php if ($nao_lidas > 0): ?>
                                            <span class="p-chat-unread"><?php echo $nao_lidas > 9 ? '9+' : $nao_lidas; ?></span>
                                        <?php endif; ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="color: #94a3b8; padding: 20px; font-size: 14px;">Inicie um chat atraves da pagina de Projetos.</p>
                            <?php endif; ?>
                            <?php if (false): ?>
                                <div class="p-chat-user active">
                                    <div class="p-chat-avatar" style="background: #38bdf8; color: black;"><i class="fa-solid fa-hand-holding-heart"></i></div>
                                    <div class="p-chat-info">
                                        <strong><?php echo $nome_contato; ?></strong>
                                        <p>Conversa ativa</p>
                                    </div>
                                </div>
                            <?php elseif (false): ?>
                                <p style="color: #94a3b8; padding: 20px; font-size: 14px;">Inicie um chat através da página de Projetos.</p>
                            <?php endif; ?>
                        </div>
                    </aside>

                    <section class="p-chat-window">
                        <header class="p-chat-header">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div class="p-chat-avatar-small" style="background: #38bdf8; color: black;"><i class="fa-solid <?php echo ($contato_atual && $contato_atual['tipo_usuario'] === 'ong') ? 'fa-hand-holding-heart' : 'fa-user'; ?>"></i></div>
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
                                <label>Tipo de Conta</label>
                                <select name="tipo_usuario" id="config-tipo-usuario" class="p-form-input">
                                    <option value="usuario" <?php echo ($dados_usuario['tipo_usuario'] === 'usuario') ? 'selected' : ''; ?>>Usuário Normal</option>
                                    <option value="ong" <?php echo ($dados_usuario['tipo_usuario'] === 'ong') ? 'selected' : ''; ?>>ONG / Projeto Social</option>
                                </select>
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

                        <div id="config-ong-fields" class="p-ong-fields">
                            <h3 class="p-section-title-green" style="margin-top: 30px; font-size: 16px;">Dados da ONG</h3>
                            <div class="p-form-grid">
                                <div class="p-form-group">
                                    <label>CNPJ</label>
                                    <input type="text" name="documento" value="<?php echo htmlspecialchars($dados_usuario['documento']); ?>" class="p-form-input">
                                </div>
                                <div class="p-form-group">
                                    <label>Área de Atuação</label>
                                    <input type="text" name="area_atuacao" value="<?php echo htmlspecialchars($dados_usuario['area_atuacao']); ?>" class="p-form-input">
                                </div>
                                <div class="p-form-group p-form-group-full">
                                    <label>Descrição da ONG</label>
                                    <textarea name="descricao_ong" class="p-form-input" rows="4"><?php echo htmlspecialchars($dados_usuario['descricao_ong']); ?></textarea>
                                </div>
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
                            
                            <a href="resetar_tema.php" class="js-confirm-link" data-confirm-title="Restaurar tema padr&atilde;o?" data-confirm-message="Isso apagar&aacute; a logo atual e voltar&aacute; as cores originais do DoaTech." data-confirm-label="Restaurar tema" data-confirm-icon="fa-rotate-left" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; padding: 12px 25px; border-radius: 8px; color: #ef4444; border: 1px solid #ef4444; background: transparent; font-weight: bold; cursor: pointer; transition: 0.3s;">
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

    <div class="p-confirm-modal" id="perfil-confirm-modal" aria-hidden="true">
        <div class="p-confirm-backdrop" data-confirm-close></div>
        <section class="p-confirm-dialog" role="dialog" aria-modal="true" aria-labelledby="perfil-confirm-title" aria-describedby="perfil-confirm-message" tabindex="-1">
            <div class="p-confirm-icon">
                <i id="perfil-confirm-modal-icon" class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <span class="p-confirm-kicker">Confirma&ccedil;&atilde;o</span>
            <h2 id="perfil-confirm-title">Confirmar a&ccedil;&atilde;o?</h2>
            <p id="perfil-confirm-message">Deseja continuar?</p>
            <div class="p-confirm-actions">
                <button type="button" class="p-confirm-cancel" data-confirm-close>Cancelar</button>
                <button type="button" class="p-confirm-accept" id="perfil-confirm-accept">
                    <i class="fa-solid fa-check"></i>
                    <span>Confirmar</span>
                </button>
            </div>
        </section>
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

            const tipoUsuarioConfig = document.getElementById('config-tipo-usuario');
            const configOngFields = document.getElementById('config-ong-fields');
            function atualizarCamposOngConfig() {
                if (!tipoUsuarioConfig || !configOngFields) return;
                configOngFields.style.display = tipoUsuarioConfig.value === 'ong' ? 'block' : 'none';
            }
            if (tipoUsuarioConfig) {
                tipoUsuarioConfig.addEventListener('change', atualizarCamposOngConfig);
                atualizarCamposOngConfig();
            }

            const confirmModal = document.getElementById('perfil-confirm-modal');
            const confirmDialog = confirmModal ? confirmModal.querySelector('.p-confirm-dialog') : null;
            const confirmTitle = document.getElementById('perfil-confirm-title');
            const confirmMessage = document.getElementById('perfil-confirm-message');
            const confirmAccept = document.getElementById('perfil-confirm-accept');
            const confirmAcceptIcon = confirmAccept ? confirmAccept.querySelector('i') : null;
            const confirmAcceptLabel = confirmAccept ? confirmAccept.querySelector('span') : null;
            const confirmModalIcon = document.getElementById('perfil-confirm-modal-icon');
            let pendingConfirmAction = null;

            function setConfirmIcon(iconClass) {
                const safeIcon = (iconClass || 'fa-check').replace(/[^a-zA-Z0-9_-]/g, '');
                if (confirmAcceptIcon) confirmAcceptIcon.className = `fa-solid ${safeIcon}`;
                if (confirmModalIcon) confirmModalIcon.className = `fa-solid ${safeIcon}`;
            }

            function openConfirmModal(options) {
                if (!confirmModal || !confirmTitle || !confirmMessage || !confirmAccept) return false;

                pendingConfirmAction = options.onConfirm;
                confirmTitle.textContent = options.title || 'Confirmar acao?';
                confirmMessage.textContent = options.message || 'Deseja continuar?';
                if (confirmAcceptLabel) confirmAcceptLabel.textContent = options.label || 'Confirmar';
                setConfirmIcon(options.icon);

                confirmModal.classList.add('active');
                confirmModal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('confirm-modal-open');
                setTimeout(function() {
                    if (confirmDialog) confirmDialog.focus();
                }, 30);

                return true;
            }

            function closeConfirmModal() {
                if (!confirmModal) return;
                confirmModal.classList.remove('active');
                confirmModal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('confirm-modal-open');
                pendingConfirmAction = null;
            }

            document.querySelectorAll('.js-confirm-form').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const opened = openConfirmModal({
                        title: form.dataset.confirmTitle,
                        message: form.dataset.confirmMessage,
                        label: form.dataset.confirmLabel,
                        icon: form.dataset.confirmIcon,
                        onConfirm: function() {
                            form.submit();
                        }
                    });
                    if (!opened) form.submit();
                });
            });

            document.querySelectorAll('.js-confirm-link').forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const opened = openConfirmModal({
                        title: link.dataset.confirmTitle,
                        message: link.dataset.confirmMessage,
                        label: link.dataset.confirmLabel,
                        icon: link.dataset.confirmIcon,
                        onConfirm: function() {
                            window.location.href = link.href;
                        }
                    });
                    if (!opened) window.location.href = link.href;
                });
            });

            document.querySelectorAll('[data-confirm-close]').forEach(function(button) {
                button.addEventListener('click', closeConfirmModal);
            });

            if (confirmAccept) {
                confirmAccept.addEventListener('click', function() {
                    const action = pendingConfirmAction;
                    closeConfirmModal();
                    if (typeof action === 'function') action();
                });
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && confirmModal && confirmModal.classList.contains('active')) {
                    closeConfirmModal();
                }
            });
        });
    </script>
</body>
</html>
