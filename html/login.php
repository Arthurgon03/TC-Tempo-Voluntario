</body><?php session_start(); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <title>Tempo Voluntário | Login</title>
</head>
<body>
    <div class="form-container">
        <h2>Faça seu Login</h2>

        <!-- Exibir mensagens de erro ou sucesso -->
        <?php if (isset($_SESSION['erro_login'])): ?>
            <p class="error-message"><?php echo $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?></p>
        <?php elseif (isset($_SESSION['sucesso_logout'])): ?>
            <p class="success-message"><?php echo $_SESSION['sucesso_logout']; unset($_SESSION['sucesso_logout']); ?></p>
        <?php endif; ?>

        <form action="../script/login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Login</button>
            <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui!</a></p>
            <br><br>
            <a style="color: rgb(255, 255, 255); font-size: 20px; text-decoration: none; font-style: bold; font-family: Arial, Helvetica, sans-serif; background-color: rgb(73, 67, 63); padding: 10px; border-radius: 5px; text-align: right;" href="index.html">Voltar ao início</a>
        </form>
    </div>

</body>
</html>