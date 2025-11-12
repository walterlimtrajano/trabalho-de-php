# trabalho-de-php
# 🛒 Projeto E-commerce — Programação Web

## 👥 Equipe
- **Líder do Projeto:** Lucas José da Silva  
- **Desenvolvedores:** Walterlim Trajano da Silva Júnior e Gabriel Rabelo Barbosa  
- **Designer Gráfico:** José Monteiro da Silva Neto  
- **QA (Testes):** Hebert Filipe da Silva  
- **Analista de Requisitos:** José Júlio Regis  
- **Professor Orientador:** Leandro Santana  

---

## 🧩 Descrição do Projeto
O **E-commerce PHP** é um sistema de vendas online desenvolvido para simular uma loja virtual completa.  
Ele permite que usuários visualizem produtos, adicionem ao carrinho, calculem frete e finalizem pedidos via **Pix** ou **cartão de crédito**.  
O **administrador** tem acesso a um painel com relatórios, controle de estoque e acompanhamento de pedidos.

---

## 🚀 Funcionalidades Principais
- Cadastro e exibição de produtos (nome, preço, descrição, imagem)  
- Carrinho de compras dinâmico  
- Cálculo de frete  
- Checkout integrado com meios de pagamento  
- Painel administrativo com:
  - Controle de estoque  
  - Relatórios de vendas  
  - Acompanhamento de pedidos  

---

## 🛠️ Tecnologias Utilizadas
- **Linguagem:** PHP  
- **Banco de Dados:** MySQL  
- **Front-end:** HTML5, CSS3, JavaScript  
- **Servidor:** Apache (via XAMPP ou Laragon)  
- **Controle de Versão:** Git e GitHub  

---

## 🗂️ Estrutura do Projeto
```
/ecommerce
├── index.php
├── produtos/
├── carrinho/
├── admin/
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
├── includes/
└── database/
```

---

## 🧪 Testes
O especialista QA (Hebert Filipe) será responsável por validar todas as funções, verificando:
- Cálculo de valores e frete  
- Operações do carrinho  
- Integração de pagamento  
- Responsividade do site  

---

## 🌱 Contribuições e Branches

Cada membro terá sua **branch própria** no repositório:

| Membro | Função | Nome da Branch |
|:--------|:--------|:----------------|
| Lucas José | Líder / Backend | `feature/lucasjose-dev` |
| Walterlim Trajano | Desenvolvedor | `feature/walterlimtrajano` |
| Gabriel Rabelo | Desenvolvedor | `feature/gabrielbarbosa16` |
| José Monteiro | Designer Gráfico | `feature/monteironetoh` |
| Hebert Filipe | QA | `feature/hebertfilipe` |
| José Júlio | Analista de Requisitos | `feature/julio-regis` |

> 📝 Após finalizar cada tarefa, os commits devem ser feitos em suas branches e revisados antes do merge na branch principal (`main`).

---

## ⚙️ Como Executar o Projeto
1. Clone o repositório:
   ```bash
   git clone https://github.com/walterlimtrajano/trabalho-de-php.git
   ```
2. Importe o banco de dados MySQL.
3. Coloque os arquivos no diretório do servidor local (ex: `htdocs`).
4. Acesse pelo navegador:
   ```
   http://localhost/trabalho-de-php/ecommerce/index.php
   ```

---

## 📜 Licença
Este projeto é de uso acadêmico e segue as diretrizes da disciplina **Programação Web**.