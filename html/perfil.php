<?php
session_start();
require_once '../script/conexao.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];

// 🔥 Atualizar dados se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pegando os dados do formulário e sanitizando
    $nome = htmlspecialchars(trim($_POST['nome']));
    $email = htmlspecialchars(trim($_POST['email']));
    $cidade = htmlspecialchars(trim($_POST['cidade']));
    $estado = htmlspecialchars(trim($_POST['estado']));
    $endereco = htmlspecialchars(trim($_POST['endereco']));

    // Atualizando no banco
    $sql = "UPDATE usuarios SET nome = ?, email = ?, cidade = ?, estado = ?, endereco = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);

    if ($stmt->execute([$nome, $email, $cidade, $estado, $endereco, $id])) {
        // Atualizar a sessão também, se quiser
        $_SESSION['nome'] = $nome;

        // Recarregar os dados atualizados
        header("Location: perfil.php?atualizado=1");
        exit;
    } else {
        echo "Erro ao atualizar perfil.";
    }
}

// 🔍 Buscar dados atuais do usuário
$stmt = $conexao->prepare("SELECT nome, email, cidade, estado, endereco FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Tempo Voluntário | Perfil</title>
</head>
<body>
    <div class="main-container">
        <div class="navbar">
            <img src="../img/logo1.png" alt="Logo" class="logo">
            <div class="divider"></div>

            <div class="navbar-center">
                <span class="titulo">Tempo Voluntário</span>
            </div>

            <div class="navbar-loged">

                <a class="loginButton" href="perfil.html">
                    <p><?php echo htmlspecialchars($usuario['nome']); ?></p>
                    <img class="user" src="../img/userPicture.avif">
                </a>

                <div class="divider"></div>

                <a href="../script/logout.php" class="logoutButton">Sair</a>

            </div>

        </div>

        <div class="perfil-container">
            <h1>Meu Perfil</h1>

            <form method="POST" id="form-editar">
                <div class="info">
                    <label>Nome:</label>
                    <span id="text-nome"><?php echo htmlspecialchars($usuario['nome']); ?></span>
                    <input type="text" name="nome" id="input-nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" style="display: none;">
                </div>

                <div class="info">
                    <label>Email:</label>
                    <span id="text-email"><?php echo htmlspecialchars($usuario['email']); ?></span>
                    <input type="email" name="email" id="input-email" value="<?php echo htmlspecialchars($usuario['email']); ?>" style="display: none;">
                </div>

                <div class="info">
                    <label>Cidade:</label>
                    <span id="text-cidade"><?php echo htmlspecialchars($usuario['cidade']); ?></span>
                    <input type="text" name="cidade" id="input-cidade" value="<?php echo htmlspecialchars($usuario['cidade']); ?>" style="display: none;">
                </div>

                <div class="info">
                    <label>Estado:</label>
                    <span id="text-estado"><?php echo htmlspecialchars($usuario['estado']); ?></span>
                    <input type="text" name="estado" id="input-estado" value="<?php echo htmlspecialchars($usuario['estado']); ?>" style="display: none;">
                </div>

                <div class="info">
                    <label>Endereço:</label>
                    <span id="text-endereco"><?php echo htmlspecialchars($usuario['endereco']); ?></span>
                    <input type="text" name="endereco" id="input-endereco" value="<?php echo htmlspecialchars($usuario['endereco']); ?>" style="display: none;">
                </div>
                <br><br>
                <div>
                    <button type="button" id="btn-editar" onclick="ativarEdicao()">Editar Perfil</button>
                    <button type="submit" id="btn-salvar" style="display: none;">Salvar</button>
                    <br><br>
                    <button type="button" id="btn-cancelar" onclick="cancelarEdicao()" style="display: none;">Cancelar</button>
                </div>
            </form>

            <a href="../html/pagInicial.php">Sair</a>
        </div>
    </div>
    <script>
        function ativarEdicao() {
            document.getElementById('btn-editar').style.display = 'none';
            document.getElementById('btn-salvar').style.display = 'inline-block';
            document.getElementById('btn-cancelar').style.display = 'inline-block';

            const campos = ['nome', 'email', 'cidade', 'estado', 'endereco'];
            campos.forEach(campo => {
                document.getElementById('text-' + campo).style.display = 'none';
                document.getElementById('input-' + campo).style.display = 'inline-block';
            });
        }

        function cancelarEdicao() {
            document.getElementById('btn-editar').style.display = 'inline-block';
            document.getElementById('btn-salvar').style.display = 'none';
            document.getElementById('btn-cancelar').style.display = 'none';

            const campos = ['nome', 'email', 'cidade', 'estado', 'endereco'];
            campos.forEach(campo => {
                document.getElementById('text-' + campo).style.display = 'inline';
                document.getElementById('input-' + campo).style.display = 'none';
            });
        }
    </script>
</body>
</html>