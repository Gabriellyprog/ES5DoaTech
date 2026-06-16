<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoaTech - Sobre Nós</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="sobre.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="sobre-body">

    <?php include('header.php'); ?>

    <main class="sobre-wrapper">
        
        <section class="sobre-hero">
            <h1>Nossa Missão: <span class="txt-green">Conectar</span> e <span class="txt-blue">Transformar</span></h1>
            <p>A DoaTech não é apenas uma plataforma. Somos uma ponte entre a tecnologia ociosa e quem realmente precisa dela para estudar, trabalhar e evoluir.</p>
        </section>

        <section class="sobre-section">
            <h2 class="section-title"><i class="fa-solid fa-gears"></i> Como o Sistema Funciona</h2>
            <div class="sobre-grid-3">
                <div class="sobre-card">
                    <div class="icon-wrapper green-glow"><i class="fa-solid fa-box-open"></i></div>
                    <h3>1. Disponibilidade</h3>
                    <p>Doadores físicos ou empresas cadastram equipamentos que não usam mais (computadores, monitores, peças), detalhando seu estado real de conservação.</p>
                </div>
                <div class="sobre-card">
                    <div class="icon-wrapper blue-glow"><i class="fa-solid fa-tower-cell"></i></div>
                    <h3>2. Conexão</h3>
                    <p>ONGs, escolas e projetos sociais publicam suas necessidades. Nosso sistema atua como um mural inteligente, conectando a demanda à oferta local.</p>
                </div>
                <div class="sobre-card">
                    <div class="icon-wrapper green-glow"><i class="fa-solid fa-handshake-angle"></i></div>
                    <h3>3. Transformação</h3>
                    <p>Através do nosso chat interno, doador e receptor combinam a logística de entrega com segurança, finalizando o ciclo da doação.</p>
                </div>
            </div>
        </section>

        <div class="sobre-grid-2">
            
            <section class="sobre-card rules-card">
                <h2 class="section-title txt-blue"><i class="fa-solid fa-scale-balanced"></i> Regras de Ouro</h2>
                <ul class="rules-list">
                    <li>
                        <i class="fa-solid fa-check"></i>
                        <div>
                            <strong>Transparência Total</strong>
                            <p>Doadores devem ser honestos sobre defeitos ou falta de peças nos itens cadastrados.</p>
                        </div>
                    </li>
                    <li>
                        <i class="fa-solid fa-ban"></i>
                        <div>
                            <strong>Proibido Revenda</strong>
                            <p>Itens recebidos via DoaTech são estritamente para uso social ou educacional. A revenda gera banimento imediato.</p>
                        </div>
                    </li>
                    <li>
                        <i class="fa-solid fa-shield-heart"></i>
                        <div>
                            <strong>Respeito e Segurança</strong>
                            <p>Toda comunicação deve ser feita no chat da plataforma até que ambas as partes se sintam seguras para trocar contatos.</p>
                        </div>
                    </li>
                    <li>
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                        <div>
                            <strong>100% Gratuito</strong>
                            <p>É proibido cobrar qualquer taxa pelos equipamentos. A DoaTech é e sempre será uma ponte gratuita.</p>
                        </div>
                    </li>
                </ul>
            </section>

            <section class="sobre-card impact-card">
                <h2 class="section-title txt-green"><i class="fa-solid fa-earth-americas"></i> Impacto no Cotidiano</h2>
                
                <div class="impact-item">
                    <div class="impact-icon"><i class="fa-solid fa-recycle"></i></div>
                    <div>
                        <h3>Redução do Lixo Eletrônico (E-Waste)</h3>
                        <p>Milhares de toneladas de eletrônicos são descartados incorretamente por ano. Ao doar, você aumenta a vida útil dos componentes e protege o meio ambiente de metais pesados.</p>
                    </div>
                </div>

                <div class="impact-item">
                    <div class="impact-icon" style="color: #38bdf8; background: rgba(56, 189, 248, 0.1); border-color: rgba(56, 189, 248, 0.2);"><i class="fa-solid fa-graduation-cap"></i></div>
                    <div>
                        <h3>Inclusão Digital e Educação</h3>
                        <p>Um computador encostado na sua gaveta pode ser a ferramenta que falta para um jovem aprender a programar, montar um currículo ou terminar a escola.</p>
                    </div>
                </div>

                <div class="impact-stats">
                    <div class="stat-box">
                        <strong class="txt-green">♻️ Economia Circular</strong>
                        <span>Reutilizar é melhor que reciclar.</span>
                    </div>
                    <div class="stat-box">
                        <strong class="txt-blue">💡 Oportunidade</strong>
                        <span>Acesso à tecnologia muda vidas.</span>
                    </div>
                </div>
            </section>

        </div>
    </main>

</body>
</html>