<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoaTech - Cadastro</title>
    <link rel="stylesheet" href="auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="auth-body">

    <div class="auth-glow-1"></div>
    <div class="auth-glow-2"></div>

    <div id="alerta-popup" class="neon-popup">Mensagem de erro aqui</div>

    <div class="auth-container" style="max-width: 450px;">
        <div class="auth-box">
            
            <div class="auth-logo" style="margin-bottom: 30px;">
                <i class="fa-solid fa-microchip"></i>
                <h1><span class="txt-green">DOA</span><span class="txt-blue">TECH</span></h1>
                <p style="color: #94a3b8; font-size: 14px; margin-top: 5px;">Crie sua conta para começar a doar</p>
            </div>

            <form id="form-cadastro" action="processar_cadastro.php" method="POST" class="auth-form">
                
                <div class="input-group">
                    <input type="text" name="nome" placeholder="Nome Completo" required>
                    <i class="fa-regular fa-address-card input-icon"></i>
                </div>

                <div class="input-group">
                    <input type="email" name="email" placeholder="E-mail" required>
                    <i class="fa-regular fa-envelope input-icon"></i>
                </div>

                <div class="input-group">
                    <input type="password" id="senha-cadastro" name="senha" placeholder="Crie uma Senha" required>
                    <i class="fa-solid fa-lock input-icon"></i>
                </div>

                <div class="input-group">
                    <input type="password" id="confirma-senha" name="confirma_senha" placeholder="Confirme a Senha" required>
                    <i class="fa-solid fa-shield-check input-icon"></i>
                </div>

                <button type="submit" class="auth-btn">Criar Conta</button>
            </form>

            <div class="auth-footer">
                Já possui uma conta? <a href="login.php">Faça login</a>
            </div>

        </div>
    </div>

    <script>
        function mostrarPopup(mensagem) {
            const popup = document.getElementById("alerta-popup");
            popup.innerText = mensagem;
            popup.classList.add("show");
            
            // Some automaticamente após 4 segundos
            setTimeout(function() {
                popup.classList.remove("show");
            }, 4000);
        }

        document.getElementById('form-cadastro').addEventListener('submit', function(event) {
            const senha = document.getElementById('senha-cadastro').value;
            const confirmaSenha = document.getElementById('confirma-senha').value;
            
            // Regex: Pelo menos 1 minúscula (?=.*[a-z]), 1 maiúscula (?=.*[A-Z]), e mínimo 8 caracteres (.{8,})
            const regexSenha = /^(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

            if (!regexSenha.test(senha)) {
                event.preventDefault(); // Impede o redirecionamento
                mostrarPopup('A senha deve ter no mínimo 8 caracteres, incluindo uma letra maiúscula e uma minúscula.');
                return;
            }

            if (senha !== confirmaSenha) {
                event.preventDefault(); // Impede o redirecionamento
                mostrarPopup('As senhas não coincidem. Verifique e tente novamente.');
                return;
            }
        });
    </script>
</body>
</html>