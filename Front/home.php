<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>DoaTech - Home</title>
    <link rel="stylesheet" href="home.css">
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
            <h3 style="font-size: 22px; line-height: 1.4;">ONG Digital precisa de 5 mouses para curso de robótica</h3>
            <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                <span style="color: #4ade80; font-weight: bold; font-size: 13px;">DESTAQUES</span>
                <a href="doar.php" class="btn-link-ajudar">Ajudar agora</a>
                
            </div>
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
    
    <div class="item-card">
        <div class="item-icon">🔧</div>
        <div class="item-info">
            <strong>Doação: Kit de Ferramentas</strong>
            <small>Disponível para Ong em Salvador</small>
            <div class="btn-group">
                <a href="projetos.php" class="btn-green" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Demonstrar interesse</a>
                <button class="btn-outline">Ver Detalhes</button>
            </div>
        </div>
    </div>

    <div class="item-card">
        <div class="item-icon">📘</div>
        <div class="item-info">
            <strong>Doação: Material Escolar</strong>
            <small>Disponível para Escola em São Paulo</small>
            <div class="btn-group">
                <a href="projetos.php" class="btn-green" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Demonstrar interesse</a>
                <button class="btn-outline">Ver Detalhes</button>
            </div>
        </div>
    </div>

</div>
</section>

</main>

</body>
</html>