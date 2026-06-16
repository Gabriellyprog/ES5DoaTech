<?php
// processar_login.php
session_start(); // Inicia o motor de sessões do PHP
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha_digitada = $_POST['senha'];

    // Busca o usuário pelo email
    $sql = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
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
            
            // Redireciona para o painel de perfil
            header("Location: perfil.php");
            exit();
            
        } else {
            echo "<script>alert('Senha incorreta!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado com este e-mail.'); window.history.back();</script>";
    }

    $sql->close();
    $conn->close();
}
?>