<?php
include_once("config/connection.php");

// Busca todos os produtos
$stmt = $conn->prepare("SELECT * FROM produtos ORDER BY id DESC");
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (count($produtos) > 0): ?>
    <table class="table" id="contatos-table">
        <thead>
            <tr>
                <th style="text-align: center;" scope="col">Nº</th>
                <th style="text-align: center;" scope="col">Nome</th>
                <th style="text-align: center;" scope="col">Tipo</th>
                <th style="text-align: center;" scope="col">Marca</th>
                <th style="text-align: center;" scope="col">Preço de Custo</th>
                <th style="text-align: center;" scope="col">Preço Final</th>
                <th style="text-align: center;" scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($produtos as $produto): ?>
                <?php $idFormatado = str_pad($produto['id'], 6, '0', STR_PAD_LEFT); ?>
                <tr>
                    <td style="text-align: center;" class="col-id"><?= $idFormatado ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($produto['nome']) ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($produto['tipo']) ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($produto['marca']) ?></td>
                    <td style="text-align: center;">R$ <?= number_format($produto['preco_custo'], 2, ",", ".") ?></td>
                    <td style="text-align: center;">R$ <?= number_format($produto['preco_final'], 2, ",", ".") ?></td>
                    <td style="text-align: center;" class="actions">
                        <a title="Visualizar" href="show_produto.php?id=<?= $produto['id'] ?>"><i class="fas fa-eye check-icon"></i></a>
                        <a title="Editar" href="edit_produto.php?id=<?= $produto['id'] ?>"><i class="fas fa-edit edit-icon"></i></a>
                        <form class="delete-form" action="config/process.php" method="POST">
                            <input type="hidden" name="type" value="delete_prod">
                            <input type="hidden" name="id" value="<?= $produto["id"] ?>">
                            <button title="Excluir" type="submit" class="delete-btn"><i class="fas fa-trash delete-icon"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p id="empty-list-text">Ainda não há Cadastros, 
        <a href="create_produto.php">Clique aqui para adicionar</a>.
    </p>
<?php endif; ?>
