<?php
// controllers/ProdutoController.php

include_once(__DIR__ . '/../config/connection.php'); // conexão PDO
include_once(__DIR__ . '/../DAO/ProdutoDAO.php'); // DAO

$produtoDAO = new ProdutoDAO($conn);

function formatarRealBD($valor) {
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("R$", "", $valor);
    $valor = str_replace(" ", "", $valor);
    return number_format((float)$valor, 2, ".", "");
}

function criarProdutoController($conn, $data, $BASE_URL) {
    global $produtoDAO;
    $data["preco_custo"] = formatarRealBD($data["preco_custo"]);
    $data["preco_final"] = formatarRealBD($data["preco_final"]);

    try {
        $produtoDAO->criarProduto($data);
        $_SESSION["msg"] = "Produto cadastrado com sucesso!";
        header("location:" . $BASE_URL . "../views/produtos_listar.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao cadastrar produto: " . $e->getMessage();
        exit;
    }
}

function editarProdutoController($conn, $data, $BASE_URL) {
    global $produtoDAO;
    $data["preco_custo"] = formatarRealBD($data["preco_custo"]);
    $data["preco_final"] = formatarRealBD($data["preco_final"]);

    try {
        $produtoDAO->editarProduto($data);
        $_SESSION["msg"] = "Produto atualizado com sucesso!";
        header("location:" . $BASE_URL . "../views/produtos_listar.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao atualizar o produto: " . $e->getMessage();
        exit;
    }
}

function deletarProdutoController($conn, $data, $BASE_URL) {
    global $produtoDAO;
    try {
        $produtoDAO->deletarProduto($data["id"]);
        $_SESSION["msg"] = "Produto removido com sucesso!";
        header("location:" . $BASE_URL . "../views/produtos_listar.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao remover produto: " . $e->getMessage();
        exit;
    }
}

function listarProdutosPorBusca($busca, $BASE_URL) {
    global $produtoDAO;
    $produtos = $produtoDAO->listarProdutosPorBusca($busca);

    if ($produtos) {
        echo "<table class='table' id='contatos-table'>";
        echo "<thead>
                <tr>
                    <th style='text-align: center;' scope='col'>Nº</th>
                    <th style='text-align: center;' scope='col'>Nome</th>
                    <th style='text-align: center;' scope='col'>Tipo</th>
                    <th style='text-align: center;' scope='col'>Marca</th>
                    <th style='text-align: center;' scope='col'>Preço de Custo</th>
                    <th style='text-align: center;' scope='col'>Preço Final</th>
                    <th style='text-align: center;' scope='col'></th>
                </tr>
              </thead>
              <tbody>";
        foreach ($produtos as $p) {
            echo "<tr>
                    <td style='text-align: center;'>{$p['id']}</td>
                    <td style='text-align: center;'>".htmlspecialchars($p['nome'])."</td>
                    <td style='text-align: center;'>".htmlspecialchars($p['tipo'])."</td>
                    <td style='text-align: center;'>".htmlspecialchars($p['marca'])."</td>
                    <td style='text-align: center;'>R$ ".number_format($p['preco_custo'], 2, ',', '.')."</td>
                    <td style='text-align: center;'>R$ ".number_format($p['preco_final'], 2, ',', '.')."</td>
                    <td style='text-align: center;' class='actions'>
                        <a title='Visualizar' href='".$BASE_URL."../show_produto.php?id=". $p['id'] ."'><i class='fas fa-eye check-icon'></i></a>
                        <a title='Editar' href='".  $BASE_URL . "../edit_produto.php?id=".$p['id'] ."'><i class='fas fa-edit edit-icon'></i></a>
                        <form class='delete-form' action='". $BASE_URL. "/config/process.php' method='POST'>
                            <input type='hidden' name='type' value='delete_prod'>
                            <input type='hidden' name='id' value='".$p["id"]."'/>
                            <button title='Excluir' type='submit' class='delete-btn'><i class='fas fa-trash delete-icon'></i></button>
                        </form>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Nenhum produto encontrado.</p>";
    }
    exit;
}
