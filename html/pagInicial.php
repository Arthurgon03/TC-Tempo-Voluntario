<?php
session_start();
include_once("../script/conexao.php");

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];
$nome = $_SESSION['nome'];
$tipoUsuarioAtual = $_SESSION['tipo'];

$sqlUser = "SELECT user_img FROM usuarios WHERE id = :id";
$stmtUser = $conexao->prepare($sqlUser);
$stmtUser->bindParam(':id', $id);
$stmtUser->execute();
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

$userImg = (!empty($userData['user_img'])) ? "../html/userImagens/" . $userData['user_img'] : "../img/userPicture.avif";

$tipoOposto = ($tipoUsuarioAtual === 'Irá ajudar') ? 'Precisa de ajuda' : 'Irá ajudar';

try {
    $sql = "SELECT 
                p.id_usuario AS id_usuario_post, 
                u.nome, 
                u.telefone, 
                u.cidade, 
                u.tipo, 
                u.user_img,
                p.texto_post, 
                p.imagem
            FROM publicacoes p
            INNER JOIN usuarios u ON p.id_usuario = u.id
            WHERE u.tipo = :tipoOposto
            ORDER BY p.id DESC";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':tipoOposto', $tipoOposto);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar posts: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempo Voluntário | Posts</title>
    <link rel="stylesheet" href="../css/pagInicial.css">
</head>
<body>

    <div class="navbar">
        <a href="pagInicial.php"><img src="../img/logo1.png" alt="Logo" class="logo"></a>

        <div class="navbar-loged">
            <a class="loginButton" href="perfil.php">
                <p><?php echo htmlspecialchars($nome); ?></p>
                <img class="user" src="<?php echo htmlspecialchars($userImg); ?>">
            </a>
            <div class="divider"></div>
            <a href="../script/logout.php" class="logoutButton">Sair</a>
        </div>

        <div class="navbar-center">
            <span class="titulo">Tempo Voluntário</span>
        </div>
    </div>
    <br><br>
    <div class="criar">
        <a href="post.php">
            <h3>Crie uma publicação!</h3>
        </a>
    </div>
    <br>
    <br>
    <?php if ($_SESSION['tipo'] === 'admin'): ?>
        <a href="http://localhost/TC_TEMPO_VOLUNTARIO/html/excluirConta.php" class="verPerfil">
            Ver Perfis
        </a>
    <?php endif; ?>

    <!-- Posts -->
    <?php foreach ($posts as $row): 
        $idUsuarioPost = $row['id_usuario_post'];
        $nomePost      = htmlspecialchars($row['nome']);
        $tipo          = ucfirst(htmlspecialchars($row['tipo']));
        $conteudo      = nl2br(htmlspecialchars($row['texto_post']));
        $imagem        = $row['imagem'] ? htmlspecialchars($row['imagem']) : null;
        $telefone      = preg_replace('/\D/', '', $row['telefone']);
        $cidade        = htmlspecialchars($row['cidade'] ?? '');

       $userPostImg = !empty($row['user_img']) ? "userImagens/" . htmlspecialchars($row['user_img']) : "userPicture.avif";

        $textoBotao    = ($tipoUsuarioAtual === 'Irá ajudar') ? 'Irei ajudar' : 'Preciso de ajuda';
        $linkWhatsapp  = "https://wa.me/55$telefone";
    ?>
        <div class="post-body">
            <div class="post">
                <div class="postHeader">
                    <img class="userPostimg" src="<?php echo $userPostImg; ?>">
                    <p><?php echo "$nomePost - $cidade"; ?> | <?php echo $tipo; ?></p>
                </div>

                <div class="post-content">
                    <p><?php echo $conteudo; ?></p>
                </div>

                <?php if (!empty($imagem)): ?>
                    <div class="post-image">
                        <img src="<?php echo htmlspecialchars($imagem); ?>" alt="Imagem do post" style="max-width: 300px;">
                    </div>
                <?php endif; ?>

                <br>
                <div class="post-solicitation">
                    <a href="<?php echo $linkWhatsapp; ?>" target="_blank">
                        <button><?php echo $textoBotao; ?></button>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</body>
</html>