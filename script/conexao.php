<?php
$host = 'localhost';
$port = '5433';
$dbname = 'bd-tempo-volun';
$user = 'postgres';
$password = 'postgres';

try {
    $conexao = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}
?>