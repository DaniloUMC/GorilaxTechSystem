<?php

function criarOrdemServicoController($ordemDAO, $data) {
    try {
        $ordemDAO->criarOrdemServico($data);
        return "ok";
    } catch (Exception $e) {
        return "erro";
    }
}

function editarOrdemServicoController($ordemDAO, $data) {
    try {
        $ordemDAO->editarOrdemServico($data);
        return "ok";
    } catch (Exception $e) {
        return "erro";
    }
}

function deletarOrdemServicoController($ordemDAO, $id) {
    try {
        $ordemDAO->deletarOrdemServico($id);
        return "ok";
    } catch (Exception $e) {
        return "erro";
    }
}
