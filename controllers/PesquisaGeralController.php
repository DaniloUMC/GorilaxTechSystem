<?php


function listarTudo($conn) {
    $filtroStatus = isset($_GET['status_atual']) ? $_GET['status_atual'] : '';
    $filtroData = isset($_GET['data_abertura']) ? $_GET['data_abertura'] : '';
    // sem filtro — lista tudo
    $queryprod  = "SELECT * FROM produtos ORDER BY id DESC"; 
    $query      = "SELECT * FROM contatos ORDER BY id DESC"; 
    
    $queryordem = "SELECT * FROM ordem_servico WHERE 1=1  ";       
    
    
    if (!empty($filtroStatus)) {
        $queryordem .= " AND status_atual = :status_atual ";
    }

    if (!empty($filtroData)) {
        $queryordem .= " AND data_abertura = :data_abertura ";
    }
    

    $queryordem .= " ORDER BY id DESC";

 

    $stmt = $conn->prepare($query);
    $stmtprod = $conn->prepare($queryprod);


    $stmtordem = $conn->prepare($queryordem);
      if (!empty($filtroStatus)) {
        $stmtordem->bindParam(":status_atual", $filtroStatus);
    }

    if (!empty($filtroData)) {
        $stmtordem->bindParam(":data_abertura", $filtroData);
    }

    $stmt->execute();
    $stmtprod->execute();


    $stmtordem->execute();

    $contatos = $stmt->fetchAll();
    $produtos = $stmtprod->fetchAll();
    
    $ordem_servicos = $stmtordem->fetchAll();
    foreach ($ordem_servicos as &$ordem_servico) {
        $id_cliente = $ordem_servico["id_cliente"];

        // Consulta para buscar o nome do cliente
        $query = "SELECT nome FROM contatos WHERE id = :id_cliente";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Adiciona o nome do cliente ao array da OS
        if ($resultado) {
            $ordem_servico["nome_cliente"] = $resultado["nome"];
        } else {
            $ordem_servico["nome_cliente"] = "Cliente não encontrado";
        }
    }

    // Retorna todos os dados em um array associativo
    return [
        'contatos' => $contatos,
        'produtos' => $produtos,
        'ordem_servicos' => $ordem_servicos
    ];
}

function pesquisaGeralController($conn, $id) {
    $resultado = [
        "contato" => null,
        "produto" => null,
        "ordem" => null,
        "cliente" => null
    ];

    // ======== Lógica que estava dentro do if(!empty($id)) ========
    // Busca contato, produto e ordem pelo id
    $query = "SELECT * FROM contatos WHERE id = :id";
    $queryprod = "SELECT * FROM produtos WHERE id = :id";
    $queryordem = "SELECT * FROM ordem_servico WHERE id = :id";

    $stmt = $conn->prepare($query);
    $stmtprod = $conn->prepare($queryprod);
    $stmtordem = $conn->prepare($queryordem);

    $stmt->bindParam(":id", $id);
    $stmtprod->bindParam(":id", $id);
    $stmtordem->bindParam(":id", $id);

    $stmt->execute();
    $stmtprod->execute();
    $stmtordem->execute();

    $resultado["contato"] = $stmt->fetch(PDO::FETCH_ASSOC);
    $resultado["produto"] = $stmtprod->fetch(PDO::FETCH_ASSOC);
    $resultado["ordem"] = $stmtordem->fetch(PDO::FETCH_ASSOC);

    // Busca o cliente relacionado à ordem, se existir
    if(!empty($resultado["ordem"]["id_cliente"])) {
        $querycliente = "SELECT * FROM contatos WHERE id = :id_cliente";
        $stmtbuscarcliente = $conn->prepare($querycliente);
        $stmtbuscarcliente->bindParam(":id_cliente", $resultado["ordem"]["id_cliente"]);
        $stmtbuscarcliente->execute();
        $resultado["cliente"] = $stmtbuscarcliente->fetch(PDO::FETCH_ASSOC);
    }

    return $resultado;
}
