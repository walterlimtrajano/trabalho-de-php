<?php
include '../includes/conexao.php';
include '../includes/header.php';

/* ------------------------------
   REMOVER PRODUTO
--------------------------------*/
if (isset($_GET['remover'])) {
    $idRemover = intval($_GET['remover']);
    $conn->query("DELETE FROM carrinho WHERE id_produto = $idRemover");
}

/* ------------------------------
   DIMINUIR QUANTIDADE
--------------------------------*/
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

/* ------------------------------
   AUMENTAR QUANTIDADE
--------------------------------*/
if (isset($_GET['aumentar'])) {
    $idAumentar = intval($_GET['aumentar']);
    $conn->query("UPDATE carrinho SET quantidade = quantidade + 1 WHERE id_produto = $idAumentar");
}

/* ------------------------------
   BUSCAR PRODUTOS DO CARRINHO
--------------------------------*/
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
/* ------------------------------
   PARCELAMENTO
--------------------------------*/
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

<h3>Parcelamento:</h3>

<form method="get">
    <label>Escolha o número de parcelas:</label>
    <select name="parcelas" onchange="this.form.submit()">
        <?php for ($i = 1; $i <= 12; $i++): ?>
            <option value="<?= $i ?>" <?= $parcelas == $i ? 'selected' : '' ?>><?= $i ?>x</option>
        <?php endfor; ?>
    </select>
</form>

<h4><?= $parcelas ?>x de R$ <?= number_format($valorParcela, 2, ',', '.') ?> <?= $info ?></h4>
<h4>Total parcelado: R$ <?= number_format($totalParcelado, 2, ',', '.') ?></h4>

<?php
/* ------------------------------
   FRETE
--------------------------------*/
if ($totalGeral >= 150): ?>
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

<hr>

<!-- ============================================================
     SISTEMA DE PAGAMENTO (PIX + CARTÃO)
=============================================================== -->

<h2>🧾 Escolha a Forma de Pagamento</h2>

<div style="display:flex; gap:20px; margin:20px 0;">
    <a href="?pagamento=pix" class="btn btn-success">Pagar com PIX</a>
    <a href="?pagamento=cartao" class="btn btn-primary">Cartão de Crédito</a>
</div>

<?php if (isset($_GET['pagamento']) && $_GET['pagamento'] == "pix"): ?>

    <h3>Pagamento via PIX</h3>

    <?php 
    $codigoPix = "00020101021126580014BR.GOV.BCB.PIX0136chave-pix-vendedor@empresa.com.br5204000053039865802BR5920Loja Exemplo LTDA6010SAO PAULO62290525CAR-" . rand(100000,999999) . "6304";
    ?>

    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=<?= urlencode($codigoPix) ?>">

    <textarea style="width:100%; height:100px; margin-top:10px;"><?= $codigoPix ?></textarea>

    <form method="post">
        <button name="simular_pix" class="btn btn-success">Simular Pagamento PIX</button>
    </form>

    <?php if (isset($_POST['simular_pix'])): ?>
        <h3 style="color:green;">✔ Pagamento Aprovado com Sucesso!</h3>
    <?php endif; ?>

<?php endif; ?>


<?php if (isset($_GET['pagamento']) && $_GET['pagamento'] == "cartao"): ?>

    <h3>Pagamento com Cartão de Crédito</h3>

    <form method="post" style="display:flex; flex-direction:column; gap:10px; max-width:400px;">

        <label>Número do Cartão</label>
        <input type="text" name="numero" maxlength="16" required>

        <label>Nome impresso no Cartão</label>
        <input type="text" name="nome" required>

        <label>Validade (MM/AA)</label>
        <input type="text" name="validade" maxlength="5" required>

        <label>CVV</label>
        <input type="password" name="cvv" maxlength="3" required>

        <label>Parcelas</label>
        <select name="parcelas">
            <?php for ($i=1;$i<=12;$i++): ?>
                <option value="<?= $i ?>"><?= $i ?>x</option>
            <?php endfor; ?>
        </select>

        <button class="btn btn-primary" name="pagar_cartao">Finalizar Pagamento</button>
    </form>

    <?php if (isset($_POST['pagar_cartao'])): ?>

        <?php
        $parcelas = intval($_POST['parcelas']);

        if ($parcelas <= 6) {
            $valorParcela = $totalGeral / $parcelas;
            $totalFinal = $totalGeral;
            $info = "(sem juros)";
        } else {
            $totalFinal = $totalGeral * pow(1 + 0.02, $parcelas);
            $valorParcela = $totalFinal / $parcelas;
            $info = "(com juros)";
        }
        ?>

        <h3 style="color:green;">✔ Pagamento Aprovado!</h3>
        <p><?= $parcelas ?>x de R$ <?= number_format($valorParcela, 2, ',', '.') ?> <?= $info ?></p>
        <p>Total pago: <strong>R$ <?= number_format($totalFinal, 2, ',', '.') ?></strong></p>

    <?php endif; ?>

<?php endif; ?>

<?php
$conn->close();
include '../includes/footer.php';
?>
