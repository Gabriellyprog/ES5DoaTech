<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoaTech - Solicitar Doação</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="receber.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="receber-body">

    <?php include('header.php'); ?>

    <main class="receber-wrapper">
        <div class="receber-header-text">
            <h1>Precisa de equipamentos? <span class="txt-blue">Faça um pedido.</span></h1>
            <p>Conte-nos o que você ou sua ONG precisa e conectaremos você a doadores dispostos a ajudar.</p>
        </div>

        <div class="receber-grid">
            
            <section class="receber-card receber-form-section">
                <h2 class="txt-blue" style="margin-bottom: 25px; font-size: 20px;">Detalhes da Necessidade</h2>
                
                <form action="processar_pedido.php" method="POST" enctype="multipart/form-data" class="receber-form">
                    
                    <div class="r-form-group">
                        <label>O que você está precisando?</label>
                        <input type="text" name="titulo" placeholder="Ex: Precisamos de 5 computadores..." class="input-class" required>
                    </div>

                    <div class="r-form-row">
                        <div class="r-form-group">
                            <label>Categoria do Pedido</label>
                            <select name="categoria" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="informatica">Informática e TI</option>
                                <option value="ferramentas">Ferramentas</option>
                                <option value="moveis">Móveis de Escritório</option>
                                <option value="material_escolar">Material Escolar</option>
                                <option value="outros">Outros</option>
                            </select>
                        </div>
                        <div class="r-form-group">
                            <label>Nível de Urgência</label>
                            <select name="urgencia" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="baixa">Baixa (Pode esperar)</option>
                                <option value="media">Média (Necessário em breve)</option>
                                <option value="alta">Alta (Urgente / Projetos parados)</option>
                            </select>
                        </div>
                    </div>

                    <div class="r-form-group">
                        <label>Conte sua história (Por que você precisa disso?)</label>
                        <textarea name="historia" rows="5" placeholder="Explique como essa doação vai impactar sua vida, seu projeto ou a comunidade. Os doadores gostam de saber quem estão ajudando!" required></textarea>
                    </div>

                    <div class="r-form-group">
                        <label>Fotos do Projeto ou Comprovantes (Opcional)</label>
                        <div class="r-upload-area">
                            <i class="fa-regular fa-image"></i>
                            <span>Anexar fotos do local ou documentos</span>
                            <small>Isso aumenta a confiança dos doadores em 80%</small>
                            <input type="file" name="comprovantes" accept="image/*, application/pdf" multiple>
                        </div>
                    </div>

                    <div class="r-form-actions">
                        <button type="submit" class="r-btn-submit">
                            <i class="fa-solid fa-bullhorn"></i> Publicar Pedido
                        </button>
                    </div>
                </form>
            </section>

            <aside class="receber-card receber-info-section">
                <h2 class="txt-green" style="margin-bottom: 25px; font-size: 20px;">Como Receber Ajuda?</h2>
                
                <div class="r-step-list">
                    <div class="r-step-item">
                        <div class="r-step-icon"><i class="fa-solid fa-pen-to-square"></i></div>
                        <div class="r-step-text">
                            <strong>1. Escreva seu pedido</strong>
                            <p>Seja transparente. Pedidos com boas descrições e fotos reais são atendidos 3x mais rápido.</p>
                        </div>
                    </div>
                    
                    <div class="r-step-item">
                        <div class="r-step-icon"><i class="fa-solid fa-satellite-dish"></i></div>
                        <div class="r-step-text">
                            <strong>2. Nosso sistema trabalha</strong>
                            <p>Seu pedido ficará visível na página de Projetos e notificaremos doadores locais com itens compatíveis.</p>
                        </div>
                    </div>

                    <div class="r-step-item">
                        <div class="r-step-icon"><i class="fa-solid fa-handshake"></i></div>
                        <div class="r-step-text">
                            <strong>3. Aceite a Doação</strong>
                            <p>Quando um doador se interessar, você receberá uma mensagem no painel para combinarem os detalhes.</p>
                        </div>
                    </div>
                </div>

                <div class="r-security-box">
                    <i class="fa-solid fa-circle-info"></i>
                    <p>A DoaTech não cobra taxas. Somos uma ponte tecnológica gratuita entre quem precisa e quem pode ajudar.</p>
                </div>
            </aside>

        </div>
    </main>

</body>
</html>