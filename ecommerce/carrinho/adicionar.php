<?php
include '../includes/conexao.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$quantidade = isset($_GET['quantidade']) ? intval($_GET['quantidade']) : 1;

if ($id > 0 && $quantidade > 0) {
    $sql_check = "SELECT * FROM carrinho WHERE id_produto = $id LIMIT 1";
    $res = $conn->query($sql_check);

    if ($res && $res->num_rows > 0) {
        $conn->query("UPDATE carrinho SET quantidade = quantidade + $quantidade WHERE id_produto = $id");
    } else {
        $conn->query("INSERT INTO carrinho (id_produto, quantidade) VALUES ($id, $quantidade)");
    }
}

header("Location: visualizar.php");
exit;
?>
