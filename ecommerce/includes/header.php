<?php
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce</title>
    <link rel="stylesheet" href="/trabalho-de-php/ecommerce/assets/css/style.css">
</head>
<body>
    
<div id="alert-container"></div>

<header class="site-header">
    <div class="header-inner">

        <div class="brand">
            <h1>🛍️ E-commerce</h1>
        </div>

        <nav class="main-nav">
            <a href="/trabalho-de-php/ecommerce/index.php">Início</a>
            <a href="/trabalho-de-php/ecommerce/produtos/listar.php">Produtos</a>
            <a href="/trabalho-de-php/ecommerce/carrinho/visualizar.php">Carrinho</a>
        </nav>

        <nav class="user-nav">
            <?php if (isset($_SESSION['usuario'])): ?>
                <span>Olá, <?php echo $_SESSION['usuario']['nome']; ?></span>
                <a href="/trabalho-de-php/ecommerce/admin/logout.php">Sair</a>
            <?php else: ?>
                <a href="/trabalho-de-php/ecommerce/admin/login.php">Entrar</a>
                <a href="/trabalho-de-php/ecommerce/admin/cadastrar.php">Cadastrar</a>
            <?php endif; ?>
        </nav>

    </div>
    <hr>
</header>
