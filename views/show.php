<?php
  include_once(__DIR__ . '/../templates/headerl.php');
    include_once(__DIR__ . '/../templates/navbar.php');
?>
<div class="container" id="view-contact-container">
    <?php  include_once(__DIR__ . '/../templates/backbtn.html');  ?>
    <h1 id="main-title"><?= $contato["nome"]?></h1>

     <p class="bold">Email:</p>
    <p> <?= $contato["email"]?></p>
     <p class="bold">Telefone:</p>
    <p> <?= $contato["telefone"]?></p>
     <p class="bold">CPF:</p>
    <p> <?= $contato["cpf"]?></p>
    <p class="bold">Empresa:</p>
    <p> <?= $contato["empresa"]?></p>
       <p class="bold">CNPJ:</p>
    <p> <?= $contato["cnpj"]?></p>
       <p class="bold">CEP:</p>
    <p> <?= $contato["cep"]?></p>
    

</div>
<div style="text-align: center;">
    <form  action="<?= $BASE_URL ?>/config/process.php" method="POST">
        <input type="hidden" name="type" value="contar">
        <input type="hidden" name="id" value="<?= $contato["id"]?>">
    </form>
</div> 

<div style="height: 50px;"></div>

<div style="text-align: center;">
    <?php
        include_once("../controllers/HtmlController.php"); // ajuste o caminho conforme sua estrutura
        echo gerarQrCodeContato($contato['id']);
    ?>

</div>

<div style="height: 50px;"></div>


<?php include_once(__DIR__ . '/../templates/footer.php'); ?>