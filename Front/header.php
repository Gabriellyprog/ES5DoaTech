<?php
// Garante que a sessão está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lógica para puxar a foto do usuário logado para o cabeçalho
$foto_header = 'uploads/default.png'; // Caminho padrão caso não tenha foto
if (isset($_SESSION['usuario_id'])) {
    include_once('conexao.php'); // Usa include_once para não dar conflito com outras páginas
    $stmt_header = $conn->prepare("SELECT foto FROM usuarios WHERE id = ?");
    $stmt_header->bind_param("i", $_SESSION['usuario_id']);
    $stmt_header->execute();
    $resultado_header = $stmt_header->get_result();
    
    if ($resultado_header->num_rows > 0) {
        $usuario_header = $resultado_header->fetch_assoc();
        if (!empty($usuario_header['foto'])) {
            $foto_header = 'uploads/' . $usuario_header['foto'];
        }
    }
}
?>
<header class="main-header">
    <div class="logo">
        <a href="home.php" id="logo-header-link" style="text-decoration: none;">
            <span style="color: #38bdf8;">DOA</span><span style="color: #4ade80;">TECH</span>
        </a>
    </div>
    
    <nav class="nav-links">
        <a href="doar.php">Doar</a>
        <a href="receber.php">Receber</a>
        <a href="projetos.php">Projetos</a>
        <a href="sobre.php">Sobre</a>
    </nav>

    <div class="header-right" style="position: relative; display: flex; align-items: center; gap: 20px;">
        <span style="cursor: pointer; font-size: 18px;">🔔</span>

        <?php if(isset($_SESSION['usuario_id'])): ?>
            <div class="user-dropdown-container" style="position: relative;">
                
                <div id="profile-btn" style="width: 40px; height: 40px; border-radius: 50%; background-image: url('<?php echo $foto_header; ?>'); background-size: cover; background-position: center; cursor: pointer; border: 2px solid #38bdf8; transition: 0.3s;">
                </div>

                <div id="dropdown-menu" style="display: none; position: absolute; top: 55px; right: 0; background: #11141d; border: 1px solid #1e293b; border-radius: 12px; width: 160px; box-shadow: 0 10px 30px rgba(0,0,0,0.8); z-index: 9999; overflow: hidden;">
                    <a href="perfil.php" class="dropdown-item" style="display: block; padding: 12px 16px; color: white; text-decoration: none; border-bottom: 1px solid #1e293b; font-size: 14px;">
                        <i class="fa-solid fa-user" style="margin-right: 10px; color: #38bdf8;"></i> Meu Perfil
                    </a>
                    <a href="logout.php" class="dropdown-item" style="display: block; padding: 12px 16px; color: #ef4444; text-decoration: none; font-size: 14px;">
                        <i class="fa-solid fa-right-from-bracket" style="margin-right: 10px;"></i> Sair
                    </a>
                </div>

            </div>
        <?php else: ?>
            <a href="login.php" class="perfil-tag" style="text-decoration: none; background-color: #4ade80; color: #05070a; padding: 10px 25px; border-radius: 25px; font-weight: bold; font-family: 'Inter', sans-serif;">Entrar</a>
        <?php endif; ?>

    </div>
</header>

<style>
    .dropdown-item:hover {
        background-color: #1e293b;
    }
    #profile-btn:hover {
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileBtn = document.getElementById('profile-btn');
        const dropdownMenu = document.getElementById('dropdown-menu');

        if (profileBtn && dropdownMenu) {
            profileBtn.addEventListener('click', function(event) {
                event.stopPropagation(); 
                if (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') {
                    dropdownMenu.style.display = 'block';
                } else {
                    dropdownMenu.style.display = 'none';
                }
            });

            document.addEventListener('click', function(event) {
                if (!profileBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.style.display = 'none';
                }
            });
        }
    });
</script>