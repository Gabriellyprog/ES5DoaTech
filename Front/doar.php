<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoaTech - Fazer Doação</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="doar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include('tema.php'); ?>
</head>
<body class="doar-body">

    <?php include('header.php'); ?>

    <main class="doar-wrapper">
        <div class="doar-header-text">
            <h1>Faça a diferença hoje. <span class="txt-green">Doe um equipamento.</span></h1>
            <p>Preencha os dados abaixo para disponibilizar seu item para ONGs e projetos sociais.</p>
        </div>

        <div class="doar-grid">
            
            <section class="doar-card doar-form-section">
                <h2 class="txt-blue" style="margin-bottom: 25px; font-size: 20px;">Detalhes do Item</h2>
                
                <form action="processar_doacao.php" method="POST" enctype="multipart/form-data" class="doar-form">
                    
                    <div class="d-form-group">
                        <label>O que você está doando?</label>
                        <input type="text" name="titulo" placeholder="Ex: Monitor Dell 24 Polegadas, Kit de Ferramentas..." required>
                    </div>

                    <div class="d-form-row">
                        <div class="d-form-group">
                            <label>Categoria</label>
                            <select name="categoria" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="informatica">Informática e TI</option>
                                <option value="ferramentas">Ferramentas</option>
                                <option value="moveis">Móveis de Escritório</option>
                                <option value="outros">Outros</option>
                            </select>
                        </div>
                        <div class="d-form-group">
                            <label>Estado de Conservação</label>
                            <select name="estado" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="novo">Novo / Sem uso</option>
                                <option value="bom">Usado - Em bom estado</option>
                                <option value="reparo">Usado - Precisa de reparos</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-form-group">
                        <label>Descrição e Detalhes</label>
                        <textarea name="descricao" rows="4" placeholder="Descreva detalhes como tempo de uso, se acompanha cabos, se tem algum defeito..." required></textarea>
                    </div>

                    <div class="d-form-group">
                        <label>Fotos do Item</label>
                        <div class="d-upload-area">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Clique ou arraste imagens aqui</span>
                            <small>Formatos aceitos: JPG, PNG (Max 5MB)</small>
                            <input type="file" name="foto_item" accept="image/*" multiple>
                        </div>
                    </div>

                    <div class="d-form-actions">
                        <button type="submit" class="d-btn-submit">
                            <i class="fa-solid fa-check"></i> Cadastrar Doação
                        </button>
                    </div>
                </form>
            </section>

            <aside class="doar-card doar-info-section">
                <h2 class="txt-green" style="margin-bottom: 25px; font-size: 20px;">Como Funciona?</h2>
                
                <div class="d-step-list">
                    <div class="d-step-item">
                        <div class="d-step-icon"><i class="fa-solid fa-1"></i></div>
                        <div class="d-step-text">
                            <strong>Cadastre o Item</strong>
                            <p>Forneça fotos nítidas e uma descrição honesta sobre o estado do equipamento.</p>
                        </div>
                    </div>
                    
                    <div class="d-step-item">
                        <div class="d-step-icon"><i class="fa-solid fa-2"></i></div>
                        <div class="d-step-text">
                            <strong>Conexão com a ONG</strong>
                            <p>Nosso sistema notifica projetos sociais que precisam do seu item na sua região.</p>
                        </div>
                    </div>

                    <div class="d-step-item">
                        <div class="d-step-icon"><i class="fa-solid fa-3"></i></div>
                        <div class="d-step-text">
                            <strong>Combine a Entrega</strong>
                            <p>Use nosso chat interno para combinar a retirada ou envio diretamente com o receptor.</p>
                        </div>
                    </div>
                </div>

                <div class="d-security-box">
                    <i class="fa-solid fa-shield-halved"></i>
                    <p>Seus dados de contato só são liberados após você aceitar a solicitação da ONG no painel de mensagens.</p>
                </div>
            </aside>

        </div>
    </main>

</body>
</html>