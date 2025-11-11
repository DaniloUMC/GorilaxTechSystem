<?php include_once("templates/header.php"); ?>
<div class="container">
    <?php   include_once("templates/backbtn.html"); ?>"
    <h1 id="main-title">Editar Cadastro</h1>
   
    <form action="<?= $BASE_URL ?>config/process.php" method="POST" id="create-form">
    <input type="hidden" name="type" value="edit">
    <input type="hidden" name="id" value="<?= $contato['id']?>">

        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome"  
            value="<?= $contato['nome']?>" placeholder="Digite o Nome" required>
        </div>
        <div class="form-group">
            <label for="email">Email :</label> 
            <input type="text" class="form-control" id="email" 
            name="email"  value="<?= $contato['email']?>" placeholder="Digite o Email" required>
        </div>
        <div class="form-group">
            <label for="telefone">Telefone :</label> 
            <input type="text" class="form-control" id="telefone" name="telefone" value="<?= $contato['telefone']?>" 
            onblur="formatarTelefone(this)" placeholder="Digite o Telefone"  required>
        </div>
        <div class="form-group">
            <label for="cpf">cpf :</label> 
            <input type="text" class="form-control" id="cpf" name="cpf" 
            onblur="formatarCPF(this)"  value="<?= $contato['cpf']?>" placeholder="Digite o CPF" required>
        </div>
        <div class="form-group">
            <label for="empresa">Empresa :</label> 
            <input type="text" class="form-control" id="empresa"  
            value="<?= $contato['empresa']?>" name="empresa" placeholder="Digite o nome da empresa" >
        </div>
        <div class="form-group">
            <label for="cnpj">CNPJ :</label> 
            <input type="text" class="form-control" id="cnpj"  value="<?= $contato['cnpj']?>" name="cnpj" 
            onblur="formatarCNPJ(this)" placeholder="Digite o CNPJ" >
        </div>
        <div class="form-group">
            <label for="cep">CEP :</label> 
            <input type="text" class="form-control" id="cep"  value="<?= $contato['cep']?>" 
             name="cep" placeholder="Digite o CEP"  maxlength="9" onblur="buscarCep(),formatarCEP(this) " required>
        </div>
        <div class="form-group">
            <label for="rua">Rua:</label> 
            <input type="text" class="form-control" id="rua" name="rua" readonly>
        </div>
        <div class="form-group">
            <label for="bairro">Bairro:</label> 
            <input type="text" class="form-control" id="bairro" name="bairro" readonly>
        </div>
        <div class="form-group">
            <label for="cidade">cidade:</label> 
            <input type="text" class="form-control" id="cidade" name="cidade" readonly>
        </div>
        <div class="form-group">
            <label for="estado">Estado:</label> 
            <input type="text" class="form-control" id="estado" name="estado" readonly>
        </div>
        <div class="form-group">
            <label for="complemento">Complemento:</label> 
            <input type="text" class="form-control" id="complemento"  value="<?= $contato['complemento']?>" 
            name="complemento" placeholder="Numero/ complemento e etc..." required>
        </div>

    <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>

<?php include_once("templates/header.php"); ?>"