<?php require_once __DIR__ . '/flash.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoaTech - Login</title>
    <link rel="stylesheet" href="auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include('tema.php'); ?>
</head>
<body class="auth-body">

    <div class="auth-glow-1"></div>
    <div class="auth-glow-2"></div>

    <div id="alerta-popup" class="neon-popup">Mensagem de erro aqui</div>
    <?php render_flash_message(); ?>

    <div class="auth-container">
        <div class="auth-box">
            
            <div class="auth-logo">
                <i class="fa-solid fa-microchip"></i>
                <h1><span class="txt-green">DOA</span><span class="txt-blue">TECH</span></h1>
            </div>

            <form id="form-login" action="processar_login.php" method="POST" class="auth-form">
                <div class="input-group">
                    <input type="email" name="email" placeholder="E-mail" required>
                    <i class="fa-regular fa-user input-icon"></i>
                </div>

                <div class="input-group">
                    <input type="password" id="senha-login" name="senha" placeholder="Senha" required>
                    <i class="fa-solid fa-lock input-icon"></i>
                </div>

                <div class="auth-forgot">
                    <a href="#">Esqueceu a senha?</a>
                </div>

                <button type="submit" class="auth-btn">Entrar</button>
            </form>

            <div class="auth-footer">
                Não tem conta? <a href="cadastro.php">Cadastre-se</a>
            </div>

        </div>
    </div>

    <script>
        function mostrarPopup(mensagem) {
            const popup = document.getElementById("alerta-popup");
            popup.innerText = mensagem;
            popup.classList.add("show");
            
            setTimeout(function() {
                popup.classList.remove("show");
            }, 4000);
        }

        document.getElementById('form-login').addEventListener('submit', function(event) {
            const senha = document.getElementById('senha-login').value;
            const regexSenha = /^(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

            if (!regexSenha.test(senha)) {
                event.preventDefault(); // Impede o redirecionamento
                mostrarPopup('Senha inválida. Ela deve conter no mínimo 8 caracteres, maiúsculas e minúsculas.');
                return;
            }
        });
    </script>
</body>
</html>
