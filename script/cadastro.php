<?php
if (isset($_POST["submit"])) {
    session_start();
    include_once("../script/conexao.php");

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $telefone = $_POST["telefone"];
    $tipo = $_POST["tipo"];
    $cidade = $_POST["cidade"];
    $estado = $_POST["estado"];
    $endereco = $_POST["endereco"];

    if (isset($_FILES['user_img']) && $_FILES['user_img']['error'] == 0) {
        $pasta = '../html/userImagens/';
        $nome_arquivo = uniqid() . "-" . $_FILES['user_img']['name'];
        $destino = $pasta . $nome_arquivo;

        if (move_uploaded_file($_FILES['user_img']['tmp_name'], $destino)) {
            $user_img = $nome_arquivo;
        } else {
            $_SESSION['erro'] = "Erro ao fazer upload da imagem.";
            header("Location: ../html/cadastro.php");
            exit;
        }
    } else {
        $user_img = 'userPicture.avif';
    }

    $sqlCheckEmail = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
    $stmtCheckEmail = $conexao->prepare($sqlCheckEmail);
    $stmtCheckEmail->bindParam(':email', $email);
    $stmtCheckEmail->execute();
    $emailExists = $stmtCheckEmail->fetchColumn();

    if ($emailExists > 0) {
        $_SESSION['erro_email'] = "Este email já está registrado. Por favor, escolha outro.";
        header("Location: ../html/cadastro.php");
        exit;
    } else {
        $sql = "INSERT INTO usuarios (nome, email, senha, telefone, tipo, cidade, estado, endereco, user_img) 
                VALUES (:nome, :email, :senha, :telefone, :tipo, :cidade, :estado, :endereco, :user_img)";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':user_img', $user_img);

        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
            header("Location: ../html/pagInicial.php");
            exit;
        } else {
            $_SESSION['erro'] = "Erro ao cadastrar!";
            header("Location: ../html/cadastro.php");
            exit;
        }
    }
}
?>