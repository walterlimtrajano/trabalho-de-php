<?php
session_start();
include '../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $query = $conn->query("SELECT * FROM usuarios WHERE email='$email' LIMIT 1");

    if ($query->num_rows > 0) {
        $user = $query->fetch_assoc();

        if (password_verify($senha, $user['senha'])) {
            $_SESSION['usuario'] = [
                'id' => $user['id'],
                'nome' => $user['nome'],
                'email' => $user['email']
            ];
            header("Location: ../index.php");
            exit;
        } else {
            $erro = "Senha incorreta";
        }
    } else {
        $erro = "Usuário não encontrado";
    }
}
?>

<?php include '../includes/header.php'; ?>

<main class="container-pagina">
    <h2>Entrar</h2>

    <form method="POST" class="auth-form">
        <label>E-mail</label>
        <input type="email" name="email" required>

        <label>Senha</label>
        <input type="password" name="senha" required>

        <button class="btn" type="submit">Entrar</button>

        <?php if (!empty($erro)) echo "<p>$erro</p>"; ?>
    </form>

    <p>Não tem conta? <a href="cadastrar.php">Criar conta</a></p>
</main>

<?php include '../includes/footer.php'; ?>
