<?php
session_start();
include '../includes/conexao.php';
include '../includes/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<p>Produto inválido.</p>";
    include '../includes/footer.php';
    exit;
}

$sql = "SELECT * FROM produtos WHERE id = $id LIMIT 1";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    echo "<p>Produto não encontrado.</p>";
    include '../includes/footer.php';
    exit;
}

$produto = $result->fetch_assoc();
?>

<h2><?= htmlspecialchars($produto['nome']) ?></h2>

<?php if (!empty($produto['imagem'])): ?>
    <img src="../uploads/<?= htmlspecialchars($produto['imagem']) ?>" 
         alt="<?= htmlspecialchars($produto['nome']) ?>" 
         style="width:300px;height:300px;object-fit:cover;">
<?php endif; ?>

<p><?= !empty($produto['descricao']) ? htmlspecialchars($produto['descricao']) : 'Sem descrição disponível.' ?></p>
<p>Preço: R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>

<?php if (isset($_SESSION['usuario_id'])): ?>

<form action="../carrinho/adicionar.php" method="get">
    <input type="hidden" name="id" value="<?= $produto['id'] ?>">
    <label>Quantidade:</label>
    <input type="number" name="quantidade" value="1" min="1" style="width:60px;">
    <button type="submit" class="btn comprar">Adicionar ao Carrinho</button>
</form>

<?php else: ?>

<p style="color:red; font-weight:bold; margin-top:10px;">
    Faça <a href="../admin/login.php">login</a> para comprar este produto.
</p>

<?php endif; ?>
<hr>

<?php
$sqlMedia = "
    SELECT AVG(nota) AS media, COUNT(*) AS total
    FROM avaliacao
    WHERE produto_id = $id
";
$resMedia = $conn->query($sqlMedia);
$dadosMedia = $resMedia->fetch_assoc();

$media = $dadosMedia['media'];
$total = $dadosMedia['total'];

if ($total > 0) {
    $estrelas = str_repeat('⭐', round($media)) . str_repeat('☆', 5 - round($media));
?>
    <h3>Média das Avaliações</h3>
    <p style="font-size:18px;">
        <?= $estrelas ?> 
        (<?= number_format($media, 1, ',', '.') ?> de 5 — <?= $total ?> avaliações)
    </p>
<?php
}
?>

<hr>

<h3>Avaliação</h3>

<?php if (isset($_SESSION['usuario_id'])): ?>

<form action="../avaliar/salvar.php" method="post">
    <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">

    <label>Nota:</label>
    <select name="nota" required>
        <option value="1">1 ⭐</option>
        <option value="2">2 ⭐⭐</option>
        <option value="3">3 ⭐⭐⭐</option>
        <option value="4">4 ⭐⭐⭐⭐</option>
        <option value="5">5 ⭐⭐⭐⭐⭐</option>
    </select>

    <button type="submit">Enviar Avaliação</button>
</form>

<?php else: ?>
    <p>Faça <a href="../admin/login.php">login</a> para avaliar.</p>
<?php endif; ?>

<hr>

<h3>Avaliações dos usuários</h3>

<?php
$sqlAval = "
    SELECT a.nota, a.data, u.nome 
    FROM avaliacao a
    JOIN usuarios u ON u.id = a.usuario_id
    WHERE a.produto_id = $id
    ORDER BY a.data DESC
";

$resAval = $conn->query($sqlAval);

if ($resAval->num_rows > 0):
    while ($a = $resAval->fetch_assoc()):
        $estrelasIndividuais = str_repeat('⭐', $a['nota']) . str_repeat('☆', 5 - $a['nota']);
?>
        <p>
            <strong><?= htmlspecialchars($a['nome']) ?></strong> — 
            <?= $estrelasIndividuais ?> 
            (<?= $a['nota'] ?>/5)
        </p>
<?php
    endwhile;
else:
    echo "<p>Nenhuma avaliação ainda.</p>";
endif;
?>

<hr>

<h3>Deixe seu comentário</h3>

<?php if (isset($_SESSION['usuario_id'])): ?>

<form action="../comentarios/salvar.php" method="post">
    <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">

    <textarea name="texto" rows="4" style="width:100%;" required></textarea>

    <button type="submit">Enviar Comentário</button>
</form>

<?php else: ?>
    <p>Faça <a href="../admin/login.php">login</a> para comentar.</p>
<?php endif; ?>

<hr>

<h3>Comentários</h3>

<?php
$sqlCom = "
    SELECT c.texto, c.data, u.nome
    FROM comentarios c
    JOIN usuarios u ON u.id = c.usuario_id
    WHERE c.produto_id = $id
    ORDER BY c.data DESC
";

$resCom = $conn->query($sqlCom);

if ($resCom->num_rows > 0):
    while ($c = $resCom->fetch_assoc()):
?>
        <p>
            <strong><?= htmlspecialchars($c['nome']) ?>:</strong> 
            <?= htmlspecialchars($c['texto']) ?>
        </p>
<?php
    endwhile;
else:
    echo "<p>Nenhum comentário ainda.</p>";
endif;
?>

<hr>

<a href="listar.php" class="btn">Voltar</a>

<?php
include '../includes/footer.php';
?>
