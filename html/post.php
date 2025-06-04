<?php
session_start();
require_once '../script/conexao.php';

// Verifica se o usuário está logado
$idUsuarioAtual = $_SESSION['id'] ?? null;
if (!$idUsuarioAtual) {
    die("Usuário não autenticado.");
}

// Obtém o tipo do usuário atual
$stmtTipo = $conexao->prepare("SELECT tipo FROM usuarios WHERE id = :id");
$stmtTipo->execute([':id' => $idUsuarioAtual]);
$tipoUsuarioAtual = $stmtTipo->fetchColumn();

if (!$tipoUsuarioAtual) {
    die("Tipo de usuário não encontrado.");
}

// Define o tipo oposto
$tipoOposto = ($tipoUsuarioAtual === 'voluntário') ? 'necessitado' : 'voluntário';

// Filtro por cidade (opcional)
$cidadeFiltro = $_GET['cidade'] ?? '';

// Monta SQL base
$sql = "
    SELECT 
        p.texto_post, 
        p.imagem, 
        u.id AS id_usuario, 
        u.nome, 
        u.tipo, 
        u.telefone, 
        u.cidade
    FROM publicacoes AS p
    JOIN usuarios AS u ON u.id = p.id_usuario
    WHERE u.tipo = :tipoOposto
";

$params = [':tipoOposto' => $tipoOposto];

// Adiciona filtro de cidade se informado
if (!empty($cidadeFiltro)) {
    $sql .= " AND LOWER(u.cidade) LIKE LOWER(:cidade)";
    $params[':cidade'] = "%$cidadeFiltro%";
}

$sql .= " ORDER BY p.id DESC";

$stmtPosts = $conexao->prepare($sql);
$stmtPosts->execute($params);
$posts = $stmtPosts->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempo Voluntário | Criacão de Post</title>
    <link rel="stylesheet" href="../css/post.css">
</head>
<body>
    <div class="container">
        <h2>Criar Publicação</h2>
        <form action="../script/processar_posts.php" method="POST" enctype="multipart/form-data">
            <div class="conteudo">
                <textarea name="texto_post" placeholder="Digite seu texto" required></textarea>
                <br>
                <input type="file" name="imagem">
                <br>
                <button type="submit">Publicar</button>  
            </div>
        </form>
    </div>
</body>
</html>