document.addEventListener("DOMContentLoaded", () => {
    carregarProdutos();
});

const form = document.getElementById("form-produto");
const lista = document.getElementById("lista-produtos");

let produtoEditando = null;

function carregarProdutos() {
    const produtos = JSON.parse(localStorage.getItem("produtos")) || [];
    lista.innerHTML = "";

    produtos.forEach(produto => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>${produto.id}</td>
            <td>${produto.nome}</td>
            <td>R$ ${produto.preco.toFixed(2)}</td>
            <td>${produto.categoria}</td>
            <td>
                <button class="acao-btn btn-editar" data-id="${produto.id}">Editar</button>
                <button class="acao-btn btn-excluir" data-id="${produto.id}">Excluir</button>
            </td>
        `;

        lista.appendChild(tr);
    });

    ativarBotoes();
}

function ativarBotoes() {
    document.querySelectorAll(".btn-editar").forEach(btn => {
        btn.addEventListener("click", () => {
            editarProduto(btn.dataset.id);
        });
    });

    document.querySelectorAll(".btn-excluir").forEach(btn => {
        btn.addEventListener("click", () => {
            excluirProduto(btn.dataset.id);
        });
    });
}

form.addEventListener("submit", e => {
    e.preventDefault();

    const nome = document.getElementById("nome").value;
    const preco = parseFloat(document.getElementById("preco").value);
    const categoria = document.getElementById("categoria").value;

    if (!nome || !preco || !categoria) {
        alert("Preencha todos os campos!");
        return;
    }

    const produtos = JSON.parse(localStorage.getItem("produtos")) || [];

    if (produtoEditando) {
        const index = produtos.findIndex(p => p.id == produtoEditando);

        produtos[index] = {
            id: Number(produtoEditando),
            nome,
            preco,
            categoria
        };

        produtoEditando = null;
        alert("Produto atualizado com sucesso!");

    } else {
        const id = produtos.length > 0 ? produtos[produtos.length - 1].id + 1 : 1;

        produtos.push({
            id,
            nome,
            preco,
            categoria
        });

        alert("Produto inserido com sucesso!");
    }

    localStorage.setItem("produtos", JSON.stringify(produtos));

    form.reset();
    carregarProdutos();
});

function editarProduto(id) {
    const produtos = JSON.parse(localStorage.getItem("produtos")) || [];
    const produto = produtos.find(p => p.id == id);

    document.getElementById("nome").value = produto.nome;
    document.getElementById("preco").value = produto.preco;
    document.getElementById("categoria").value = produto.categoria;

    produtoEditando = id;

    window.scrollTo({ top: 0, behavior: "smooth" });
}

function excluirProduto(id) {
    if (!confirm("Tem certeza que deseja excluir?")) return;

    let produtos = JSON.parse(localStorage.getItem("produtos")) || [];
    produtos = produtos.filter(p => p.id != id);

    localStorage.setItem("produtos", JSON.stringify(produtos));
    carregarProdutos();
}
