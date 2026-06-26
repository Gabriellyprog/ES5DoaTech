<?php
// processar_login.php
session_start(); // Inicia o motor de sessões do PHP
require_once __DIR__ . '/flash.php';
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha_digitada = $_POST['senha'];

    // Busca o usuário pelo email
    $sql = $conn->prepare("SELECT id, nome, senha, tipo_usuario FROM usuarios WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $resultado = $sql->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // Verifica se a senha digitada bate com a hash salva no banco
        if (password_verify($senha_digitada, $usuario['senha'])) {
            
            // LOGADO COM SUCESSO! Salva os dados na Sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
            
            set_flash_message('Bem-vindo de volta, ' . $usuario['nome'] . '!', 'success');

            // Redireciona para o painel de perfil
            header("Location: perfil.php");
            exit();
            
        } else {
            redirect_with_flash('login.php', 'Senha incorreta. Verifique e tente novamente.', 'error');
        }
    } else {
        redirect_with_flash('login.php', 'Usuário não encontrado com este e-mail.', 'error');
    }

    $sql->close();
    $conn->close();
}
?>
