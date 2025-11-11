<?php include_once("templates/header.php"); ?>
<div class="container">
    <?php   include_once("templates/backbtn_prod.html"); ?>"
    <h1 id="main-title">Editar Cadastro</h1>

    <form action="<?= $BASE_URL ?>config/process.php" method="POST" id="create-form">
        <input type="hidden" name="type" value="edit_prod">
        <input type="hidden" name="id" value="<?= $produto['id']?>">

            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome"  
                value="<?= $produto['nome']?>" placeholder="Digite o Nome" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo :</label> 
                <input type="text" class="form-control" id="tipo" 
                name="tipo"  value="<?= $produto['tipo']?>" placeholder="Digite o Tipo" required>
            </div>
            <div class="form-group">
                <label for="marca">Marca :</label> 
                <input type="text" class="form-control" id="marca" name="marca" value="<?= $produto['marca']?>" 
                placeholder="Digite a Marca"  required>
            </div>
        
            <div class="form-group">
                <label for="preco_custo">Preço de custo :</label> 
                <input type="text" class="form-control" id="preco_custo"  
                value="<?= $produto['preco_custo']?>" name="preco_custo" onkeyup="formatarReais(this)"  placeholder="Digite o Preço de custo" required>
            </div>
                <div class="form-group">
                <label for="preco_final">Preço final :</label> 
                <input type="text" class="form-control" id="preco_final"  
                value="<?= $produto['preco_final']?>" name="preco_final" onkeyup="formatarReais(this)"  placeholder="Digite o Preço Final" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição :</label> 
                <input type="text" class="form-control" id="descricao" name="descricao" 
                value="<?= $produto['descricao']?>" placeholder="Digite a Descrição" required>
            </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>

<?php include_once("templates/header.php"); ?>"