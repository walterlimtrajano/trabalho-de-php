document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('login-form');
  if (!form) {
    console.error('form #login-form não encontrado');
    return;
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const usuario = document.querySelector("input[name='usuario']").value.trim();
    const senha = document.querySelector("input[name='senha']").value.trim();

    const usuarioCorreto = "admin";
    const senhaCorreta = "1234";

    if (usuario === usuarioCorreto && senha === senhaCorreta) {
      window.location.href = "crud.html";
    } else {
      alert("Usuário ou senha incorretos!");
    }
  });
});
