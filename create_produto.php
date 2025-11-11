<?php include_once("templates/header.php"); ?>

<div class="container">    
    <?php   include_once("templates/backbtn_prod.html"); ?>
    <h1 id="main-title">Cadastrar</h1>
    <form action="<?= $BASE_URL ?>config/process.php" method="POST" id="create-form">
        <input type="hidden" name="type" value="create_prod">

        <div class="form-group">
            
            <label for="nome">Produto:</label>
            <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome" required>
        </div>
        <div class="form-group">
            <label for="tipo">tipo :</label> 
            <select name="tipo" id="tipo" class="form-control" required onchange="toggleCampos()" required>
                <option value="Produto">Produto</option>
                <option value="Serviço">Serviço</option>
            </select>
        </div>
        <div class="form-group"  id="div_marca">
            <label for="marca">Marca:</label>
            <input type="text" class="form-control" id="marca" name="marca" placeholder="Digite a marca" >
        </div>
        <div class="form-group">
            <label for="descricao">Descrição :</label> 
            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Digite uma descrição" required>
        </div>
        <div class="form-group" id="div_preco_custo">
            <label for="preco_custo">Preço de custo :</label> 
            <input type="text" class="form-control" id="preco_custo" name="preco_custo"  maxlength="50" onkeyup="formatarReais(this)" placeholder="Digite o preço de custo" >
        </div>
        <div class="form-group">
            <label for="preco_final">Preço final :</label> 
            <input type="text" class="form-control" id="preco_final" name="preco_final" maxlength="50" onkeyup="formatarReais(this)"  placeholder="Digite o preço de final" required>
        </div>
     
    <button type="submit" id="btn-cadastrar" class="btn btn-primary">Cadastrar</button>
    </form>
</divclas>


<?php include_once("templates/footer.php"); ?>