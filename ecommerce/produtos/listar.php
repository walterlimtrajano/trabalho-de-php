<?php
include '../includes/conexao.php';
include '../includes/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$buscar  = $_GET['buscar'] ?? '';
$ordenar = $_GET['ordem']  ?? '';

$sql = "
    SELECT 
        p.*, 
        COALESCE(AVG(a.nota), 0) AS media_avaliacao
    FROM produtos p
    LEFT JOIN avaliacao a ON a.produto_id = p.id
    WHERE 1=1
";

if (!empty($buscar)) {
    $b = $conn->real_escape_string($buscar);
    $sql .= " AND p.nome LIKE '%$b%'";
}

$sql .= " GROUP BY p.id ";

switch ($ordenar) {
    case "preco_asc":
        $sql .= " ORDER BY p.preco ASC"; break;
    case "preco_desc":
        $sql .= " ORDER BY p.preco DESC"; break;
    case "nome_asc":
        $sql .= " ORDER BY p.nome ASC"; break;
    case "nome_desc":
        $sql .= " ORDER BY p.nome DESC"; break;
    case "avaliacao_desc":
        $sql .= " ORDER BY media_avaliacao DESC"; break;
    case "avaliacao_asc":
        $sql .= " ORDER BY media_avaliacao ASC"; break;
    default:
        $sql .= " ORDER BY p.id DESC";
}

$result = $conn->query($sql);
$total  = $result->num_rows;
?>

<div class="container-pagina">

<h2>Lista de Produtos (<?= $total ?> encontrados)</h2>

<form class="barra-filtro" method="get">

    <input 
        type="text" 
        name="buscar"
        placeholder="Buscar produto..."
        value="<?= htmlspecialchars($buscar) ?>"
    >

    <select name="ordem">
        <option value="">Ordenar por</option>
        <option value="preco_asc"        <?= $ordenar=='preco_asc' ? 'selected' : '' ?>>Preço ↑</option>
        <option value="preco_desc"       <?= $ordenar=='preco_desc' ? 'selected' : '' ?>>Preço ↓</option>
        <option value="nome_asc"         <?= $ordenar=='nome_asc' ? 'selected' : '' ?>>Nome A-Z</option>
        <option value="nome_desc"        <?= $ordenar=='nome_desc' ? 'selected' : '' ?>>Nome Z-A</option>
        <option value="avaliacao_desc"   <?= $ordenar=='avaliacao_desc' ? 'selected' : '' ?>>Melhor avaliados</option>
        <option value="avaliacao_asc"    <?= $ordenar=='avaliacao_asc' ? 'selected' : '' ?>>Pior avaliados</option>
    </select>

    <button type="submit">Filtrar</button>
</form>

<div class="lista-produtos">
<?php while($row = $result->fetch_assoc()): ?>
    <div class="produto-card">

        <img src="../assets/img/<?= $row['imagem'] ?>" alt="<?= $row['nome'] ?>">

        <h3><?= htmlspecialchars($row['nome']) ?></h3>

        <p style="color:#f5a623; font-size:14px;">
            ⭐ <?= number_format($row['media_avaliacao'], 1, ',', '.') ?> / 5
        </p>

        <p class="preco">R$ <?= number_format($row['preco'], 2, ',', '.') ?></p>

        <div class="acoes">
            <a href="detalhes.php?id=<?= $row['id'] ?>" class="btn">Ver detalhes</a>

            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="../carrinho/adicionar.php?id=<?= $row['id'] ?>" class="btn comprar">Comprar</a>
            <?php else: ?>
                <a href="../admin/login.php" class="btn comprar" style="background:#ccc;">Fazer login</a>
            <?php endif; ?>
        </div>

    </div>
<?php endwhile; ?>
</div>

</div>

<?php 
$conn->close();
include '../includes/footer.php';
?>
