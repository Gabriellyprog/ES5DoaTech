<?php
// processar_pedido.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Você precisa estar logado para solicitar uma doação!'); window.location.href = 'login.php';</script>";
    exit();
}

include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['usuario_id'];
    $titulo = $_POST['titulo'];
    $categoria = $_POST['categoria'];
    $urgencia = $_POST['urgencia'];
    $historia = $_POST['historia'];
    $status = 'Aberto'; // O pedido nasce em aberto

    $nome_comprovante = null;

    // Upload de documento ou foto da ONG
    if (isset($_FILES['comprovante']) && $_FILES['comprovante']['error'] == 0) {
        $pasta_destino = "uploads/";
        if (!is_dir($pasta_destino)) { mkdir($pasta_destino, 0777, true); }

        $extensao = pathinfo($_FILES['comprovante']['name'], PATHINFO_EXTENSION);
        $nome_comprovante = "doc_" . time() . "_" . rand(100, 999) . "." . $extensao;
        
        move_uploaded_file($_FILES['comprovante']['tmp_name'], $pasta_destino . $nome_comprovante);
    }

    $sql = "INSERT INTO pedidos (id_usuario, titulo, categoria, urgencia, historia, comprovantes, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $id_usuario, $titulo, $categoria, $urgencia, $historia, $nome_comprovante, $status);

    if ($stmt->execute()) {
        echo "<script>
                alert('Pedido registrado com sucesso! Ele aparecerá no mural em breve.');
                window.location.href = 'projetos.php';
              </script>";
    } else {
        echo "<script>alert('Erro ao registrar o pedido.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>