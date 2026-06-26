<?php
session_start();
include 'conexao.php';

// Verifica se veio alguma palavra chave de busca pela URL (vindo da Home ou digitado no filtro)
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$categoria_filtro = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';

if (!empty($busca) && (empty($categoria_filtro) || $categoria_filtro === 'todos')) {
    // Se houver uma busca, filtra os pedidos pelo título do item ou nome da categoria
    $sql = "SELECT p.*, u.nome AS ong_nome, u.localizacao 
            FROM pedidos p 
            JOIN usuarios u ON p.id_usuario = u.id 
            WHERE p.status = 'Aberto' AND (p.titulo LIKE ? OR p.categoria LIKE ? OR u.nome LIKE ? OR u.localizacao LIKE ?)
            ORDER BY p.data_cadastro DESC";
            
    $stmt = $conn->prepare($sql);
    $termo = "%" . $busca . "%";
    $stmt->bind_param("ssss", $termo, $termo, $termo, $termo);
    $stmt->execute();
    $resultado = $stmt->get_result();
} elseif (!empty($busca) && !empty($categoria_filtro) && $categoria_filtro !== 'todos') {
    $sql = "SELECT p.*, u.nome AS ong_nome, u.localizacao
            FROM pedidos p
            JOIN usuarios u ON p.id_usuario = u.id
            WHERE p.status = 'Aberto' AND p.categoria = ? AND (p.titulo LIKE ? OR p.categoria LIKE ? OR u.nome LIKE ? OR u.localizacao LIKE ?)
            ORDER BY p.data_cadastro DESC";

    $stmt = $conn->prepare($sql);
    $termo = "%" . $busca . "%";
    $stmt->bind_param("sssss", $categoria_filtro, $termo, $termo, $termo, $termo);
    $stmt->execute();
    $resultado = $stmt->get_result();
} elseif (!empty($categoria_filtro) && $categoria_filtro !== 'todos') {
    $sql = "SELECT p.*, u.nome AS ong_nome, u.localizacao
            FROM pedidos p
            JOIN usuarios u ON p.id_usuario = u.id
            WHERE p.status = 'Aberto' AND p.categoria = ?
            ORDER BY p.data_cadastro DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $categoria_filtro);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    // Se não houver busca, traz todos os projetos normalmente como já estava
    $sql = "SELECT p.*, u.nome AS ong_nome, u.localizacao 
            FROM pedidos p 
            JOIN usuarios u ON p.id_usuario = u.id 
            WHERE p.status = 'Aberto'
            ORDER BY p.data_cadastro DESC";
    $resultado = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoaTech - Projetos e Necessidades</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="projetos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include('tema.php'); ?>
</head>
<body class="proj-body">

    <?php include('header.php'); ?>

    <main class="proj-wrapper">
        
        <div class="proj-header">
            <div class="proj-header-titles">
                <h1>Projetos e <span class="txt-blue">Demandas Ativas</span></h1>
                <p>Encontre a causa ideal ou acompanhe os pedidos da sua região.</p>
            </div>

            <form class="proj-filters" method="GET" action="projetos.php">
                <div class="proj-search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="busca" value="<?php echo htmlspecialchars($busca); ?>" placeholder="Buscar por ONG, item ou cidade...">
                </div>
                <select class="proj-select" name="categoria">
                    <option value="todos">Todas as Categorias</option>
                    <option value="informatica" <?php echo ($categoria_filtro === 'informatica') ? 'selected' : ''; ?>>InformÃ¡tica</option>
                    <option value="ferramentas" <?php echo ($categoria_filtro === 'ferramentas') ? 'selected' : ''; ?>>Ferramentas</option>
                    <option value="moveis" <?php echo ($categoria_filtro === 'moveis') ? 'selected' : ''; ?>>MÃ³veis</option>
                    <option value="material_escolar" <?php echo ($categoria_filtro === 'material_escolar') ? 'selected' : ''; ?>>Material Escolar</option>
                    <option value="outros" <?php echo ($categoria_filtro === 'outros') ? 'selected' : ''; ?>>Outros</option>
                </select>
                <button class="proj-filter-btn" type="submit"><i class="fa-solid fa-filter"></i> Filtrar</button>
            </form>
        </div>

        <div class="proj-grid">
            
            <?php if ($resultado->num_rows > 0): ?>
                <?php while($projeto = $resultado->fetch_assoc()): 
                    
                    // Lógica para mudar a cor da etiqueta dependendo da Urgência
                    $badge_class = 'badge-blue';
                    $urgencia = strtolower($projeto['urgencia']);
                    if ($urgencia == 'alta' || $urgencia == 'urgente') {
                        $badge_class = 'badge-red';
                    } elseif ($urgencia == 'baixa') {
                        $badge_class = 'badge-green';
                    }

                    // Lógica para colocar um ícone diferente dependendo da Categoria
                    $icone = 'fa-box-open';
                    $cor_icone = '#38bdf8';
                    $cat = strtolower($projeto['categoria']);
                    if (strpos($cat, 'inform') !== false) { $icone = 'fa-desktop'; $cor_icone = '#38bdf8'; }
                    elseif (strpos($cat, 'ferramenta') !== false) { $icone = 'fa-screwdriver-wrench'; $cor_icone = '#94a3b8'; }
                    elseif (strpos($cat, 'móvei') !== false || strpos($cat, 'movei') !== false) { $icone = 'fa-chair'; $cor_icone = '#4ade80'; }
                    elseif (strpos($cat, 'escolar') !== false) { $icone = 'fa-book-open'; $cor_icone = '#facc15'; }
                ?>
                
                <div class="proj-card">
                    <div class="proj-badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($projeto['urgencia']); ?></div>
                    
                    <div class="proj-icon-header" style="color: <?php echo $cor_icone; ?>;">
                        <i class="fa-solid <?php echo $icone; ?>"></i>
                    </div>
                    
                    <h3 class="proj-title"><?php echo htmlspecialchars($projeto['titulo']); ?></h3>
                    
                    <p class="proj-ong">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                        <?php echo htmlspecialchars($projeto['ong_nome']); ?> - <?php echo !empty($projeto['localizacao']) ? htmlspecialchars($projeto['localizacao']) : 'Local não informado'; ?>
                    </p>
                    
                    <p class="proj-desc"><?php echo htmlspecialchars($projeto['historia']); ?></p>
                    
                    <a href="perfil.php?aba=mensagens&contato_id=<?php echo $projeto['id_usuario']; ?>&nome_contato=<?php echo urlencode($projeto['ong_nome']); ?>" class="proj-btn"><i class="fa-solid fa-hand-holding-heart"></i> Ajudar Projeto</a>
                </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px 0;">
                    <i class="fa-solid fa-folder-open" style="font-size: 40px; color: #1e293b; margin-bottom: 15px;"></i>
                    <p style="color: #94a3b8; font-size: 18px;">Nenhuma demanda cadastrada no momento.</p>
                </div>
            <?php endif; ?>

        </div>
    </main>

</body>
</html>
