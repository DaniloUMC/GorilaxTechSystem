<?php

function formatarRealBD($valor) {
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("R$", "", $valor);
    $valor = str_replace(" ", "", $valor);
    return number_format((float)$valor, 2, ".", "");
}

function criarProdutoController($produtoDAO, $data) {
    try {
        $data["preco_custo"] = formatarRealBD($data["preco_custo"]);
        $data["preco_final"] = formatarRealBD($data["preco_final"]);

        $produtoDAO->criarProduto($data);
        return "ok";
    } catch (Exception $e) {
        return "erro";
    }
}

function editarProdutoController($produtoDAO, $data) {
    try {
        $data["preco_custo"] = formatarRealBD($data["preco_custo"]);
        $data["preco_final"] = formatarRealBD($data["preco_final"]);

        $produtoDAO->editarProduto($data);
        return "ok";
    } catch (Exception $e) {
        return "erro";
    }
}

function deletarProdutoController($produtoDAO, $id) {
    try {
        $produtoDAO->deletarProduto($id);
        return "ok";
    } catch (Exception $e) {
        return "erro";
    }
}
