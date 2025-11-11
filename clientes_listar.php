<?php
    include_once("templates/header.php");
    
?>


<div class="container">
 
    <?php if(isset($printMsg) && $printMsg != '') : ?>
        <p id="msg"><?= $printMsg?></p>
    <?php endif; ?>
    <h1 id="main-title">Clientes </h1>



    <nav  style="text-align: end;">
        <div tyle="text-align: center;">
            <div class="navbar-nav" >
                <a class="nav-link active " href="<?= $BASE_URL ?>create.php"> <b style="font-weight: bold;">Adicionar</b> <i class="fas fa-plus start-icon"></i></a>
            </div>
        </div>
   
        
    </nav>
     <div style="border-bottom: none;">
            <input style="width: 100%; " class="form-control" type="text" id="search" oninput="buscarCliente(this.value)" placeholder="Digite o nome do cliente ou CPF...">
            <div  class="container"  style="border: none; padding-left: opx; padding-right: 27px;" id="suggestions"></div>
    </div>
    <div style="height: 20px;" ></div>
    <?php if(count($contatos)> 0): ?>
        <table class="table" id="contatos-table">
            <thead>
                <tr>
                    <th style="text-align: center;" scope="col">Nº</th>
                    <th style="text-align: center;" scope="col">Nome</th>
                    <th style="text-align: center;" scope="col">Email</th>
                    <th style="text-align: center;" scope="col">CPF</th>
                    <th style="text-align: center;" scope="col">Telefone</th>
                    <th  scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($contatos as $contato):?>
                    <tr>
                        <?php $idFormatado = str_pad($contato['id'], 6, '0', STR_PAD_LEFT); ?>
                        <td style="text-align: center;" class="col-id"><?= $idFormatado?></td>
                        <td style="text-align: center;"><?= $contato['nome']?></td>
                        <td style="text-align: center;"><?= $contato['email']?></td>
                        <?php
                            $cpf = preg_replace('/\D/', '', $contato['cpf']); // remove tudo que não for número
                            $telefone = preg_replace('/\D/', '', $contato['telefone']); // Remove tudo que não é número

                            if (strlen($cpf) === 11) {
                                $cpf = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
                            }
                            if (strlen($telefone) === 11) {
                                // Celular: (xx) xxxxx-xxxx
                                $telefone = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
                            } elseif (strlen($telefone) === 10) {
                                // Telefone fixo: (xx) xxxx-xxxx
                                $telefone = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
                            }

                           
                        ?>
                       
                        <td style="text-align: center;"><?= $cpf ?></td>
                        <td style="text-align: center;"><?= $telefone ?></td>
                        <td class="actions">
                            <a title="Abrir Ordem de Serviço" href="<?= $BASE_URL ?>create_ordem_servico.php?id=<?= $contato['id']?>"><i class="fa fa-address-book"></i></a> 
                            <a title="Visualizar" href="<?= $BASE_URL ?>show.php?id=<?= $contato['id']?>"><i class="fas fa-eye check-icon"></i></a> 
                            <a title="Editar" href="<?= $BASE_URL ?>edit.php?id=<?= $contato['id']?>"><i class="fas fa-edit edit-icon"></i></a> 

                            <form class="delete-form" action="<?= $BASE_URL ?>/config/process.php" method="POST">
                                <input type="hidden" name="type" value="delete">
                                <input type="hidden" name="id" value="<?= $contato["id"] ?>">
                                <button title="Deletar" type="submit" class="delete-btn"><i class="fas fa-trash delete-icon"></i></button>
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