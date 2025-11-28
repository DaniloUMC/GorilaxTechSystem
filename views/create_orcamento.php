<?php 
     include_once(__DIR__ . '/../templates/headerl.php');
    include_once(__DIR__ . '/../templates/navbar.php');
?>

<div class="container">
    <?php   include_once("../templates/backbtn_ordem_servico.html"); ?>
    <h1 id="main-title">Gerar Orçamento</h1>
    <form action="../config/process.php" method="POST" id="create-form">
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

            <div class="form-group">
                <label for="data_orcamento">Data Orçamento:</label>
                <input required name="data_orcamento" id="data_orcamento" type="date" class="form-control"   value="<?= $ordem["data_orcamento"]?>">

     
            </div> 


        <button type="submit" class="btn btn-primary">Gerar Orçamento</button>
    </form>
</div>



<script src="<?= $BASE_URL ?>../scripts/abrir_produtos.js"></script>


</body>
</html>