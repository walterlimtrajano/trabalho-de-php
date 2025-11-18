<?php
session_start();
include '../includes/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Você precisa estar logado para comentar.");
}

$produto_id = intval($_POST['produto_id']);
$texto      = trim($_POST['texto']);
$usuario_id = intval($_SESSION['usuario_id']);

if (empty($texto)) {
    die("O comentário não pode estar vazio.");
}

$sql = "INSERT INTO comentarios (produto_id, usuario_id, texto) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $produto_id, $usuario_id, $texto);
$stmt->execute();

header("Location: ../produtos/detalhes.php?id={$produto_id}");
exit;
