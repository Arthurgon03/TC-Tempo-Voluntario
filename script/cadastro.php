<?php
session_start(); // Inicia a sessão para armazenar mensagens de erro

if (isset($_POST["submit"])) {
    
    // Incluindo a conexão com o banco
    include_once("../script/conexao.php");

    // Captura os dados do formulário
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $telefone = $_POST["telefone"];
    $tipo = $_POST["tipo"];
    $cidade = $_POST["cidade"];
    $estado = $_POST["estado"];
    $endereco = $_POST["endereco"];

    // Verifica se o email já existe no banco de dados
    $sqlCheckEmail = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
    $stmtCheckEmail = $conexao->prepare($sqlCheckEmail);
    $stmtCheckEmail->bindParam(':email', $email);
    $stmtCheckEmail->execute();
    $emailExists = $stmtCheckEmail->fetchColumn();

    if ($emailExists > 0) {
        // Armazena a mensagem de erro na sessão
        $_SESSION['erro_email'] = "❌ Este email já está registrado. Por favor, escolha outro.";
        header("Location: ../html/cadastro.php");
        exit;
    } else {
        // Se o email não existir, realiza o cadastro
        $sql = "INSERT INTO usuarios (nome, email, senha, telefone, tipo, cidade, estado, endereco) 
                VALUES (:nome, :email, :senha, :telefone, :tipo, :cidade, :estado, :endereco)";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':endereco', $endereco);

        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "✅ Cadastro realizado com sucesso!";
            header("Location: ../html/cadastro.php");
            exit;
        } else {
            $_SESSION['erro'] = "❌ Erro ao cadastrar.";
            header("Location: ../html/cadastro.php");
            exit;
        }
    }
}
?>
