<?php
    include_once("templates/header.php");
?>

<div class="container">

    <?php if(isset($printMsg) && $printMsg != '') : ?>
        <p id="msg"><?= $printMsg?></p>
    <?php endif; ?>

    <h1 id="main-title">Produtos & Serviços</h1>

    <!-- 🔗 Botão adicionar -->
    <nav style="text-align: end;">
        <div style="text-align: center;">
            <div class="navbar-nav">
                <a class="nav-link active" href="<?= $BASE_URL ?>create_produto.php">
                    <b style="font-weight: bold;">Adicionar</b> 
                    <i class="fas fa-plus start-icon"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- 🔍 Campo de busca -->
    <div style="border-bottom: none; margin-top: 15px;">
        <input 
            style="width: 100%;" 
            class="form-control" 
            type="text" 
            id="searchProduto" 
            oninput="buscarProduto(this.value)" 
            placeholder="Digite o nome do produto..."
        >
    </div>

    <div style="height: 20px;"></div>

    <!-- 🧾 Tabela dos produtos -->
    <div id="tabelaProdutos">
        <?php if(count($produtos) > 0): ?>
            <table class="table" id="contatos-table">
                <thead>
                    <tr>
                        <th style="text-align: center;" scope="col">Nº</th>
                        <th style="text-align: center;" scope="col">Nome</th>
                        <th style="text-align: center;" scope="col">Tipo</th>
                        <th style="text-align: center;" scope="col">Marca</th>
                        <th style="text-align: center;" scope="col">Preço de Custo</th>
                        <th style="text-align: center;" scope="col">Preço Final</th>
                        
                        <th style="text-align: center;" scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($produtos as $produto): ?>
                        <?php $idFormatado = str_pad($produto['id'], 6, '0', STR_PAD_LEFT); ?>
                        <tr>
                            <td style="text-align: center;" class="col-id"><?= $idFormatado ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($produto['nome']) ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($produto['tipo']) ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($produto['marca']) ?></td>
                            <td style="text-align: center;">R$ <?= number_format($produto['preco_custo'], 2, ",", ".") ?></td>
                            <td style="text-align: center;">R$ <?= number_format($produto['preco_final'], 2, ",", ".") ?></td>
                            <td style="text-align: center;" class="actions">
                                <a title="Visualizar" href="<?= $BASE_URL ?>show_produto.php?id=<?= $produto['id'] ?>"><i class="fas fa-eye check-icon"></i></a>
                                <a title="Editar" href="<?= $BASE_URL ?>edit_produto.php?id=<?= $produto['id'] ?>"><i class="fas fa-edit edit-icon"></i></a>
                                <form class="delete-form" action="<?= $BASE_URL ?>/config/process.php" method="POST">
                                    <input type="hidden" name="type" value="delete_prod">
                                    <input type="hidden" name="id" value="<?= $produto["id"] ?>">
                                    <button title="Excluir" type="submit" class="delete-btn"><i class="fas fa-trash delete-icon"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p id="empty-list-text">Ainda não há Cadastros, 
                <a href="<?= $BASE_URL ?>create_produto.php">Clique aqui para adicionar</a>.
            </p>
        <?php endif; ?>
    </div>
</div>

<?php include_once("templates/footer.php"); ?>

<script>
function buscarProduto(valor) {
    const tabela = document.getElementById("tabelaProdutos");

    // Se o campo estiver vazio, recarrega todos os produtos
    if (valor.trim().length === 0) {
        fetch("produtos_listar_tabela.php") // <-- arquivo PHP que lista todos os produtos
            .then(res => res.text())
            .then(html => {
                tabela.innerHTML = html;
            })
            .catch(err => console.error("Erro ao recarregar produtos:", err));
        return;
    }

    // Só busca após 3 letras (pode mudar para 8 se quiser)
    if (valor.trim().length < 3) {
        return;
    }

    const formData = new FormData();
    formData.append("type", "pesquisa_inteligente_produto");
    formData.append("busca", valor);

    fetch("config/process.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(html => {
        tabela.innerHTML = html; // substitui o conteúdo da tabela filtrada
    })
    .catch(err => console.error("Erro na busca:", err));
}
</script>

