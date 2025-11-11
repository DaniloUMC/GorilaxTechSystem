<?php
    include_once("templates/header.php");
?>
<div class="container" id="view-contact-container">
    <?php   include_once("templates/backbtn_ordem_servico.html"); ?>

    <h1 id="main-title">Ordem de Serviço: <?= $ordem["id"]?></h1>

    <p class="bold" style="padding-top: 10px;">Cliente:</p>
    <input type="text" class="form-control" disabled  value="<?= $cliente["nome"]?>">
    <p class="bold" style="padding-top: 10px;">Equipamento Cliente:</p>
    <input type="text" class="form-control" disabled  value="<?= $ordem["equipamento_cliente"]?>">
    <p class="bold" style="padding-top: 10px;">Complementos:</p>
     <textarea type="text" class="form-control" disabled  >
        <?= $ordem["complementos"]?>
    </textarea>
    <p class="bold" style="padding-top: 10px;">Data Entrada Equipamento:</p>
    <input type="text" class="form-control" disabled  value="<?= $ordem["data_abertura"]?>">
    <p class="bold" style="padding-top: 10px;">Pré diagnóstico:</p>
    <textarea type="text" class="form-control" disabled  >
        <?= $ordem["pre_diagnostico"]?>
    </textarea>
    <p class="bold" style="padding-top: 10px;">Observação:</p>
    <textarea type="text" class="form-control" disabled  >
        <?= $ordem["observacao"]?>
    </textarea>
</div>


<div style="height: 50px;"></div>

<?php
    include_once("templates/header.php");
?>