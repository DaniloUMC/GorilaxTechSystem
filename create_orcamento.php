<?php include_once("templates/header.php"); ?>
<div class="container">
    <?php   include_once("templates/backbtn_ordem_servico.html"); ?>"
    <h1 id="main-title">Gerar Orçamento</h1>


    <form action="<?= $BASE_URL ?>config/process.php" method="POST" id="create-form">
        <input type="hidden" name="type" value="create_orcamento">

        <input type="hidden" name="produtos_string" id="produtos_string">
       
        <input type="hidden" name="id" value="<?= $ordem['id']?>">

            <div class="form-group">
                <label for="nome">Cliente:</label>
                <input disabled type="text" class="form-control" id="nome" name="nome"  
                value="<?= $cliente['nome']?>" placeholder="Digite o Nome" required>
            </div>
            <div class="form-group">
                <label for="equipamento_cliente">Equipamento Cliente :</label> 
                <input type="text" class="form-control" id="equipamento_cliente" 
                name="equipamento_cliente"  disabled value="<?= $ordem['equipamento_cliente']?>" placeholder="Digite o Equipamento do cliente" required>
            </div>
            <div class="form-group">
                <label for="complementos">Complementos  :</label> 
                <input disabled type="text" class="form-control" id="complementos" name="complementos" value="<?= $ordem['complementos']?>" 
                placeholder="O que acompanha a maquina do cliente?"  required>
            </div>
        
            <div class="form-group">
                <label for="data_abertura">Data Entrada Equipamento:</label>
                <input disabled type="text" class="form-control" disabled  value="<?= $ordem["data_abertura"]?>">

     
            </div>
            <div class="form-group">
                <label for="pre_diagnostico">Pré diagnóstico:</label> 
                <input disabled type="text" class="form-control" id="pre_diagnostico" name="pre_diagnostico" value="<?= $ordem['pre_diagnostico']?>" 
                placeholder="Digite a queixa ou necessidade do equipamento"  required>
            </div>

        
            <div class="form-group">
                <label for="observacao">Observação :</label> 
                <textarea class="form-control" name="observacao" id="observacao" placeholder="Caso tenha alguma observação"><?= $ordem['observacao']?></textarea>

           <div class="form-group">
                <button type="button" class="btn btn-primary" onclick="abrirProdutos()">Adicionar Produtos/Serviços</button>
            </div>
            <div class="form-group">
                <div id="tabelaSelecionados" class="mt-3"></div>

            </div>
            <!-- Tabela dos produtos selecionados -->
            <div id="tabelaSelecionados" class="mt-3"></div>

            <div class="form-group">
                <label for="data_orcamento">Data Orçamento:</label>
                <input required name="data_orcamento" id="data_orcamento" type="date" class="form-control"   value="<?= $ordem["data_orcamento"]?>">

     
            </div> 


        <button type="submit" class="btn btn-primary">Gerar Orçamento</button>
    </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Array global com produtos selecionados
    window.produtosSelecionados = window.produtosSelecionados || [];

    // Abre a popup e inicializa os produtos marcados
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

    // Função chamada pela popup ao confirmar seleção
    window.adicionarProdutosSelecionados = function (lista) {
        window.produtosSelecionados = Array.isArray(lista) ? lista : [];
        atualizarTabelaPrincipal();
    };

    // Atualiza a tabela principal e o campo hidden
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

        // Atualiza o campo hidden
        if (campoHidden) campoHidden.value = produtosString.join(",");
        console.log("📦 produtos_string =", campoHidden.value);
    }

    // Atualiza a string ao mudar quantidades
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

    // Atualiza o campo antes de enviar o formulário
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

    // Função de segurança para evitar injeção
    function escapeHtml(text) {
        if (text === undefined || text === null) return "";
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Inicializa a tabela vazia
    atualizarTabelaPrincipal();
});
</script>



<?php include_once("templates/header.php"); ?>"