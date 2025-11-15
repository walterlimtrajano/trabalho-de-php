<?php
include '../includes/conexao.php';
include '../includes/header.php';

if (isset($_GET['remover'])) {
    $idRemover = intval($_GET['remover']);
    $conn->query("DELETE FROM carrinho WHERE id_produto = $idRemover");
}

$sql = "SELECT c.id_produto, p.nome, p.preco, p.imagem, c.quantidade 
        FROM carrinho c
        JOIN produtos p ON c.id_produto = p.id
        ORDER BY c.id_produto DESC";
$result = $conn->query($sql);
?>

<h2>🛒 Seu Carrinho</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <div class="lista-produtos">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="produto-card">
                <?php if (!empty($row['imagem'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($row['imagem']) ?>" alt="<?= htmlspecialchars($row['nome']) ?>">
                <?php else: ?>
                    <img src="../assets/img/placeholder.png" alt="Sem imagem">
                <?php endif; ?>
                
                <h3><?= htmlspecialchars($row['nome']) ?></h3>
                <p class="preco">
                    R$ <?= number_format($row['preco'], 2, ',', '.') ?>
                </p>
                <p>Quantidade: <?= intval($row['quantidade']) ?></p>
                
                <div class="acoes">
                    <a href="visualizar.php?remover=<?= $row['id_produto'] ?>" class="btn">Remover</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <a href="../produtos/listar.php" class="btn">Continuar Comprando</a>

<?php else: ?>
    <p>Seu carrinho está vazio.</p>
    <a href="../produtos/listar.php" class="btn">Voltar aos Produtos</a>
<?php endif; ?>

<?php
$conn->close();
include '../includes/footer.php';
?>
