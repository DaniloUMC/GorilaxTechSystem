<?php
// controllers/OrdemServicoController.php

include_once(__DIR__ . '/../config/connection.php'); // Conexão PDO
include_once(__DIR__ . '/../DAO/OrdemServicoDAO.php'); // DAO

$ordemDAO = new OrdemServicoDAO($conn);

function criarOrdemServicoController($conn, $data, $BASE_URL) {
    global $ordemDAO;
    try {
        $ordemDAO->criarOrdemServico($data);
        $_SESSION["msg"] = "Ordem de Serviço cadastrada com sucesso!";
        header("location:" . $BASE_URL . "../views/ordem_servico_listar.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao cadastrar ordem de serviço: " . $e->getMessage();
        exit;
    }
}

function editarOrdemServicoController($conn, $data, $BASE_URL) {
    global $ordemDAO;
    try {
        $ordemDAO->editarOrdemServico($data);
        $_SESSION["msg"] = "Ordem de Serviço atualizada com sucesso!";
        header("location:" . $BASE_URL . "../views/ordem_servico_listar.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao atualizar a ordem de serviço: " . $e->getMessage();
        exit;
    }
}

function deletarOrdemServicoController($conn, $data, $BASE_URL) {
    global $ordemDAO;
    try {
        $ordemDAO->deletarOrdemServico($data["id"]);
        $_SESSION["msg"] = "Ordem de Serviço removida com sucesso!";
        header("location:" . $BASE_URL . "../views/ordem_servico_listar.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao remover Ordem de Serviço: " . $e->getMessage();
        exit;
    }
}
