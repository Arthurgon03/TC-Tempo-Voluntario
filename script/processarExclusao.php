<?php
session_start();
include_once("../script/conexao.php");

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: pagInicial.php");
    exit;
}

if (isset($_POST['idUsuario'])) {
    $idUsuario = $_POST['idUsuario'];

    try {
        $conexao->beginTransaction();

        $sqlDeletePosts = "DELETE FROM publicacoes WHERE id_usuario = :id";
        $stmtPosts = $conexao->prepare($sqlDeletePosts);
        $stmtPosts->bindParam(':id', $idUsuario, PDO::PARAM_INT);
        $stmtPosts->execute();

        $sqlDeleteUser = "DELETE FROM usuarios WHERE id = :id";
        $stmtUser = $conexao->prepare($sqlDeleteUser);
        $stmtUser->bindParam(':id', $idUsuario, PDO::PARAM_INT);
        $stmtUser->execute();

        $conexao->commit();

        $_SESSION['sucesso_excluir'] = "✅ Usuário excluído com sucesso!";
        header("Location: ../html/excluirConta.php");
        exit;

    } catch (PDOException $e) {
        $conexao->rollBack();
        $_SESSION['erro_excluir'] = "❌ Erro ao excluir: " . $e->getMessage();
        header("Location: ../html/excluirConta.php");
        exit;
    }
} else {
    $_SESSION['erro_excluir'] = "❌ ID do usuário não especificado.";
    header("Location: ../html/excluirConta.php");
    exit;
}
?>