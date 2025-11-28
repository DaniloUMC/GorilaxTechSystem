<?php
// Caminhos para templates
include_once(__DIR__ . '/../templates/headerl.php');
include_once(__DIR__ . '/../templates/navbar.php');
?>

<div class="container">
    <?php if(isset($printMsg) && $printMsg != '') : ?>
        <p id="msg"><?= $printMsg ?></p>
    <?php endif; ?>
    <a href="<?= $BASE_URL ?>produtos_listar.php"><h1 id="main-title">Clientes</h1></a> 

    <nav style="text-align: end;">
        <div style="text-align: center;">
            <div class="navbar-nav">
                <a class="nav-link active" href="<?= $BASE_URL ?>create.php">
                    <b>Adicionar</b> <i class="fas fa-plus start-icon"></i>
                </a>
            </div>
        </div>
    </nav>

    <div style="border-bottom: none;">
        <input style="width: 100%;" class="form-control" type="text" id="search" oninput="buscarCliente(this.value)" placeholder="Digite o nome do cliente ou CPF...">
        <div class="container" style="border: none; padding-left: 0; padding-right: 27px;" id="suggestions"></div>
    </div>

    <div style="height: 20px;"></div>

    <?php if(count($contatos) > 0): ?>
        <table class="table" id="contatos-table">
            <thead>
                <tr>
                    <th style="text-align: center;">Nº</th>
                    <th style="text-align: center;">Nome</th>
                    <th style="text-align: center;">Email</th>
                    <th style="text-align: center;">CPF</th>
                    <th style="text-align: center;">Telefone</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($contatos as $contato): ?>
                    <tr>
                        <?php $idFormatado = str_pad($contato['id'], 6, '0', STR_PAD_LEFT); ?>
                        <td style="text-align: center;"><?= $idFormatado ?></td>
                        <td style="text-align: center;"><?= $contato['nome'] ?></td>
                        <td style="text-align: center;"><?= $contato['email'] ?></td>
                        <?php
                            include_once(__DIR__ . '/../controllers/HtmlController.php');

                            $cpf = formatarCPF($contato['cpf']);
                            $telefone = formatarTelefone($contato['telefone']);
                        ?>
                        <td style="text-align: center;"><?= $cpf ?></td>
                        <td style="text-align: center;"><?= $telefone ?></td>
                        <td class="actions">
                            <a title="Abrir Ordem de Serviço" href="<?= $BASE_URL ?>create_ordem_servico.php?id=<?= $contato['id'] ?>"><i class="fa fa-address-book"></i></a> 
                            <a title="Visualizar" href="<?= $BASE_URL ?>show.php?id=<?= $contato['id'] ?>"><i class="fas fa-eye check-icon"></i></a> 
                            <a title="Editar" href="<?= $BASE_URL ?>edit.php?id=<?= $contato['id'] ?>"><i class="fas fa-edit edit-icon"></i></a> 

                            <form class="delete-form" action="<?= $BASE_URL ?>../config/process.php" method="POST">
                                <input type="hidden" name="type" value="delete">
                                <input type="hidden" name="id" value="<?= $contato["id"] ?>">
                                <button title="Deletar" type="submit" class="delete-btn"><i class="fas fa-trash delete-icon"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p id="empty-list-text">Ainda não há Cadastros, <a href="<?= $BASE_URL ?>create.php">Clique aqui para adicionar</a>.</p>
    <?php endif; ?>
</div>

<?php
include_once(__DIR__ . '/../templates/footer.php');
?>
