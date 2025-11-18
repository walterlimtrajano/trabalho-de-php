<?php
session_start();
include '../includes/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Você precisa estar logado para avaliar.");
}

$produto_id = intval($_POST['produto_id']);
$nota       = intval($_POST['nota']);
$usuario_id = intval($_SESSION['usuario_id']);

if ($nota < 1 || $nota > 5) {
    die("Nota inválida.");
}

$sql = "INSERT INTO avaliacao (produto_id, usuario_id, nota) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $produto_id, $usuario_id, $nota);
$stmt->execute();

header("Location: ../produtos/detalhes.php?id={$produto_id}");
exit;
