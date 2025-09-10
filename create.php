<?php include_once("templates/header.php"); ?>

<div class="container">    
    <?php   include_once("templates/backbtn.html"); ?>
    <h1 id="main-title">Cadastrar</h1>
    <form action="<?= $BASE_URL ?>config/process.php" method="POST" id="create-form">
        <input type="hidden" name="type" value="create">

        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o Nome" required>
        </div>
        <div class="form-group">
            <label for="email">Email :</label> 
            <input type="text" class="form-control" id="email" name="email" placeholder="Digite o Email" required>
        </div>
        <div class="form-group">
            <label for="telefone">Telefone :</label> 
            <input type="text" class="form-control" id="telefone" name="telefone"  onkeyup="formatarTelefone(this)"  placeholder="Digite o Telefone" data-mask="(00) 0000-0000" data-mask-selectonfocus="true" required>
        </div>
        <div class="form-group">
            <label for="cpf">cpf :</label> 
            <input type="text" class="form-control" id="cpf" name="cpf" onkeyup="formatarCPF(this)" placeholder="Digite o CPF" required>
        </div>
        <div class="form-group">
            <label for="empresa">Empresa :</label> 
            <input type="text" class="form-control" id="empresa" name="empresa" placeholder="Digite o nome da empresa" required>
        </div>
        <div class="form-group">
            <label for="cnpj">CNPJ :</label> 
            <input type="text" class="form-control" id="cnpj" name="cnpj" onkeyup="formatarCNPJ(this)" placeholder="Digite o CNPJ" required>
        </div>
        <div class="form-group">
            <label for="cep">CEP :</label> 
            <input type="text" class="form-control" id="cep" onkeyup="formatarCEP(this)" name="cep" placeholder="Digite o CEP"  maxlength="9" onblur="buscarCep()" required>
           
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
            <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Numero/ complemento e etc..." required>
        </div>
    <button type="submit" id="btn-cadastrar" class="btn btn-primary">Cadastrar</button>
    </form>
</divclas>

<?php include_once("templates/footer.php"); ?>