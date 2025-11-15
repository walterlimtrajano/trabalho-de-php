<?php include 'includes/header.php'; ?>

<main class="container-pagina">
    <h2>Entrar ou Cadastrar</h2>

    <section class="auth-section">
        <form action="login_action.php" method="post" class="auth-form">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit" class="btn">Entrar</button>
        </form>

        <div class="register-suggestion">
            <p>Não tem conta? <a href="cadastrar.php">Crie uma aqui</a> (próximo passo)</p>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
