<?php
include '../includes/conexao.php';
include '../includes/header.php';

if (isset($_GET['remover'])) {
    $idRemover = intval($_GET['remover']);
    $conn->query("DELETE FROM carrinho WHERE id_produto = $idRemover");
}

if (isset($_GET['diminuir'])) {
    $idDiminuir = intval($_GET['diminuir']);
    $resQtd = $conn->query("SELECT quantidade FROM carrinho WHERE id_produto = $idDiminuir");
    if ($resQtd && $resQtd->num_rows > 0) {
        $qtd = $resQtd->fetch_assoc()['quantidade'];
        if ($qtd > 1) {
            $conn->query("UPDATE carrinho SET quantidade = quantidade - 1 WHERE id_produto = $idDiminuir");
        } else {
            $conn->query("DELETE FROM carrinho WHERE id_produto = $idDiminuir");
        }
    }
}

if (isset($_GET['aumentar'])) {
    $idAumentar = intval($_GET['aumentar']);
    $conn->query("UPDATE carrinho SET quantidade = quantidade + 1 WHERE id_produto = $idAumentar");
}

$sql = "SELECT c.id_produto, p.nome, p.preco, p.descricao, p.imagem, c.quantidade 
        FROM carrinho c
        JOIN produtos p ON c.id_produto = p.id
        ORDER BY c.id_produto DESC";

$result = $conn->query($sql);
$totalGeral = 0;
?>

<h2>🛒 Seu Carrinho</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <div class="lista-produtos">
        <?php while ($row = $result->fetch_assoc()):
            $subtotal = $row['preco'] * $row['quantidade'];
            $totalGeral += $subtotal;
        ?>
            <div class="produto-card">

                <?php if (!empty($row['imagem'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($row['imagem']) ?>" alt="<?= htmlspecialchars($row['nome']) ?>">
                <?php else: ?>
                    <img src="../assets/img/placeholder.png" alt="Sem imagem">
                <?php endif; ?>

                <h3><?= htmlspecialchars($row['nome']) ?></h3>
                <p>Preço: R$ <?= number_format($row['preco'], 2, ',', '.') ?></p>

                <p><strong>Quantidade:</strong></p>

                <div style="display:flex; gap:10px; align-items:center;">
                    <a class="btn btn-sm btn-secondary" href="?diminuir=<?= $row['id_produto'] ?>">–</a>
                    <span style="font-size:18px;"><?= $row['quantidade'] ?></span>
                    <a class="btn btn-sm btn-secondary" href="?aumentar=<?= $row['id_produto'] ?>">+</a>
                </div>

                <p style="margin-top:10px;">Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></p>

                <a class="btn btn-danger" href="?remover=<?= $row['id_produto'] ?>">Remover</a>

            </div>
        <?php endwhile; ?>
    </div>

    <h2>Total Geral: R$ <?= number_format($totalGeral, 2, ',', '.') ?></h2>

    <?php
    $juros = 0.02;
    ?>

 <h3>Parcelamento:</h3>

<form method="get">
    <input type="hidden" name="cep" value="<?= isset($_GET['cep']) ? $_GET['cep'] : '' ?>">
    <label>Escolha o número de parcelas:</label>
    <select name="parcelas" onchange="this.form.submit()">
        <?php for ($i = 1; $i <= 12; $i++): ?>
            <option value="<?= $i ?>" 
                <?= isset($_GET['parcelas']) && $_GET['parcelas'] == $i ? 'selected' : '' ?>>
                <?= $i ?>x
            </option>
        <?php endfor; ?>
    </select>
</form>

<?php
$parcelas = isset($_GET['parcelas']) ? intval($_GET['parcelas']) : 1;
$juros = 0.02;

if ($parcelas <= 6) {
    $valorParcela = $totalGeral / $parcelas;
    $totalParcelado = $totalGeral;
    $info = "(sem juros)";
} else {
    $totalParcelado = $totalGeral * pow(1 + $juros, $parcelas);
    $valorParcela = $totalParcelado / $parcelas;
    $info = "(com juros)";
}
?>

<h4><?= $parcelas ?>x de R$ <?= number_format($valorParcela, 2, ',', '.') ?> <?= $info ?></h4>
<h4>Total parcelado: R$ <?= number_format($totalParcelado, 2, ',', '.') ?></h4>

    <?php if ($totalGeral >= 150): ?>
        <h3 style="color: green;">Frete: GRÁTIS 🎉</h3>
        <h3>Total Final: R$ <?= number_format($totalGeral, 2, ',', '.') ?></h3>
    <?php else: ?>

        <?php
        $frete = null;
        if (isset($_GET['cep'])) {
            $cep = preg_replace('/[^0-9]/', '', $_GET['cep']);
            if (strlen($cep) == 8) {
                $cepBase = 58000000;
                $distancia = abs($cepBase - intval($cep));
                $frete = 10 + ($distancia / 100000);
                if ($frete < 12) $frete = 12;
            }
        }
        ?>

        <form method="get" style="margin-top: 20px;">
            <label>Digite seu CEP:</label>
            <input type="text" name="cep" placeholder="00000-000" required>
            <button class="btn">Calcular Frete</button>
        </form>

        <?php if ($frete !== null): ?>
            <h3>Frete: R$ <?= number_format($frete, 2, ',', '.') ?></h3>
            <h3>Total com Frete: R$ <?= number_format($totalGeral + $frete, 2, ',', '.') ?></h3>
        <?php endif; ?>

    <?php endif; ?>

    <a href="../produtos/listar.php" class="btn">Continuar Comprando</a>

<?php else: ?>

    <p>Seu carrinho está vazio.</p>
    <a href="../produtos/listar.php" class="btn">Voltar aos Produtos</a>

<?php endif; ?>

<?php
$conn->close();
include '../includes/footer.php';
?>
