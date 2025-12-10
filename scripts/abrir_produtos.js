
document.addEventListener("DOMContentLoaded", function () {
    window.produtosSelecionados = window.produtosSelecionados || [];

    window.abrirProdutos = function () {
        const popup = window.open("selecionar_produtos.php", "SelecionarProdutos", "width=900,height=700");

        if (!popup) {
            alert("Popup bloqueado — permita popups para este site.");
            return;
        }

        // tenta inicializar a popup até que ela esteja pronta
        const tryInit = setInterval(() => {
            try {
                if (popup && typeof popup.inicializarSelecionados === "function") {
                    popup.inicializarSelecionados(window.produtosSelecionados);
                    clearInterval(tryInit);
                }
                if (!popup || popup.closed) clearInterval(tryInit);
            } catch (err) { /* popup ainda não pronta */ }
        }, 200);
    };

    window.adicionarProdutosSelecionados = function (lista) {
        window.produtosSelecionados = Array.isArray(lista) ? lista : [];
        atualizarTabelaPrincipal();
    };

    function atualizarTabelaPrincipal() {
        const tabelaDiv = document.getElementById("tabelaSelecionados");
        const campoHidden = document.getElementById("produtos_string");

        if (!tabelaDiv) return;

        if (!window.produtosSelecionados || window.produtosSelecionados.length === 0) {
            tabelaDiv.innerHTML = "<p>Nenhum produto selecionado.</p>";
            if (campoHidden) campoHidden.value = "";
            return;
        }

        let html = `
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Preço Final</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>
        `;

        let produtosString = [];

        window.produtosSelecionados.forEach(p => {
            const preco = Number(p.preco_final || 0).toFixed(2).replace('.', ',');
            const qtd = Number(p.quantidade || 1);
            html += `
                <tr data-id="${p.id}">
                    <td>${p.id}</td>
                    <td>${escapeHtml(p.nome)}</td>
                    <td>R$ ${preco}</td>
                    <td><input type="number" class="input-quantidade form-control form-control-sm" value="${qtd}" min="1"></td>
                </tr>
            `;
            produtosString.push(`${p.id}:${qtd}`);
        });

        html += "</tbody></table>";
        tabelaDiv.innerHTML = html;

        if (campoHidden) campoHidden.value = produtosString.join(",");
        console.log("📦 produtos_string =", campoHidden.value);
    }

    document.addEventListener("input", function (e) {
        if (e.target.classList.contains("input-quantidade")) {
            const linha = e.target.closest("tr");
            const id = linha?.dataset.id;
            const novaQtd = e.target.value;

            if (id) {
                const item = window.produtosSelecionados.find(p => String(p.id) === String(id));
                if (item) item.quantidade = novaQtd;
                atualizarTabelaPrincipal();
            }
        }
    });

    document.getElementById("create-form").addEventListener("submit", function () {
        const campoHidden = document.getElementById("produtos_string");
        if (!campoHidden) return;
        const dados = [];

        window.produtosSelecionados.forEach(p => {
            const qtd = p.quantidade || 1;
            dados.push(`${p.id}:${qtd}`);
        });

        campoHidden.value = dados.join(",");
        console.log("🚀 Enviando produtos_string =", campoHidden.value);
    });

    function escapeHtml(text) {
        if (text === undefined || text === null) return "";
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    atualizarTabelaPrincipal();
});
