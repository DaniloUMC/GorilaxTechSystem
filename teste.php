<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Adicionar Produtos</title>
<style>
  body { font-family: Arial; margin: 20px; }
  table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    padding: 5px;
  }
</style>
</head>
<body>
<h3>Adicionar Produtos</h3>

<input id="produto" placeholder="Produto">
<input id="quantidade" type="number" min="1" value="1">
<button onclick="adicionar()">Adicionar</button>

<table id="tabela">
  <thead>
    <tr><th>Produto</th><th>Qtd</th></tr>
  </thead>
  <tbody></tbody>
</table>

<script>
let produtos = [];

function adicionar() {
  const nome = document.getElementById("produto").value;
  const qtd = document.getElementById("quantidade").value;
  if (!nome) return;

  produtos.push({ nome, qtd });
  atualizarTabela();

  // salvar dados localmente para manter no orçamento principal
  sessionStorage.setItem("produtos", JSON.stringify(produtos));
}

function atualizarTabela() {
  const tbody = document.querySelector("#tabela tbody");
  tbody.innerHTML = "";
  produtos.forEach(p => {
    const tr = document.createElement("tr");
    tr.innerHTML = `<td>${p.nome}</td><td>${p.qtd}</td>`;
    tbody.appendChild(tr);
  });
}

// caso já tenha produtos salvos
window.onload = () => {
  const salvos = sessionStorage.getItem("produtos");
  if (salvos) {
    produtos = JSON.parse(salvos);
    atualizarTabela();
  }
};
</script>
</body>
</html>
