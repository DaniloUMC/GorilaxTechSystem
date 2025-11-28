<?php
// controllers/ClienteController.php

include_once(__DIR__ . '/../config/connection.php'); // conexão PDO
include_once(__DIR__ . '/../DAO/ClienteDAO.php'); // DAO



$clienteDAO = new ClienteDAO($conn);

function limparCaracteresEspeciais($valor) {
    return preg_replace("/[.\/\-]/", "", $valor);
}
function formatarRealBD($valor) {
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("R$", "", $valor); 
    $valor = str_replace(" ", "", $valor);// substitui vírgula por ponto
    $valor = number_format((float)$valor, 2, ".", ""); // 2 casas decimais
        
    return $valor;
}
function criarClienteController($conn, $data, $BASE_URL) {
    global $clienteDAO;
    
    $data = array_map('trim', $data);

    try {
        $clienteDAO->criarCliente($data);
        $_SESSION["msg"] = "Cliente cadastrado com sucesso!";
       
        header("Location: " . $BASE_URL . "../views/clientes_listar.php");
        exit;
        
    } catch (PDOException $e) {
        echo "Erro ao cadastrar cliente: " . $e->getMessage();
        exit;
    }

}

// =====================
// Função para editar cliente
// =====================
function editarClienteController($conn, $data, $BASE_URL) {
    global $clienteDAO;

    $data["telefone"] = limparCaracteresEspeciais($data["telefone"]);
    $data["cpf"] = limparCaracteresEspeciais($data["cpf"]);
    $data["cnpj"] = limparCaracteresEspeciais($data["cnpj"]);
    $data["cep"] = limparCaracteresEspeciais($data["cep"]);

    try {
        $clienteDAO->editarCliente($data);
        $_SESSION["msg"] = "Cadastro atualizado com sucesso!";
        header("Location:" . $BASE_URL . "../views/clientes_listar.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro: CPF ou Email já existe em nosso sistema.";
        exit;
    }
}

// =====================
// Função para deletar cliente
// =====================
function deletarClienteController($conn, $data, $BASE_URL) {
    global $clienteDAO;
    $id = $data["id"];

    $ordens = $clienteDAO->buscarOrdensPorCliente($id);
    if ($ordens) {
        $clienteDAO->reatribuirOrdens($id, 1);
    }

    try {
        $clienteDAO->deletarCliente($id);
        $_SESSION["msg"] = "Cadastro removido com sucesso!";
        header("Location:" . $BASE_URL . "../views/clientes_listar.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao remover cliente: " . $e->getMessage();
        exit;
    }
}

// =====================
// Função para confirmar exclusão
// =====================
function confirmarExclusaoController($conn, $data) {
  
    $clienteDAO = new ClienteDAO($conn);

    $cpf = limparCaracteresEspeciais($data["cpf"]);
    $contato = $clienteDAO->buscarClientePorCpf($cpf);

    if (!$contato) {
        echo "Cliente não encontrado.";
        exit();
    }

    $corpo = "<h2>Exclusão</h2>
              <p><strong>{$contato["nome"]}</strong></p>
              <p><strong>A partir deste momento, seus dados foram excluídos da nossa base.</strong></p>";

    $ordens = $clienteDAO->buscarOrdensPorCliente($contato["id"]);
    if ($ordens) {
        $clienteDAO->reatribuirOrdens($contato["id"], 1);
    }

    $clienteDAO->deletarCliente($contato["id"]);
    enviarEmail($contato["email"], $contato["nome"], $corpo);

    header("location:https://media.tenor.com/buPx8dUsXH8AAAAM/jake-gyllenhaal-bye-bye.gif");
    exit();
}

// =====================
// Função para solicitar exclusão
// =====================
function solicitarExclusaoController($conn, $data, $BASE_URL) {
    global $clienteDAO;

    $cpf = limparCaracteresEspeciais($data["cpf"]);
    $contato = $clienteDAO->buscarClientePorCpf($cpf);

    if (!$contato) {
        echo "Cliente não encontrado para exclusão.";
        exit();
    }

    $corpo = "<h2>Solicitação de exclusão</h2>
              <p><strong>Cliente:</strong> {$contato["nome"]}</p>
              <p><strong>CPF:</strong> {$cpf}</p>
              <p><strong>Foi solicitado a exclusão dos seus dados. Para prosseguir, acesse o seguinte link:</p>
              <p><a href='{$BASE_URL}../views/confirmar_exclusao_bd.php?id={$contato["id"]}' target='_blank'>
              {$BASE_URL}../views/confirmar_exclusao_bd.php?id={$contato["id"]}</a></p>
              <p>Agradecemos a confiança!</p>";

    enviarEmail($contato["email"], $contato["nome"], $corpo);
    header("location:" . $BASE_URL . "../index.php");
    exit();
}
