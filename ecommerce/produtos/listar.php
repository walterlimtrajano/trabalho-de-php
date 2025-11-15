<?php
include '../includes/conexao.php';
include '../includes/header.php';

$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : '';

$sql = "SELECT * FROM produtos WHERE 1=1";

if (!empty($buscar)) {
    $buscarEscapado = $conn->real_escape_string($buscar);
    $sql .= " AND nome LIKE '%$buscarEscapado%'";
}

switch ($ordem) {
    case "preco_asc":
        $sql .= " ORDER BY preco ASC";
        break;
    case "preco_desc":
        $sql .= " ORDER BY preco DESC";
        break;
    case "nome_asc":
        $sql .= " ORDER BY nome ASC";
        break;
    case "nome_desc":
        $sql .= " ORDER BY nome DESC";
        break;
    default:
        $sql .= " ORDER BY id DESC";
        break;
}

$result = $conn->query($sql);
$total = $result->num_rows;
?>

<div class="container-pagina">

<h2>Lista de Produtos (<?= $total ?> encontrados)</h2>

<p style="font-size: 16px; color: #4a90e2; margin-top: -10px;">
    🌟 Explore nossas promoções e produtos mais procurados!
</p>

<div class="filtros" style="margin: 20px 0; display: flex; gap: 10px; flex-wrap: wrap;">
    <form method="get" style="display: flex; gap: 10px; flex-wrap: wrap;">
        <input 
            type="text" 
            name="buscar" 
            value="<?= htmlspecialchars($buscar) ?>"
            placeholder="Buscar produto..." 
            style="padding: 8px; border-radius: 8px; border: 1px solid #bbb;"
        >

        <select name="ordem" style="padding: 8px; border-radius: 8px; border: 1px solid #bbb;">
            <option value="">Ordenar por</option>
            <option value="preco_asc"  <?= $ordem == 'preco_asc'  ? 'selected' : '' ?>>Preço menor → maior</option>
            <option value="preco_desc" <?= $ordem == 'preco_desc' ? 'selected' : '' ?>>Preço maior → menor</option>
            <option value="nome_asc"   <?= $ordem == 'nome_asc'   ? 'selected' : '' ?>>Nome A → Z</option>
            <option value="nome_desc"  <?= $ordem == 'nome_desc'  ? 'selected' : '' ?>>Nome Z → A</option>
        </select>

        <button class="btn">Filtrar</button>
    </form>
</div>

<?php if ($total > 0): ?>
    <div class="lista-produtos">

    <?php while($row = $result->fetch_assoc()): ?>
        <div class="produto-card">
            <?php if (!empty($row['imagem'])): ?>
                <img src="../uploads/<?= htmlspecialchars($row['imagem']) ?>" alt="<?= htmlspecialchars($row['nome']) ?>">
            <?php else: ?>
                <img src="../assets/img/placeholder.png" alt="Sem imagem">
            <?php endif; ?>

            <h3><?= htmlspecialchars($row['nome']) ?></h3>

            <?php if (!empty($row['descricao'])): ?>
                <p class="descricao"><?= htmlspecialchars($row['descricao']) ?></p>
            <?php endif; ?>

            <p class="preco">
                R$ <?= number_format($row['preco'], 2, ',', '.') ?>
            </p>

            <div class="acoes">
                <a href="detalhes.php?id=<?= $row['id'] ?>" class="btn">Ver detalhes</a>
                <a href="../carrinho/adicionar.php?id=<?= $row['id'] ?>" class="btn comprar">Comprar</a>
            </div>
        </div>
    <?php endwhile; ?>

    </div>
<?php else: ?>
    <p>Nenhum produto encontrado com os filtros selecionados.</p>
<?php endif; ?>

</div> <!-- fim container -->

<?php
$conn->close();
include '../includes/footer.php';
?>
