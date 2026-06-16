<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoaTech - Projetos e Necessidades</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="projetos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="proj-body">

    <?php include('header.php'); ?>

    <main class="proj-wrapper">
        
        <div class="proj-header">
            <div class="proj-header-titles">
                <h1>Projetos e <span class="txt-blue">Demandas Ativas</span></h1>
                <p>Encontre a causa ideal ou acompanhe os pedidos da sua região.</p>
            </div>

            <div class="proj-filters">
                <div class="proj-search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Buscar por ONG, item ou cidade...">
                </div>
                <select class="proj-select">
                    <option value="todos">Todas as Categorias</option>
                    <option value="informatica">Informática</option>
                    <option value="ferramentas">Ferramentas</option>
                    <option value="moveis">Móveis</option>
                </select>
                <select class="proj-select">
                    <option value="urgentes">Mais Urgentes</option>
                    <option value="recentes">Mais Recentes</option>
                    <option value="concluidos">Concluídos</option>
                </select>
            </div>
        </div>

        <div class="proj-grid">
            
            <div class="proj-card">
                <div class="proj-badge badge-red">Urgência Alta</div>
                <div class="proj-icon-header" style="color: #38bdf8;">
                    <i class="fa-solid fa-computer"></i>
                </div>
                <h3 class="proj-title">5 Computadores para Curso de Robótica</h3>
                <p class="proj-ong"><i class="fa-solid fa-building-ngo"></i> ONG Vida Digital - São Paulo/SP</p>
                <p class="proj-desc">Precisamos de máquinas básicas para iniciar a turma de adolescentes no próximo mês. Monitores também são bem-vindos!</p>
                
                <div class="proj-progress-area">
                    <div class="proj-progress-text">
                        <span>Arrecadado</span>
                        <strong>2 / 5 itens</strong>
                    </div>
                    <div class="proj-progress-bar">
                        <div class="proj-progress-fill" style="width: 40%;"></div>
                    </div>
                </div>
                
                <a href="doar.php" class="proj-btn"><i class="fa-solid fa-hand-holding-heart"></i> Ajudar Projeto</a>
            </div>

            <div class="proj-card">
                <div class="proj-badge badge-blue">Informática</div>
                <div class="proj-icon-header" style="color: #4ade80;">
                    <i class="fa-solid fa-print"></i>
                </div>
                <h3 class="proj-title">Impressora Multifuncional</h3>
                <p class="proj-ong"><i class="fa-solid fa-building-ngo"></i> Escola Comunitária - Rio de Janeiro/RJ</p>
                <p class="proj-desc">Nossa impressora quebrou e precisamos imprimir as provas dos alunos. Pode ser usada, desde que funcionando.</p>
                
                <div class="proj-progress-area">
                    <div class="proj-progress-text">
                        <span>Arrecadado</span>
                        <strong>0 / 1 itens</strong>
                    </div>
                    <div class="proj-progress-bar">
                        <div class="proj-progress-fill" style="width: 0%;"></div>
                    </div>
                </div>
                
                <a href="doar.php" class="proj-btn"><i class="fa-solid fa-hand-holding-heart"></i> Ajudar Projeto</a>
            </div>

            <div class="proj-card" style="opacity: 0.8;">
                <div class="proj-badge badge-green">Concluído</div>
                <div class="proj-icon-header" style="color: #94a3b8;">
                    <i class="fa-solid fa-toolbox"></i>
                </div>
                <h3 class="proj-title">Kit de Ferramentas para Oficina</h3>
                <p class="proj-ong"><i class="fa-solid fa-building-ngo"></i> Projeto Construir - Belo Horizonte/MG</p>
                <p class="proj-desc">Arrecadamos todas as ferramentas necessárias para as aulas de marcenaria. Obrigado aos doadores!</p>
                
                <div class="proj-progress-area">
                    <div class="proj-progress-text">
                        <span>Arrecadado</span>
                        <strong style="color: #4ade80;">100% Completo</strong>
                    </div>
                    <div class="proj-progress-bar">
                        <div class="proj-progress-fill fill-green" style="width: 100%;"></div>
                    </div>
                </div>
                
                <button class="proj-btn" disabled style="background: #1e293b; color: #94a3b8; cursor: not-allowed;">Meta Atingida</button>
            </div>

        </div>
    </main>

</body>
</html>