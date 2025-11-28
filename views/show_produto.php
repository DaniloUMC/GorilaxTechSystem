<?php
   include_once(__DIR__ . '/../templates/headerl.php');
    include_once(__DIR__ . '/../templates/navbar.php');
?>
<div class="container" id="view-contact-container"> 
    <?php   include_once(__DIR__ . '/../templates/backbtn_prod.html'); ?>
    <h1 id="main-title"><?= $produto["nome"]?></h1>
    <p class="bold" style="padding-top: 10px;">Marca:</p>
    <input type="text" class="form-control" disabled  value="<?= $produto["marca"]?>">
    <p class="bold" style="padding-top: 10px;">Empresa:</p>
    <input type="text" class="form-control" disabled  value="<?= $produto["tipo"]?>">
    <p class="bold" style="padding-top: 10px;">Preço custo:</p>
    <input type="text" class="form-control" disabled  value="<?= $produto["preco_custo"]?>">
    <p class="bold" style="padding-top: 10px;">Preço final:</p>
    <input type="text" class="form-control" disabled  value="<?= $produto["preco_final"]?>">
    <p class="bold" style="padding-top: 10px;">Descrição:</p>
    <input type="text" class="form-control" disabled  value="<?= $produto["descricao"]?>">
</div>
<div style="text-align: center;">
    <form  action="<?= $BASE_URL ?>/config/process.php" method="POST">
        <input type="hidden" name="type" value="contar">
        <input type="hidden" name="id" value="<?= $contato["id"]?>">
    </form>
</div>

<div style="height: 50px;"></div>

<?php
    include_once("../controllers/HtmlController.php"); // ajuste o caminho conforme sua estrutura
    echo gerarQrCodeProduto($produto['id']);
?>

<div style="height: 50px;"></div>


<?php include_once(__DIR__ . '/../templates/footer.php'); ?>