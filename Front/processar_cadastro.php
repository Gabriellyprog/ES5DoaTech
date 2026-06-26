<?php
require_once __DIR__ . '/flash.php';
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_usuario = (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] === 'ong') ? 'ong' : 'usuario';
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');
    $localizacao = trim($_POST['localizacao'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    $area_atuacao = trim($_POST['area_atuacao'] ?? '');
    $descricao_ong = trim($_POST['descricao_ong'] ?? '');

    if (empty($nome) || empty($email) || empty($senha)) {
        redirect_with_flash('cadastro.php', 'Preencha nome, e-mail e senha para continuar.', 'warning');
    }

    if ($tipo_usuario === 'ong' && (empty($telefone) || empty($localizacao) || empty($documento) || empty($area_atuacao) || empty($descricao_ong))) {
        redirect_with_flash('cadastro.php', 'Para cadastrar uma ONG, preencha telefone, localização, CNPJ, área de atuação e descrição.', 'warning');
    }

    $stmt_email = $conn->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $resultado_email = $stmt_email->get_result();

    if ($resultado_email && $resultado_email->num_rows > 0) {
        $stmt_email->close();
        $conn->close();
        redirect_with_flash('cadastro.php', 'Esse e-mail já está cadastrado. Faça login ou use outro e-mail.', 'error');
    }
    $stmt_email->close();

    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

    $sql = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo_usuario, telefone, documento, localizacao, area_atuacao, descricao_ong) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$sql) {
        redirect_with_flash('cadastro.php', 'Não foi possível iniciar o cadastro agora. Tente novamente.', 'error');
    }

    $sql->bind_param("sssssssss", $nome, $email, $senha_criptografada, $tipo_usuario, $telefone, $documento, $localizacao, $area_atuacao, $descricao_ong);

    if ($sql->execute()) {
        redirect_with_flash('login.php', 'Conta criada com sucesso! Faça seu login.', 'success');
    } else {
        redirect_with_flash('cadastro.php', 'Não foi possível concluir o cadastro agora. Verifique os dados e tente novamente.', 'error');
    }

    $sql->close();
    $conn->close();
}
?>
