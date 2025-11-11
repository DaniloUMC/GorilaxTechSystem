<?php include_once("templates/header.php"); ?>

<div class="container">    
    <?php   include_once("templates/backbtn_ordem_servico.html"); ?>
    <h1 id="main-title">Cadastrar Ordem de Serviço</h1>
    <form action="<?= $BASE_URL ?>config/process.php" method="POST" id="create-form">
        <input type="hidden" name="type" value="create_ordem_servico">
        <input type="hidden" name="id" value="<?= $contato["id"] ?>">

        <div class="form-group">
            <label for="cliente">Cliente :</label> 
            <input type="text" class="form-control" id="cliente" name="cliente"  disabled value="<?= $contato["nome"]?>" required>
        </div>

        <div class="form-group">
            <label for="equipamento">Equipamento Cliente :</label> 
            <textarea class="form-control" name="equipamento" id="equipamento" placeholder="Digite as informações do equipamento do Cliente..."></textarea>
        </div>
        <div class="form-group">
            <label for="complementos">Complementos :</label> 
            <input type="text" class="form-control" id="complementos" name="complementos"  placeholder="O que acompanha a maquina do cliente?" >
        </div>


        <div class="form-group">
            <label for="data_entrada">Data Entrada Equipamento:</label>
            <input type="date" class="form-control" id="data_entrada" name="data_entrada"  required>
        </div>
        <div class="form-group">
            <label for="pre_diagnostico">Pré diagnóstico:</label> 
            <input type="text" class="form-control" id="pre_diagnostico" name="pre_diagnostico" placeholder="Digite a queixa ou necessidade do equipamento" required>
        </div>
        <div class="form-group">
            <label for="observacao">Observação :</label> 
            <textarea class="form-control" name="observacao" id="observacao" placeholder="Caso tenha alguma observação"></textarea>

            
        </div>
     
    <button type="submit" id="btn-cadastrar" class="btn btn-primary">Cadastrar</button>
    </form>
</divclas>

<?php include_once("templates/footer.php"); ?>