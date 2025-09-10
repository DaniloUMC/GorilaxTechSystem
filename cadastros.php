<?php
    include_once("templates/header.php");
    
?>
<div class="container-dash">

    <div class="col-dash">Total de Cadastros: <?= count($contatos)?></div>

    <div class="col-dash">Quantidade Total de Impressões: <?= $somaImpressoes['soma_numeros']?> </div>


   

</div>

<div class="container">

    <?php if(isset($printMsg) && $printMsg != '') : ?>
        <p id="msg"><?= $printMsg?></p>
    <?php endif; ?>
    <h1 id="main-title">Dados Clientes GorilaxTech</h1>



    <nav  style="text-align: end;">
        <div tyle="text-align: center;">
            <div class="navbar-nav" >
                <a class="nav-link active " href="<?= $BASE_URL ?>create.php">Novo</a>
            </div>
        </div>
        
    </nav>

    <div style="height: 20px;" ></div>
    <?php if(count($contatos)> 0): ?>
        <table class="table" id="contatos-table">
            <thead>
                <tr>
                    <th scope="col">Nº</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Telefone</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($contatos as $contato):?>
                    <tr>
                        <?php $idFormatado = str_pad($contato['id'], 6, '0', STR_PAD_LEFT); ?>
                        <td class="col-id"><?= $idFormatado?></td>
                        <td><?= $contato['nome']?></td>
                        <td><?= $contato['telefone']?></td>
                        <td class="actions">
                            <a href="<?= $BASE_URL ?>show.php?id=<?= $contato['id']?>"><i class="fas fa-eye check-icon"></i></a> 
                            <a href="<?= $BASE_URL ?>edit.php?id=<?= $contato['id']?>"><i class="fas fa-edit edit-icon"></i></a> 
                            
                            <form class="delete-form" action="<?= $BASE_URL ?>/config/process.php" method="POST">
                                <input type="hidden" name="type" value="delete">
                                <input type="hidden" name="id" value="<?= $contato["id"] ?>">
                                <button type="submit" class="delete-btn"><i class="fas fa-trash delete-icon"></i></button>
                            </form>
                        </td>
                    </tr>

                <?php endforeach;?>
            </tbody>
        </table>


    <?php else: ?>
        <p id="empty-list-text">Ainda não há Cadastros , <a href="<?= $BASE_URL ?>create.php">Clique aqui para adicionar</a>.</p>

    <?php endif; ?>
</div>

<?php
    include_once("templates/header.php");
?>