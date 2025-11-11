<?php
    include_once("templates/header.php");
    include_once("config/connection.php"); // Certifique-se de incluir a conexão PDO

    // -------------------------------
    // FILTROS
    // -------------------------------
    $filtroStatus = isset($_GET['status_atual']) ? $_GET['status_atual'] : '';
    $filtroData = isset($_GET['data_abertura']) ? $_GET['data_abertura'] : '';

    $query = "SELECT * FROM ordem_servico WHERE 1=1";

    if (!empty($filtroStatus)) {
        $query .= " AND status_atual = :status_atual";
    }

    if (!empty($filtroData)) {
        $query .= " AND data_abertura = :data_abertura";
    }

    $query .= " ORDER BY id DESC";

    $stmt = $conn->prepare($query);

    if (!empty($filtroStatus)) {
        $stmt->bindParam(":status_atual", $filtroStatus);
    }

    if (!empty($filtroData)) {
        $stmt->bindParam(":data_abertura", $filtroData);
    }

    $stmt->execute();
    $ordem_servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    foreach ($ordem_servicos as &$ordem_servico) {
        $id_cliente = $ordem_servico["id_cliente"];

        // Consulta para buscar o nome do cliente
        $query = "SELECT nome FROM contatos WHERE id = :id_cliente";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Adiciona o nome do cliente ao array da OS
        if ($resultado) {
            $ordem_servico["nome_cliente"] = $resultado["nome"];
        } else {
            $ordem_servico["nome_cliente"] = "Cliente não encontrado";
        }
    }

    
?>

<div class="container">

    <h1 id="main-title">Registros Serviços
    </h1>

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
                            if($ordem_servico['status_atual'] == "Orçamento"){
                                echo "
                                <a title='Visualizar Orçamento' href='". $BASE_URL ."show_orcamento.php?id=" .$ordem_servico['id'] ."'><i class='fas fa-eye check-icon'></i></a>
                                <form class='delete-form' action='{$BASE_URL}/config/process.php' method='POST'>
                                    <input type='hidden' name='type' value='enviar_servico'>
                                    <input type='hidden' name='id' value='{$ordem_servico["id"]}'>
                                    <button title='Enviar para Serviço' type='submit' class='delete-btn'>
                                        <i class='fa fa-wrench'></i>
                                    </button>
                                </form>";
                            }
                            if($ordem_servico['status_atual'] == "Serviço"  ){
                        
                                echo "
                                <a title='Visualizar Orçamento' href='". $BASE_URL ."show_orcamento.php?id=" .$ordem_servico['id'] ."'><i class='fas fa-eye check-icon'></i></a>";
                            }
                      
                            if($ordem_servico['status_atual'] == "Finalizado"  ){
                        
                                echo "
                                <a title='Visualizar Orçamento' href='". $BASE_URL ."show_orcamento.php?id=" .$ordem_servico['id'] ."'><i class='fas fa-eye check-icon'></i></a>";
                            }
                            if($ordem_servico['status_atual'] == "Finalizado" && !$ordem_servico["entregue"] ){
                        
                                echo "
                                <form class='delete-form' action='{$BASE_URL}/config/process.php' method='POST'>
                                    <input type='hidden' name='type' value='entregue'>
                                    <input type='hidden' name='id' value='{$ordem_servico["id"]}'>
                                    <button title='definir como entregue' type='submit' class='delete-btn'>
                                        <i class='fa fa-cube'></i>
                                    </button>
                                </form>";
                        
                        
                            }
                                

                            if($ordem_servico['status_atual'] == "O.S"){
                                echo "
                                <a title='Visualizar' href='". $BASE_URL ."show_ordem_servico.php?id=" .$ordem_servico['id'] ."'><i class='fas fa-eye check-icon'></i></a>
                                <a title='Gerar Orçamento' href='{$BASE_URL}create_orcamento.php?id={$ordem_servico['id']}'><i class='fas fa-briefcase'></i></a>
                                <a title='Editar' href='{$BASE_URL}edit_ordem_servico.php?id={$ordem_servico['id']}'><i class='fas fa-edit edit-icon'></i></a>
                                <form class='delete-form' action='{$BASE_URL}/config/process.php' method='POST'>
                                    <input type='hidden' name='type' value='delete_ordem_servico'>
                                    <input type='hidden' name='id' value='{$ordem_servico["id"]}'>
                                    <button title='Deletar' type='submit' class='delete-btn'>
                                        <i class='fas fa-trash delete-icon'></i>
                                    </button>
                                </form>";
                            }
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

<?php include_once("templates/footer.php"); ?>
