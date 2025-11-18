<?php
session_start();
include '../includes/conexao.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) $_SESSION['user_id'] = 0;
$userIdAtual = $_SESSION['user_id'];

if (!isset($_SESSION['ultimo_user'])) {
    $_SESSION['ultimo_user'] = $userIdAtual;
} elseif ($_SESSION['ultimo_user'] != $userIdAtual) {
    $conn->query("DELETE FROM carrinho WHERE user_id = {$_SESSION['ultimo_user']}");
    $_SESSION['ultimo_user'] = $userIdAtual;
}

if (isset($_GET['remover'])) {
    $idRemover = intval($_GET['remover']);
    $conn->query("DELETE FROM carrinho WHERE id_produto = $idRemover AND user_id = $userIdAtual");
}

if (isset($_GET['diminuir'])) {
    $id = intval($_GET['diminuir']);
    $res = $conn->query("SELECT quantidade FROM carrinho WHERE id_produto = $id AND user_id = $userIdAtual");
    if ($res && $res->num_rows > 0) {
        $qtd = $res->fetch_assoc()['quantidade'];
        if ($qtd > 1) {
            $conn->query("UPDATE carrinho SET quantidade = quantidade - 1 WHERE id_produto = $id AND user_id = $userIdAtual");
        } else {
            $conn->query("DELETE FROM carrinho WHERE id_produto = $id AND user_id = $userIdAtual");
        }
    }
}

if (isset($_GET['aumentar'])) {
    $idAumentar = intval($_GET['aumentar']);
    $conn->query("UPDATE carrinho SET quantidade = quantidade + 1 WHERE id_produto = $idAumentar AND user_id = $userIdAtual");
}

$sql = "
    SELECT c.id_produto, p.nome, p.preco, p.descricao, p.imagem, c.quantidade 
    FROM carrinho c
    JOIN produtos p ON c.id_produto = p.id
    WHERE c.user_id = $userIdAtual
    ORDER BY c.id_produto DESC
";

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
                <img src="../assets/img/<?= htmlspecialchars($row['imagem']) ?>" alt="">
            <?php else: ?>
                <img src="../assets/img/placeholder.png" alt="">
            <?php endif; ?>
            <h3><?= htmlspecialchars($row['nome']) ?></h3>
            <p>Preço: R$ <?= number_format($row['preco'], 2, ',', '.') ?></p>
            <div style="display:flex; gap:10px;">
                <a class="btn btn-sm btn-secondary" href="?diminuir=<?= $row['id_produto'] ?>">–</a>
                <span><?= $row['quantidade'] ?></span>
                <a class="btn btn-sm btn-secondary" href="?aumentar=<?= $row['id_produto'] ?>">+</a>
            </div>
            <p>Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
            <a class="btn btn-danger" href="?remover=<?= $row['id_produto'] ?>">Remover</a>
        </div>
        <?php endwhile; ?>
    </div>
    <h2>Total Geral: R$ <?= number_format($totalGeral, 2, ',', '.') ?></h2>

    <?php
    $frete = 15;
    if ($totalGeral > 150) $frete = 0;
    $totalComFrete = $totalGeral + $frete;
    ?>
    <h3>Frete: <?= $frete == 0 ? "Grátis 🎉" : "R$ " . number_format($frete, 2, ',', '.') ?></h3>
    <h3>Total com Frete: R$ <?= number_format($totalComFrete, 2, ',', '.') ?></h3>

<?php else: ?>
    <p>Seu carrinho está vazio.</p>
<?php endif; ?>

<hr>

<?php if ($totalGeral > 0): ?>
    <h2>🧾 Escolha a Forma de Pagamento</h2>
    <div style="display:flex; gap:20px; margin:20px 0;">
        <a href="?pagamento=pix" class="btn btn-success">Pix</a>
        <a href="?pagamento=credito" class="btn btn-primary">Crédito</a>
        <a href="?pagamento=debito" class="btn btn-secondary">Débito</a>
        <a href="?pagamento=boleto" class="btn btn-dark">Boleto</a>
    </div>

    <?php $pagamento = $_GET['pagamento'] ?? null; ?>

    <?php if ($pagamento === "pix"): ?>
        <h3>Pagamento via PIX</h3>
        <?php $codigoPix = "000201PIX-" . rand(100000,999999); ?>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?= urlencode($codigoPix) ?>">
        <textarea style="width:100%;height:80px;"><?= $codigoPix ?></textarea>
    <?php endif; ?>

    <?php if ($pagamento === "debito"): ?>
        <h3>Pagamento no Débito</h3>
        <form method="post">
            <input type="text" name="num" placeholder="Número" required>
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="text" name="validade" placeholder="MM/AA" required>
            <input type="password" name="cvv" placeholder="CVV" required>
            <button class="btn btn-secondary" name="pagar_debito">Pagar</button>
        </form>
        <?php if (isset($_POST['pagar_debito'])) echo "<h3 style='color:green;'>✔ Aprovado!</h3>"; ?>
    <?php endif; ?>

    <?php if ($pagamento === "boleto"): ?>
        <h3>Boleto Bancário</h3>
        <?php $boleto = "23793." . rand(10000,99999) . ".123456"; ?>
        <textarea style="width:100%;height:80px;"><?= $boleto ?></textarea>
    <?php endif; ?>

    <?php if ($pagamento === "credito"): ?>
        <h3>Cartão de Crédito</h3>
        <form method="post">
            <input type="text" name="numero" placeholder="Número" required>
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="text" name="validade" placeholder="MM/AA" required>
            <input type="password" name="cvv" placeholder="CVV" required>
            <label>Parcelas:</label>
            <select name="parcelas">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?>x <?= $i <= 6 ? "sem juros" : "com juros" ?></option>
                <?php endfor; ?>
            </select>
            <button class="btn btn-primary" name="pagar_credito">Pagar</button>
        </form>
        <?php if (isset($_POST['pagar_credito'])): ?>
            <?php
            $parc = intval($_POST['parcelas']);
            $totalFinal = $parc <= 6 ? $totalComFrete : $totalComFrete * pow(1.02, $parc);
            $valorParcela = $totalFinal / $parc;
            ?>
            <h3 style="color:green;">✔ Pagamento aprovado!</h3>
            <p><?= $parc ?>x de R$ <?= number_format($valorParcela, 2, ',', '.') ?></p>
            <p>Total: R$ <?= number_format($totalFinal, 2, ',', '.') ?></p>
        <?php endif; ?>
    <?php endif; ?>

<?php endif; ?>

<?php
$conn->close();
include '../includes/footer.php';
?>
