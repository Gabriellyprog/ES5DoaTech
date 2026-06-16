<?php
// processar_cadastro.php
include 'conexao.php'; // Chama o arquivo de banco de dados que criamos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // Criptografa a senha para segurança
    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

    // Prepara o comando SQL (usando prepared statements para evitar invasões/SQL Injection)
    $sql = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $sql->bind_param("sss", $nome, $email, $senha_criptografada);

    if ($sql->execute()) {
        // Deu certo! Manda o usuário para a tela de login com um aviso via JavaScript
        echo "<script>
                alert('Conta criada com sucesso! Faça seu login.');
                window.location.href = 'login.php';
              </script>";
    } else {
        // Provavelmente o e-mail já existe
        echo "<script>
                alert('Erro ao cadastrar. Este e-mail já pode estar em uso.');
                window.history.back();
              </script>";
    }

    $sql->close();
    $conn->close();
}
?>