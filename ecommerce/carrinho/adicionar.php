<?php
include '../includes/conexao.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    
    $sql_check = "SELECT * FROM carrinho WHERE id_produto = $id LIMIT 1";
    $res = $conn->query($sql_check);

    if ($res && $res->num_rows > 0) {
        
        $conn->query("UPDATE carrinho SET quantidade = quantidade + 1 WHERE id_produto = $id");
    } else {
        
        $conn->query("INSERT INTO carrinho (id_produto, quantidade) VALUES ($id, 1)");
    }
}

header("Location: visualizar.php");
exit;
?>
