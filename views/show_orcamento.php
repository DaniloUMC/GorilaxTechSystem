<?php 
    include_once("templates/header.php"); 
    include_once("templates/navbar.php"); ?>
<div class="container">
    <?php   include_once("templates/backbtn_ordem_servico.html"); ?>"
    <h1 id="main-title">Gerar Orçamento</h1>


    <form action="<?= $BASE_URL ?>config/process.php" method="POST" id="create-form">
    

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
                <textarea disabled class="form-control" name="observacao" id="observacao" placeholder="Caso tenha alguma observação"><?= $ordem['observacao']?></textarea>
            
       
          
            </div>
            <div class="form-group">
                <label for="data_orcamento">Data Orçamento:</label>
                <input disabled required name="data_orcamento" id="data_orcamento" type="date" class="form-control"   value="<?= $ordem["data_orcamento"]?>">
            </div> 

            <?php
                if (!empty($ordem['produtos_servicos'])):
                    $itens = explode(',', $ordem['produtos_servicos']);
                ?>
                    <h4 class="mt-4">Produtos / Serviços do Orçamento</h4>
                    <table class="table" id="produtos-table">
                        <thead>
                            <tr>
                                <th scope="col">ID Produto</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Preço Unitário</th>
                                <th scope="col">Subtotal</th>
                            </tr>
                        </thead>
                   <?php
                        include_once("controllers/HtmlController.php"); 

                        $resultado = renderTabelaItens($itens, $conn);
                        $linhas = $resultado['html'];
                        $valor_total = $resultado['valor_total'];
                    ?>

                    <tbody>
                        <?= $linhas ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="4"><strong>Total</strong></td>
                            <td><strong>R$ <?= number_format($valor_total, 2, ',', '.') ?></strong></td>
                        </tr>
                    </tfoot>
                    </table>
                <?php else: ?>
                    <p><em>Nenhum produto ou serviço vinculado a este orçamento.</em></p>
                <?php endif; ?>

         

    </form>
</div>




<?php
    include_once("templates/footer.php");
?>