<?php
session_start();
include_once("../script/conexao.php");

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: pagInicial.php");
    exit;
}

$idAdmin = $_SESSION['id'];

try {
    $sql = "SELECT id, nome, email, tipo, cidade, telefone FROM usuarios WHERE tipo != 'admin'";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar usuários: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Excluir Contas</title>
    <link rel="stylesheet" href="../css/excluirConta.css">
    <link rel="stylesheet" href="../css/pagInicial.css">
    
</head>
<body>

<div class="navbar">
    <a href="pagInicial.php"><img src="../img/logo1.png" alt="Logo" class="logo"></a>

    <div class="navbar-loged">
        <a class="loginButton" href="perfil.php">
            <p><?php echo htmlspecialchars($_SESSION['nome']); ?></p>
            <img class="user" src="../img/userPicture.avif">
        </a>
        <div class="divider"></div>
        <a href="../script/logout.php" class="logoutButton">Sair</a>
    </div>

    <div class="navbar-center">
        <span class="titulo">Área Administrativa - Excluir Contas</span>
    </div>
</div>
<br><br>
<h2 style="text-align: center;">Lista de Usuários</h2>

<?php if (isset($_SESSION['sucesso_excluir'])): ?>
    <p style="color: green;"><?php echo $_SESSION['sucesso_excluir']; unset($_SESSION['sucesso_excluir']); ?></p>
<?php elseif (isset($_SESSION['erro_excluir'])): ?>
    <p style="color: red;"><?php echo $_SESSION['erro_excluir']; unset($_SESSION['erro_excluir']); ?></p>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Tipo</th>
        <th>Cidade</th>
        <th>Telefone</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
            <td><?php echo htmlspecialchars($usuario['tipo']); ?></td>
            <td><?php echo htmlspecialchars($usuario['cidade']); ?></td>
            <td><?php echo htmlspecialchars($usuario['telefone']); ?></td>
            <td>
                <form action="../script/processarExclusao.php" method="post" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                    <input type="hidden" name="idUsuario" value="<?php echo $usuario['id']; ?>">
                    <button type="submit">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

<div class="voltar">
    <a href="pagInicial.php">Voltar</a>
</div>

</body>
</html>