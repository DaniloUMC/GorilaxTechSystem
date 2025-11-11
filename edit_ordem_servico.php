<?php include_once("templates/header.php"); ?>
<div class="container">
    <?php   include_once("templates/backbtn_ordem_servico.html"); ?>"
    <h1 id="main-title">Editar Ordem de Serviço</h1>


    <form action="<?= $BASE_URL ?>config/process.php" method="POST" id="create-form">
        <input type="hidden" name="type" value="edit_ordem">
        <input type="hidden" name="id" value="<?= $ordem['id']?>">

            <div class="form-group">
                <label for="nome">Cliente:</label>
                <input disabled type="text" class="form-control" id="nome" name="nome"  
                value="<?= $cliente['nome']?>" placeholder="Digite o Nome" required>
            </div>
            <div class="form-group">
                <label for="equipamento_cliente">Equipamento Cliente :</label> 
                <input type="text" class="form-control" id="equipamento_cliente" 
                name="equipamento_cliente"  value="<?= $ordem['equipamento_cliente']?>" placeholder="Digite o Equipamento do cliente" required>
            </div>
            <div class="form-group">
                <label for="complementos">Complementos  :</label> 
                <input type="text" class="form-control" id="complementos" name="complementos" value="<?= $ordem['complementos']?>" 
                placeholder="O que acompanha a maquina do cliente?"  >
            </div>
        
            <div class="form-group">
                <label for="data_entrada2">Data Entrada Equipamento:</label>
                <input type="date" class="form-control" id="data_entrada" name="data_entrada" value="<?= $ordem["data_abertura"]?>" required>
     
            </div>
            <div class="form-group">
                <label for="pre_diagnostico">Pré diagnóstico:</label> 
                <input type="text" class="form-control" id="pre_diagnostico" name="pre_diagnostico" value="<?= $ordem['pre_diagnostico']?>" 
                placeholder="Digite a queixa ou necessidade do equipamento"  required>
            </div>

        
            <div class="form-group">
                <label for="observacao">Observação :</label> 
                <input type="text" class="form-control" id="observacao" name="observacao" 
                value="<?= $ordem['observacao']?>" placeholder="Digite a Observação" >
            </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>

<?php include_once("templates/header.php"); ?>"