<?php session_start(); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cadastro.css">
    <title>Tempo Voluntário | Cadastro</title>
</head>
<body>
    <div class="form-container">
        <?php if (isset($_SESSION['erro_email'])): ?>
            <p class="error-message"><?php echo $_SESSION['erro_email']; unset($_SESSION['erro_email']); ?></p>
        <?php elseif (isset($_SESSION['erro'])): ?>
            <p class="error-message"><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></p>
        <?php elseif (isset($_SESSION['sucesso'])): ?>
            <p class="success-message"><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></p>
        <?php endif; ?>

        <form action="../script/cadastro.php" method="POST" enctype="multipart/form-data"> 
            <legend><b>Faça seu cadastro!</b></legend>
            <br>
            <div class="inputBox">
                <input type="text" name="nome" id="nome" class="inputUser" required>
                <label for="nome" class="labelInput">Nome completo:</label>
            </div>

            <div class="inputBox">
                <input type="text" name="email" id="email" class="inputUser" required>
                <label for="email" class="labelInput">Insira seu email:</label>
            </div>

            <div class="inputBox">
                <input type="password" name="senha" id="senha" class="inputUser" required>
                <label for="senha" class="labelInput">Crie uma senha:</label>
            </div>

            <div class="inputBox">
                <input type="tel" name="telefone" id="telefone" class="inputUser" required>
                <label for="telefone" class="labelInput">Insira seu telefone:</label>
            </div> 
            <div class="selecao">
                <p>Você:</p>
                <input type="radio" name="tipo" id="necessitado" value="Precisa de ajuda" required>
                <label for="necessitado">Precisa de ajuda</label>
                <br>
                <input type="radio" name="tipo" id="voluntario" value="Irá ajudar" required>
                <label for="voluntario">Irá ajudar</label>
            </div>
            
            <br><br>
            <div class="inputBox">
                <input type="text" name="cidade" id="cidade" class="inputUser" required>
                <label for="cidade" class="labelInput">Insira sua cidade:</label>
            </div> 

            <div class="inputBox">
                <input type="text" name="estado" id="estado" class="inputUser" required>
                <label for="estado" class="labelInput">Insira seu estado:</label>
            </div> 

            <div class="inputBox">
                <input type="text" name="endereco" id="endereco" class="inputUser" required>
                <label for="endereco" class="labelInput">Insira seu endereço:</label>
            </div> 

            <div class="inputBox">
                <input type="file" name="user_img" id="user_img" class="inputUser">
                <label for="user_img" class="labelInput">Foto de Perfil (opcional)</label>
            </div>

            <input type="submit" name="submit" id="submit" class="inputButton">
            <p class="voltar">Já tem uma conta? <a href="login.php" class="enter">Entre aqui!</a></p><br>
            <a style="color: rgb(255, 255, 255); font-size: 15px; text-decoration: none; font-style: bold; font-family: Arial, Helvetica, sans-serif; background-color: rgb(73, 67, 63); padding: 6px; border-radius: 5px;" 
                href="index.html">Voltar ao início</a>  
        </form>
    </div>

</body>
</html>