<?php
include("../config/connection.php"); // conexão
$query = "SELECT id, nome, preco_final FROM produtos";
$stmt = $conn->prepare($query);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Selecionar Produtos</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
    input[type="number"] { width: 90px; }
</style>
</head>
<body class="p-3">
<h3>Selecionar Produtos</h3>

<input id="busca" class="form-control mb-2" placeholder="Buscar produto..." onkeyup="filtrarProdutos()">
<button class="btn btn-success" onclick="enviarSelecionados()">Confirmar Seleção</button>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th></th>
            <th>Nome</th>
            <th>Preço Final</th>
            <th>Quantidade</th>
        </tr>
    </thead>
    <tbody id="tabelaProdutos">
        <?php foreach($produtos as $p): ?>
        <tr data-id="<?= $p['id'] ?>">
            <td><?= $p['id'] ?></td>
            <td><input type="checkbox" class="chk-produto" data-json='<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>'></td>
            <td class="prod-nome"><?= htmlspecialchars($p['nome']) ?></td>
            <td class="prod-preco"><?= number_format($p['preco_final'], 2, ',', '.') ?></td>
            <td><input type="number" min="1" value="1" class="quantidade form-control form-control-sm"></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<script>
// 🔍 Filtro de produtos
function filtrarProdutos() {
    const val = document.getElementById('busca').value.toLowerCase();
    document.querySelectorAll('#tabelaProdutos tr').forEach(tr => {
        const nome = tr.querySelector('.prod-nome').innerText.toLowerCase();
        tr.style.display = nome.includes(val) ? '' : 'none';
    });
}

// 🟩 Reaplica seleção anterior
window.inicializarSelecionados = function(listaSelecionados) {
    if (!Array.isArray(listaSelecionados) || listaSelecionados.length === 0) return;
    document.querySelectorAll('#tabelaProdutos tr').forEach(tr => {
        const chk = tr.querySelector('.chk-produto');
        const qtdInput = tr.querySelector('.quantidade');
        const prod = JSON.parse(chk.getAttribute('data-json'));
        const encontrado = listaSelecionados.find(p => Number(p.id) === Number(prod.id));
        if (encontrado) {
            chk.checked = true;
            qtdInput.value = Number(encontrado.quantidade) || 1;
        }
    });
};

// 🟢 Envia produtos para a janela principal
function enviarSelecionados() {
    const selecionados = [];
    document.querySelectorAll('#tabelaProdutos tr').forEach(tr => {
        const chk = tr.querySelector('.chk-produto');
        if (!chk || !chk.checked) return;
        const prod = JSON.parse(chk.getAttribute('data-json'));
        const qtd = tr.querySelector('.quantidade').value || 1;
        prod.quantidade = Number(qtd);
        selecionados.push(prod);
    });

    try {
        if (window.opener && !window.opener.closed && typeof window.opener.adicionarProdutosSelecionados === 'function') {
            window.opener.adicionarProdutosSelecionados(selecionados);
            window.close();
            return;
        }
    } catch(e) {
        console.warn("Não foi possível usar opener:", e);
    }

    // fallback com localStorage
    try {
        localStorage.setItem('produtosSelecionados_temp', JSON.stringify(selecionados));
        window.close();
    } catch(e) {
        alert("Erro ao enviar seleção: " + e.message);
    }
}

// 🟠 Se popup abriu de novo, tenta restaurar do localStorage
(function applyLocalStorageIfExists() {
    try {
        const temp = localStorage.getItem('produtosSelecionados_temp');
        if (temp) {
            const arr = JSON.parse(temp);
            if (Array.isArray(arr) && arr.length) {
                inicializarSelecionados(arr);
                localStorage.removeItem('produtosSelecionados_temp');
            }
        }
    } catch(e) {}
})();
</script>
</body>
</html>
