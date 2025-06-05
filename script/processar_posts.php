<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['id'])) {
    die("Usuário não autenticado.");
}

$id_usuario = $_SESSION['id'];
$texto_post = $_POST['texto_post'] ?? '';
$imagemPath = null;

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
    $pastaDestino = '../html/uploads';
    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0777, true);
    }

    $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $nomeArquivo = uniqid() . '.' . $extensao;
    $caminhoCompleto = $pastaDestino . '/' . $nomeArquivo;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoCompleto)) {
        $imagemPath = 'uploads/' . $nomeArquivo;
    } else {
        die('Erro ao salvar a imagem.');
    }
}

try {
    $sql = "INSERT INTO publicacoes (id_usuario, texto_post, imagem) 
            VALUES (:id_usuario, :texto_post, :imagem)";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':texto_post', $texto_post);
    $stmt->bindParam(':imagem', $imagemPath);
    $stmt->execute();

    header("Location: ../html/pagInicial.php");
    exit;
} catch (PDOException $e) {
    echo "Erro ao salvar publicação: " . $e->getMessage();
}
?>