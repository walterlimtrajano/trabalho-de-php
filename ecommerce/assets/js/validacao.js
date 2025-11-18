function validarCadastro() {
    const nome = document.getElementById("nome");
    const email = document.getElementById("email");
    const senha = document.getElementById("senha");
    const confirmar = document.getElementById("confirmar");

    if (
        nome.value.trim() === "" ||
        email.value.trim() === "" ||
        senha.value.trim() === "" ||
        confirmar.value.trim() === ""
    ) {
        alert("Preencha todos os campos!");
        return false;
    }

    if (!email.value.includes("@")) {
        alert("Digite um e-mail válido.");
        return false;
    }

    if (senha.value.length < 6) {
        alert("A senha deve ter pelo menos 6 caracteres.");
        return false;
    }

    if (senha.value !== confirmar.value) {
        alert("As senhas não coincidem.");
        return false;
    }

    return true;
}

function validarLogin() {
    const email = document.getElementById("email");
    const senha = document.getElementById("senha");

    if (email.value.trim() === "" || senha.value.trim() === "") {
        alert("Preencha todos os campos!");
        return false;
    }

    if (!email.value.includes("@")) {
        alert("Digite um e-mail válido.");
        return false;
    }

    return true;
}

function showAlert(message, type = "success") {
    const container = document.getElementById("alert-container");

    const alert = document.createElement("div");
    alert.classList.add("alert-box");

    if (type === "error") {
        alert.classList.add("alert-error");
    }

    alert.textContent = message;
    container.appendChild(alert);

    setTimeout(() => {
        alert.style.animation = "fadeOut 0.4s forwards";
        setTimeout(() => alert.remove(), 400);
    }, 3000);
}
