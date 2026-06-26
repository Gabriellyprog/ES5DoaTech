<?php require_once __DIR__ . '/flash.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoaTech - Cadastro</title>
    <link rel="stylesheet" href="auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include('tema.php'); ?>
</head>
<body class="auth-body">

    <div class="auth-glow-1"></div>
    <div class="auth-glow-2"></div>

    <div id="alerta-popup" class="neon-popup">Mensagem de erro aqui</div>
    <?php render_flash_message(); ?>

    <div class="auth-container cadastro-container">
        <div class="auth-box">
            
            <div class="auth-logo" style="margin-bottom: 30px;">
                <i class="fa-solid fa-microchip"></i>
                <h1><span class="txt-green">DOA</span><span class="txt-blue">TECH</span></h1>
                <p style="color: #94a3b8; font-size: 14px; margin-top: 5px;">Crie sua conta para começar a doar</p>
            </div>

            <form id="form-cadastro" action="processar_cadastro.php" method="POST" class="auth-form">
                <div class="account-type-options" role="group" aria-label="Tipo de cadastro">
                    <label class="account-type-card active" data-account-card="usuario">
                        <input type="radio" name="tipo_usuario" value="usuario" checked>
                        <span><i class="fa-solid fa-user"></i></span>
                        <div class="account-type-copy">
                        <strong>Usuário</strong>
                        <small>Quero doar ou pedir ajuda.</small>
                        </div>
                    </label>

                    <label class="account-type-card" data-account-card="ong">
                        <input type="radio" name="tipo_usuario" value="ong">
                        <span><i class="fa-solid fa-hand-holding-heart"></i></span>
                        <div class="account-type-copy">
                        <strong>Cadastrar ONG</strong>
                        <small>Represento um projeto social.</small>
                        </div>
                    </label>
                </div>
                
                <div class="input-group">
                    <input type="text" id="nome-cadastro" name="nome" placeholder="Nome Completo" required>
                    <i class="fa-regular fa-address-card input-icon"></i>
                </div>

                <div class="input-group">
                    <input type="email" name="email" placeholder="E-mail" required>
                    <i class="fa-regular fa-envelope input-icon"></i>
                </div>

                <div id="ong-fields" class="ong-fields">
                    <div class="input-group">
                        <input type="text" name="telefone" placeholder="Telefone para contato">
                        <i class="fa-solid fa-phone input-icon"></i>
                    </div>

                    <div class="input-group">
                        <input type="text" name="localizacao" placeholder="Cidade / Estado">
                        <i class="fa-solid fa-location-dot input-icon"></i>
                    </div>

                    <div class="input-group">
                        <input type="text" name="documento" placeholder="CNPJ da ONG">
                        <i class="fa-regular fa-id-card input-icon"></i>
                    </div>

                    <div class="input-group">
                        <input type="text" name="area_atuacao" placeholder="Área de atuação">
                        <i class="fa-solid fa-seedling input-icon"></i>
                    </div>

                    <div class="input-group">
                        <textarea name="descricao_ong" placeholder="Conte rapidamente o que a ONG faz"></textarea>
                        <i class="fa-solid fa-align-left input-icon textarea-icon"></i>
                    </div>
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

        const accountCards = document.querySelectorAll('.account-type-card');
        const accountRadios = document.querySelectorAll('input[name="tipo_usuario"]');
        const ongFields = document.getElementById('ong-fields');
        const nomeCadastro = document.getElementById('nome-cadastro');

        function atualizarTipoCadastro() {
            const tipoSelecionado = document.querySelector('input[name="tipo_usuario"]:checked').value;
            accountCards.forEach(function(card) {
                card.classList.toggle('active', card.dataset.accountCard === tipoSelecionado);
            });

            const camposOng = ongFields.querySelectorAll('input, textarea');
            if (tipoSelecionado === 'ong') {
                ongFields.classList.add('visible');
                nomeCadastro.placeholder = 'Nome da ONG';
                camposOng.forEach(function(campo) {
                    campo.required = true;
                });
            } else {
                ongFields.classList.remove('visible');
                nomeCadastro.placeholder = 'Nome Completo';
                camposOng.forEach(function(campo) {
                    campo.required = false;
                });
            }
        }

        accountRadios.forEach(function(radio) {
            radio.addEventListener('change', atualizarTipoCadastro);
        });

        atualizarTipoCadastro();

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
