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
