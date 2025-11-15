<?php
include '../includes/conexao.php';
include '../includes/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM produtos WHERE id = $id LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0):
    $produto = $result->fetch_assoc();
?>
<h2><?= htmlspecialchars($produto['nome']) ?></h2>

<?php if (!empty($produto['imagem'])): ?>
    <img src="../uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" style="width:300px;height:300px;object-fit:cover;">
<?php endif; ?>

<p><?= !empty($produto['descricao']) ? htmlspecialchars($produto['descricao']) : 'Sem descrição disponível.' ?></p>
<p>Preço unitário: R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>

<form action="../carrinho/adicionar.php" method="get">
    <input type="hidden" name="id" value="<?= $produto['id'] ?>">
    <label for="quantidade">Quantidade:</label>
    <input type="number" name="quantidade" id="quantidade" value="1" min="1" style="width:50px;">
    <button type="submit" class="btn comprar">Adicionar ao Carrinho</button>
</form>

<a href="listar.php" class="btn">Voltar</a>

<?php
else:
    echo "<p>Produto não encontrado.</p>";
endif;

$conn->close();
include '../includes/footer.php';
?>
