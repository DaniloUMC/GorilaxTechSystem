<?php
// controllers/UtilController.php

require_once __DIR__ . '/../DAO/UtilDAO.php';

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../config/email.php';

$utilDAO = new UtilDAO($conn);

// =======================
// Login
// =======================
function loginController($data, $BASE_URL) {
    global $utilDAO;

    $email = $data["email"];
    $senha = $data["senha"];
 
    // Busca usuário pelo email
    $usuario = $utilDAO->login($email);


    // Se encontrou o usuário e a senha confere com o hash
    if ($usuario && password_verify($senha, $usuario["senha"])) {

        // Guarda informações na sessão
        $_SESSION["usuario"] = $usuario["nome"];

        header("Location: " . $BASE_URL . "../views/clientes_listar.php");
        exit();

    } else {
        echo "❌ Senha ou email incorretos.";
        exit();
    }
}


// =======================
// Enviar Serviço
// =======================
function enviarServicoController($utilDAO, $data, $BASE_URL) {
    $id = $data["id"];
    $utilDAO = new UtilDAO($utilDAO);
    $ordem = $utilDAO->buscarIdClienteOrdem($id);
    $id_cliente = $ordem["id_cliente"];

    $contato = $utilDAO->buscarContato($id_cliente);

    $corpo = "
        <h2>Equipamento Bancada</h2>
        <p><strong>Cliente:</strong> " . $contato["nome"] . "</p>
        <p><strong>Seu equipamento se encontra na bancada, segue link para acesso:</strong></p>
        <p>
            <a href='https://www.twitch.tv/gorilaxtech' target='_blank'>
                https://www.twitch.tv/gorilaxtech
            </a>
        </p>
    ";

    enviarEmail($contato["email"], $contato["nome"], $corpo);

    $utilDAO->atualizarStatusOrdem($id, "Finalizado");

    $_SESSION["msg"] = "Link criado e enviado com sucesso!";
    header("location:" . $BASE_URL . "../views/ordem_servico_listar.php");
    exit();
}

// =======================
// Definir Entregue
// =======================
function definirEntregueController($utilDAO, $data, $BASE_URL) {
    if ($data["type"] === "entregue") {

        $utilDAO = new UtilDAO($utilDAO);
        $id = $data["id"];
        $utilDAO->definirEntregue($id);

        $_SESSION["msg"] = "Atualizado como entregue!";
        header("location:" . $BASE_URL . "../views/ordem_servico_listar.php");
        exit();
    }
}

// =======================
// Alterar Senha
// =======================
function alterarSenhaController($utilDAO, $email, $frase, $novaSenha, $BASE_URL) {
    $frase_correta = "GorilaxTech10";
    $utilDAO = new UtilDAO($utilDAO);
    if (trim(strtolower($frase)) !== strtolower($frase_correta)) {
        echo "❌ Frase de validação incorreta.";
        exit;
    }

    $usuario = $utilDAO->buscarUsuarioPorEmail($email);

    if ($usuario) {
        $utilDAO->atualizarSenha($email, $novaSenha);
        header("location:" . $BASE_URL . "../index.php");
        exit;
    } else {
        echo "❌ Usuário não encontrado.";
        exit;
    }
}

// =======================
// Confirmar Orçamento
// =======================
function confirmarOrcamentoController($utilDAO, $data) {
    $utilDAO = new UtilDAO($utilDAO);
    $id = $data["id"];
    $utilDAO->confirmarOrcamento($id);

    header("location:https://i.pinimg.com/originals/3d/92/cf/3d92cf4be9b5fe218db9d64fc9f85ec0.gif");
    exit(); 
}

// =======================
// Criar Orçamento
// =======================
function createOrcamentoController($utilDAO, $data, $BASE_URL) {
    $total = 0;
    $utilDAO = new UtilDAO($utilDAO);
    if (isset($_POST['produtos_string']) && !empty($_POST['produtos_string'])) {
        $produtos = explode(',', $_POST['produtos_string']);
   
        

        foreach ($produtos as $item) {
         
        

            list($id_prod, $qtd) = explode(':', $item);
            $produto = $utilDAO->buscarProduto($id_prod);

            if ($produto) {
                $preco = floatval($produto['preco_final']);
                $subtotal = $preco * intval($qtd);
                $total += $subtotal;
            }
        }
    }

    $id = $data["id"];
    $observacao = $data["observacao"];
    $status_atual = "Orçamento";
    $produtos_servicos = $_POST['produtos_string'] ?? '';
    $data_orcamento = $data["data_orcamento"];

    $utilDAO->atualizarOrcamento(
        $id,
        $total,
        $produtos_servicos,
        $status_atual,
        $observacao,
        $data_orcamento
    );

    // Buscar dados atualizados da ordem e do cliente
    $ordem = $utilDAO->buscarOrdemPorId($id);
    $contato = $utilDAO->buscarContato($ordem["id_cliente"]);

    // Montar corpo do email
    $corpo = "<h2>Resumo do Orçamento</h2>
        <p><strong>Cliente:</strong> {$contato['nome']}</p>
        <p><strong>Produto principal:</strong> {$ordem['equipamento_cliente']}</p>
        <p><strong>Diagnóstico:</strong> {$ordem['observacao']}</p>";

    $totalGeral = 0;
    if (!empty($produtos_servicos)) {
        $produtos = explode(',', $produtos_servicos);
        $corpo .= "<h3>Itens do Orçamento:</h3><ul>";

        foreach ($produtos as $item) {
            list($id_prod, $qtd) = explode(':', $item);
            $produto = $utilDAO->buscarProduto($id_prod);

            if ($produto) {
                $nomeProduto = $produto['nome'];
                $preco = floatval($produto['preco_final']);
                $subtotal = $preco * intval($qtd);
                $totalGeral += $subtotal;

                $corpo .= "<li>$nomeProduto — Quantidade: $qtd — Valor unitário: R$ " . number_format($preco, 2, ',', '.') . " — Subtotal: R$ " . number_format($subtotal, 2, ',', '.') . "</li>";
            } else {
                $corpo .= "<li>Produto não encontrado (ID $id_prod)</li>";
            }
        }
        $corpo .= "</ul>";
    }

    $corpo .= "<p><strong>Total:</strong> R$ " . number_format($totalGeral, 2, ',', '.') . "</p>
               <p><strong>Para pré-autorizar o seu orçamento, clique no link abaixo:</strong></p>
               <p><a href='{$BASE_URL}../views/confirmar_orcamento.php?id={$ordem['id']}' target='_blank'>
                   {$BASE_URL}../views/confirmar_orcamento.php?id={$ordem['id']}
               </a></p>";

    enviarEmail($contato["email"], $contato["nome"], $corpo);

    $_SESSION["msg"] = "Ordem de Serviço atualizada para orçamento com sucesso!";
    header("location:" . $BASE_URL ."../views/ordem_servico_listar.php");
    exit;
}
