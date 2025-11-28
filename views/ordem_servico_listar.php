<?php
    include_once(__DIR__ . '/../templates/headerl.php');
    include_once(__DIR__ . '/../templates/navbar.php');
    $filtroStatus = isset($_GET['status_atual']) ? $_GET['status_atual'] : '';
    $filtroData = isset($_GET['data_abertura']) ? $_GET['data_abertura'] : '';
   
    
?>

<div class="container">

    <a href="<?= $BASE_URL ?>clientes_listar.php"><h1 id="main-title">Registros Serviços
    </h1></a>

    <!-- 🔍 FILTROS -->
    <form method="GET" action="" class="row" style="margin-bottom: 30px;">
        <div class="col-md-4">
            <label for="status_atual"><strong>Status:</strong></label>
            <select name="status_atual" id="status_atual" class="form-control">
                <option value="">-- Todos --</option>
                <option value="O.S" <?= $filtroStatus == 'O.S' ? 'selected' : '' ?>>O.S</option>
                <option value="Orçamento" <?= $filtroStatus == 'Orçamento' ? 'selected' : '' ?>>Orçamento</option>
                <option value="Serviço" <?= $filtroStatus == 'Serviço' ? 'selected' : '' ?>>Serviço</option>
                <option value="Finalizado" <?= $filtroStatus == 'Finalizado' ? 'selected' : '' ?>>Finalizado</option>
            </select>
        </div>

        <div class="col-md-4">
            <label for="data_abertura"><strong>Data de Abertura:</strong></label>
            <input type="date" name="data_abertura" id="data_abertura" class="form-control" 
                   value="<?= htmlspecialchars($filtroData) ?>">
        </div>

        <div class="col-md-4" style="display: flex; align-items: flex-end;">
            <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrar</button>
        </div>
    </form>

    <!-- TABELA DE RESULTADOS -->
    <?php if(count($ordem_servicos) > 0): ?>
        <table class="table" id="contatos-table">
            <thead>
                <tr>
                    <th scope="col">Nº</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Data Abertura</th>
                    <th scope="col">Status Atual</th>
                    <th scope="col">Data Orçamento</th>
                    <th scope="col">Valor Total</th>
                    <th scope="col">pré Aprovado?</th>

                    <th scope="col">Entregue?</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ordem_servicos as $ordem_servico): ?>
                    <?php 
                        $idFormatado = str_pad($ordem_servico['id'], 6, '0', STR_PAD_LEFT);
                        $entrega = $ordem_servico['entregue'] ? "Sim" : "Não";
                        $data_orcamento = !empty($ordem_servico['data_orcamento']) ? 
                        $ordem_servico['data_orcamento'] : "Orçamento não aberto";
                        $orc_aprovado = $ordem_servico['orc_aprovado'] ? "Sim" : "Não";

                    ?>
                    <tr>
                        <td><?= $idFormatado ?></td>
                        <td><?= htmlspecialchars($ordem_servico['nome_cliente']) ?></td>
                        <td><?= htmlspecialchars($ordem_servico['data_abertura']) ?></td>
                        <td><?= htmlspecialchars($ordem_servico['status_atual']) ?></td>
                        <td><?= htmlspecialchars($data_orcamento) ?></td>
                        <td>R$ <?= number_format($ordem_servico['valor_total_orcamento'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($orc_aprovado) ?></td>

                        <td><?= $entrega ?></td>

                        <td class="actions">

                            <?php 
                                include_once("../controllers/HtmlController.php"); 
                                echo renderAcoesOrdemServico($ordem_servico, $BASE_URL);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p id="empty-list-text">Nenhuma ordem de serviço encontrada com os filtros aplicados.</p>
    <?php endif; ?>
</div>

<?php include_once(__DIR__ . '/../templates/footer.php'); ?>
