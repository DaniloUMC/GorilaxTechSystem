<?php
    include_once("templates/header.php");
?>
<div class="container" id="view-contact-container">
    <?php   include_once("templates/backbtn_prod.html"); ?>
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

<div style="text-align: center;">
    <?php
        $idFormatado = str_pad($produto['id'], 6, '0', STR_PAD_LEFT); 
        // Gerar a URL para o QR Code usando a API goQR.me
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($idFormatado) . "&size=300x300";

        // Exibir o QR Code diretamente na tela
        echo '<img src="' . $qrCodeUrl . '" alt="QR Code">';
    ?>
</div>

<div style="height: 50px;"></div>

<?php
    include_once("templates/header.php");
?>