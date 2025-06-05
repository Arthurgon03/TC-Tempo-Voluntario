<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include_once("../script/conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $_SESSION['erro_login'] = "❌ Preencha todos os campos!";
        header("Location: ../html/login.php");
        exit;
    }

    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['tipo'] = $usuario['tipo']; 

            header("Location: ../html/pagInicial.php");
            exit;
        } else {
            $_SESSION['erro_login'] = "Senha incorreta!";
            header("Location: ../html/login.php");
            exit;
        }
    } else {
        $_SESSION['erro_login'] = "Usuário não encontrado!";
        header("Location: ../html/login.php");
        exit;
    }
} else {
    $_SESSION['erro_login'] = "Acesso inválido!";
    header("Location: ../html/login.php");
    exit;
}
?>