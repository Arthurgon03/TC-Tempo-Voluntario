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

$tipoFiltro = ($tipoUsuarioAtual === 'Irá ajudar') ? ['Precisa de ajuda', 'Irá ajudar'] : ['Irá ajudar', 'Precisa de ajuda'];

// Paginação
$registrosPorPagina = 10;
$paginaAtual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $registrosPorPagina;

// Contar total de posts
$countSql = "SELECT COUNT(*) FROM publicacoes p
             INNER JOIN usuarios u ON p.id_usuario = u.id
             WHERE u.tipo IN (:tipo1, :tipo2)";
$countStmt = $conexao->prepare($countSql);
$countStmt->bindParam(':tipo1', $tipoFiltro[0]);
$countStmt->bindParam(':tipo2', $tipoFiltro[1]);
$countStmt->execute();
$totalPosts = $countStmt->fetchColumn();
$totalPaginas = ceil($totalPosts / $registrosPorPagina);

// Buscar posts paginados
try {
    $sql = "SELECT 
                p.id AS id_post,
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
            WHERE u.tipo IN (:tipo1, :tipo2)
            ORDER BY p.id DESC
            LIMIT :limit OFFSET :offset";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':tipo1', $tipoFiltro[0]);
    $stmt->bindParam(':tipo2', $tipoFiltro[1]);
    $stmt->bindValue(':limit', $registrosPorPagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
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
    <br><br>

    <?php if ($_SESSION['tipo'] === 'admin'): ?>
        <a href="http://localhost/TC_TEMPO_VOLUNTARIO/html/excluirConta.php" class="verPerfil">
            Ver Perfis
        </a>
    <?php endif; ?>

    <?php if (count($posts) === 0): ?>
        <div style="text-align: center; margin-top: 50px;">
            <p style="font-size: 18px;">Nenhuma publicação encontrada.</p>
            <p><a href="post.php">Seja o primeiro a publicar!</a></p>
        </div>
    <?php else: ?>
        <?php foreach ($posts as $row): 
            $idPost = $row['id_post'];
            $idUsuarioPost = $row['id_usuario_post'];
            $nomePost = htmlspecialchars($row['nome']);
            $tipo = ucfirst(htmlspecialchars($row['tipo']));
            $conteudo = nl2br(htmlspecialchars($row['texto_post']));
            $imagem = $row['imagem'] ? htmlspecialchars($row['imagem']) : null;
            $telefone = preg_replace('/\D/', '', $row['telefone']);
            $cidade = htmlspecialchars($row['cidade'] ?? '');
            $userPostImg = !empty($row['user_img']) ? "userImagens/" . htmlspecialchars($row['user_img']) : "userPicture.avif";
            $textoBotao = ($tipoUsuarioAtual === 'Irá ajudar') ? 'Irei ajudar' : 'Preciso de ajuda';
            $linkWhatsapp = "https://wa.me/55$telefone";
        ?>
        <div class="post-body">
            <div class="post">
                <div class="postHeader">
                    <img class="userPostimg" src="<?php echo $userPostImg; ?>">
                    <p><?php echo "$nomePost - $cidade"; ?> | <?php echo $tipo; ?></p>

                    <?php if ($idUsuarioPost == $_SESSION['id'] || $_SESSION['tipo'] === 'admin'): ?>
                        <div class="post-options">
                            <button class="menu-btn">⋮</button>
                            <div class="dropdown-menu">
                                <form method="POST" action="../script/excluir_post.php" onsubmit="return confirm('Tem certeza que deseja excluir este post?');">
                                    <input type="hidden" name="id_post" value="<?php echo $idPost; ?>">
                                    <button type="submit">Excluir</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="post-content">
                    <p><?php echo $conteudo; ?></p>
                </div>

                <?php if (!empty($imagem)): ?>
                    <div class="post-image">
                        <img src="<?php echo $imagem; ?>" alt="Imagem do post" style="max-width: 300px;">
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

        <!-- Paginação -->
        <div class="pagination" style="text-align: center; margin: 30px;">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?pagina=<?php echo $i; ?>"
                   style="margin: 0 5px; padding: 6px 12px; text-decoration: none;
                          background-color: <?= ($i == $paginaAtual) ? '#333' : '#ccc' ?>;
                          color: white; border-radius: 4px;">
                   <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <script>
        document.querySelectorAll('.menu-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const menu = btn.nextElementSibling;
                menu.classList.toggle('show');
            });
        });

        window.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        });
    </script>
</body>
</html>