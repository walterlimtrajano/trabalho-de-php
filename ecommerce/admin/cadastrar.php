<?php
session_start();
include '../includes/conexao.php';
include '../includes/header.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome  = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT id FROM usuarios WHERE email = '$email'");

    if ($check->num_rows > 0) {
        echo "<p style='text-align:center; color:red;'><b>Este e-mail já está cadastrado.</b></p>";
    } else {

        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
        $conn->query($sql);

        $user = $conn->query("SELECT * FROM usuarios WHERE email = '$email' LIMIT 1")->fetch_assoc();

        $_SESSION['usuario'] = [
            "id" => $user['id'],
            "nome" => $user['nome'],
            "email" => $user['email']
        ];

        header("Location: ../index.php");
        exit;
    }
}
?>

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
    </section>
</main>

<?php include '../includes/footer.php'; ?>
