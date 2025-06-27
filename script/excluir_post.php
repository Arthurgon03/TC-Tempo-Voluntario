<?php
session_start();
include_once("conexao.php");

if (!isset($_SESSION['id'])) {
    header("Location: ../html/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_post'])) {
    $idPost = $_POST['id_post'];
    $usuarioAtual = $_SESSION['id'];
    $isAdmin = $_SESSION['tipo'] === 'admin';

    try {
        $sql = "SELECT id_usuario FROM publicacoes WHERE id = :id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":id", $idPost);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            die("Post não encontrado.");
        }

        if ($post['id_usuario'] != $usuarioAtual && !$isAdmin) {
            die("Acesso negado.");
        }

        $sqlDelete = "DELETE FROM publicacoes WHERE id = :id";
        $stmtDelete = $conexao->prepare($sqlDelete);
        $stmtDelete->bindParam(":id", $idPost);
        $stmtDelete->execute();

        header("Location: ../html/pagInicial.php");
        exit;

    } catch (PDOException $e) {
        echo "Erro ao excluir post: " . $e->getMessage();
    }
} else {
    echo "Requisição inválida.";
}
?>