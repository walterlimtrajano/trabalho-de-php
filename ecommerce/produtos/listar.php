<?php
include '../includes/conexao.php';
include '../includes/header.php';

$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);
?>

<h2>Lista de Produtos</h2>

<?php if ($result->num_rows > 0): ?>
    <ul>
    <?php while($row = $result->fetch_assoc()): ?>
        <li>
            <?= htmlspecialchars($row['nome']) ?> - 
            R$ <?= number_format($row['preco'], 2, ',', '.') ?>
        </li>
    <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>Nenhum produto cadastrado.</p>
<?php endif; ?>

<?php
$conn->close();
include '../includes/footer.php';
?>
