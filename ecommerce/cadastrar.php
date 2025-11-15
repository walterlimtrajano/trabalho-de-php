<?php include 'includes/header.php'; ?>

<main class="container-pagina">
    <h2>📝 Criar conta</h2>

    <section class="auth-section">
        <form class="auth-form" method="POST">

            <label>Nome completo</label>
            <input type="text" name="nome" required>

            <label>E-mail</label>
            <input type="email" name="email" required>

            <label>Senha</label>
            <input type="password" name="senha" required>

            <button class="btn" type="submit">Cadastrar</button>
        </form>

        <p class="register-suggestion">
            Já tem conta? <a href="login.php">Entrar</a>
        </p>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            echo "<p style='text-align:center; margin-top:10px;'><b>🎉 Cadastro enviado com sucesso!</b></p>";
        }
        ?>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
