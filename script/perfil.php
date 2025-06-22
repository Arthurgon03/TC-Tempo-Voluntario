<?php
session_start();
require_once 'script/conexao.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = htmlspecialchars(trim($_POST['nome']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $cidade = htmlspecialchars(trim($_POST['cidade']));
    $estado = htmlspecialchars(trim($_POST['estado']));
    $endereco = htmlspecialchars(trim($_POST['endereco']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email inválido.";
        exit();
    }

    $stmt = $conexao->prepare("UPDATE usuarios SET nome = ?, email = ?, cidade = ?, estado = ?, endereco = ? WHERE id = ?");
    $stmt->execute([$nome, $email, $cidade, $estado, $endereco, $id]);

    $_SESSION['nome'] = $nome;

    header("Location: perfil.php");
    exit();
}

$stmt = $pdo->prepare("SELECT nome, email, cidade, estado, endereco FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit();
}
?>