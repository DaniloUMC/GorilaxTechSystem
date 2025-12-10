<?php
    session_start();
    include_once("../config/connection.php");
    include_once("../config/email.php");
    include_once("../config/url.php");


    $data = $_POST;
    $termo = $_GET['term'] ?? '';

    if (!empty($_POST["type"]) && $_POST["type"] === "pesquisa_inteligente_produto") {
        include_once("../controllers/ProdutoController.php");
        listarProdutosPorBusca($_POST["busca"], $BASE_URL);
    }

    else {
        include_once(__DIR__ . '/../controllers/pesquisaGeralController.php');
        $dados = listarTudo($conn); // função do controller
        $contatos = $dados['contatos'];
        $produtos = $dados['produtos'];
        $ordem_servicos = $dados['ordem_servicos'];
    }
        
     
    if(!empty($data)){  

        
        if($data["type"] === "pesquisa_inteligente") {
            $idCliente = $data['pesquisa_inteligente'];

            $_SESSION['pesquisa_inteligente'] = $idCliente;

            header("Location: " . $BASE_URL . "../clientes_listar.php");
            exit;
        }

       if($data["type"] === "entregue") {
            include_once("../controllers/UtilController.php");
            definirEntregueController($conn, $data, $BASE_URL);
        }

        if($data["type"] === "joguinho"){        
            header("location:" .$BASE_URL. "../play.php");

        }

        if($data["type"] === "confirmar_exclusao") {
            include_once("../controllers/ClienteController.php");
            confirmarExclusaoController($conn, $data);
        }


        if($data["type"] === "edit") {
            include_once("../controllers/ClienteController.php");
            editarClienteController($conn, $data, $BASE_URL);
        }

        if($data["type"] === "solicita_exclusao") {
            include_once("../controllers/ClienteController.php");
            solicitarExclusaoController($conn, $data, $BASE_URL);
        }

        if($data["type"] === "create"){
            
                include_once("../controllers/ClienteController.php"); 
                criarClienteController($conn, $data, $BASE_URL); 
        }

        if($data["type"] === "delete") {
            include_once("../controllers/ClienteController.php");
            deletarClienteController($conn, $data, $BASE_URL);
        }

        if($data["type"] === "confirmar_orcamento") {
            include_once("../controllers/UtilController.php");
            confirmarOrcamentoController($conn, $data);
        }

        if($data["type"] === "enviar_servico") {
            include_once("../controllers/UtilController.php");
            enviarServicoController($conn, $data, $BASE_URL);
        }    

        if (!empty($data["type"]) && $data["type"] === "create_orcamento") {
            include_once("../controllers/UtilController.php");
            createOrcamentoController($conn, $data, $BASE_URL);
        }

        if($data["type"] === "create_ordem_servico") {
            include_once("../controllers/OrdemServicoController.php");
            criarOrdemServicoController($conn, $data, $BASE_URL);
        }

        if($data["type"] === "edit_ordem") {
            include_once("../controllers/OrdemServicoController.php");
            editarOrdemServicoController($conn, $data, $BASE_URL);
        }

        if($data["type"] === "delete_ordem_servico") {
            include_once("../controllers/OrdemServicoController.php");
            deletarOrdemServicoController($conn, $data, $BASE_URL);
        }


        if($data["type"] === "create_prod") {
            include_once("../controllers/ProdutoController.php");
            criarProdutoController($conn, $data, $BASE_URL);
        }

        if($data["type"] === "edit_prod") {
            include_once("../controllers/ProdutoController.php");
            editarProdutoController($conn, $data, $BASE_URL);
        }

        if($data["type"] === "delete_prod") {
             include_once("../controllers/ProdutoController.php");
            deletarProdutoController($conn, $data, $BASE_URL);
        }

        if($data["type"] === "login") {
            include_once("../controllers/UtilController.php");
            loginController($data, $BASE_URL);
        }
 
        else if (!empty($data["type"]) && $data["type"] === "alterar_senha") {
            include_once("../controllers/UtilController.php");
            alterarSenhaController($conn, $data["email"], $data["frase"], $data["senha"], $BASE_URL);
        }
    }
    
    if ($termo !== '') {
            
        if (preg_match('/\d{5,}/', $termo)) {
            $stmt = $conn->prepare("SELECT id, nome, cpf FROM contatos WHERE cpf LIKE :termo LIMIT 10");
        } else {
            // Caso contrário, busca por nome
            $stmt = $conn->prepare("SELECT id, nome, cpf FROM contatos WHERE nome LIKE :termo LIMIT 10");
        }

        $stmt->bindValue(':termo', "%$termo%");
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($resultados);
    }   
     else{
        if(!empty($_GET["id"])){
     
            $query = "SELECT * FROM contatos WHERE id = :id";
            $queryprod = "SELECT * FROM produtos WHERE id = :id";
            $queryordem = "SELECT * FROM ordem_servico WHERE id = :id";

            $stmt = $conn->prepare($query);
            $stmtprod = $conn->prepare($queryprod);
            $stmtordem = $conn->prepare($queryordem);

            $id = $_GET["id"];
            $stmt->bindParam(":id", $id);
            $stmtprod->bindParam(":id", $id);
            $stmtordem->bindParam(":id", $id);
            $stmt->execute();
            $stmtprod->execute();
            $stmtordem->execute();

            $contato = $stmt->fetch();
            $produto = $stmtprod->fetch();
            $ordem = $stmtordem->fetch();

            $querycliente = "SELECT * FROM contatos WHERE id = :id_cliente";
            $stmtbuscarcliente = $conn->prepare($querycliente);
            $stmtbuscarcliente->bindParam(":id_cliente", $ordem["id_cliente"]);
            $stmtbuscarcliente->execute();
            $cliente = $stmtbuscarcliente->fetch();

        }
       
     }
    
    $conn = null;
    
?>
