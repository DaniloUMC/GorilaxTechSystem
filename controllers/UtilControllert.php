<?php
// =======================================
// controllers/UtilController.php
// =======================================

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../DAO/UtilDAO.php';
require_once __DIR__ . '/../config/email.php';

// Instância do DAO
$utilDAO = new UtilDAO($conn);

/**
 * Helper: Detecta ambiente de teste para evitar header() no PHPUnit
 */
function isTestEnvironment() {
    return defined('PHPUNIT_RUNNING') && PHPUNIT_RUNNING === true;
}

/**
 * Helper: Redirecionamento seguro (ignora durante teste)
 */
function safeRedirect($url) {
    if (!isTestEnvironment()) {
        header("Location: " . $url);
        exit();
    }
    return $url; // útil para asserts no PHPUnit
}

// =======================
// LOGIN
// =======================
function loginController($data, $BASE_URL) {
    global $utilDAO;

    $email = $data["email"];
    $senha = $data["senha"];

    $usuario = $utilDAO->login($email);

    if ($usuario && $senha === $usuario["senha"]) {
        $_SESSION["usuario"] = $usuario["nome"];
        return safeRedirect($BASE_URL . "../views/clientes_listar.php");
    }

    return "❌ Senha ou email incorretos.";
}



// =======================
// ENVIAR SERVIÇO
// =======================
function enviarServicoController($utilDAO, $data, $BASE_URL) {

    $id = $data["id"];

    $ordem = $utilDAO->buscarIdClienteOrdem($id);
    $id_cliente = $ordem["id_cliente"];

    $contato = $utilDAO->buscarContato($id_cliente);

    $corpo = "
        <h2>Equipamento na Bancada</h2>
        <p><strong>Cliente:</strong> {$contato['nome']}</p>
        <p>Seu equipamento está na bancada. Acompanhe ao vivo:</p>
        <p>
            <a href='https://www.twitch.tv/gorilaxtech' target='_blank'>
                https://www.twitch.tv/gorilaxtech
            </a>
        </p>
    ";

    enviarEmail($contato["email"], $contato["nome"], $corpo);

    $utilDAO->atualizarStatusOrdem($id, "Finalizado");

    $_SESSION["msg"] = "Link criado e enviado com sucesso!";
    return safeRedirect($BASE_URL . "../ordem_servico_listar.php");
}



// =======================
// DEFINIR ENTREGUE
// =======================
function definirEntregueController($utilDAO, $data, $BASE_URL) {

    if ($data["type"] === "entregue") {
        $utilDAO->definirEntregue($data["id"]);

        $_SESSION["msg"] = "Atualizado como entregue!";
        return safeRedirect($BASE_URL . "../ordem_servico_listar.php");
    }

    return "❌ Tipo inválido.";
}



// =======================
// ALTERAR SENHA
// =======================
function alterarSenhaController($utilDAO, $email, $frase, $novaSenha, $BASE_URL) {

    $frase_correta = "GorilaxTech10";

    if (trim(strtolower($frase)) !== strtolower($frase_correta)) {
        return "❌ Frase de validação incorreta.";
    }

    $usuario = $utilDAO->buscarUsuarioPorEmail($email);

    if ($usuario) {
        $utilDAO->atualizarSenha($email, $novaSenha);
        return safeRedirect($BASE_URL . "../index.php");
    }

    return "❌ Usuário não encontrado.";
}



// =======================
// CONFIRMAR ORÇAMENTO
// =======================
function confirmarOrcamentoController($utilDAO, $data) {
    $id = $data["id"];
    $utilDAO->confirmarOrcamento($id);

    return safeRedirect("https://i.pinimg.com/originals/3d/92/cf/3d92cf4be9b5fe218db9d64fc9f85ec0.gif");
}



// =======================
// CRIAR ORÇAMENTO
// =======================
function createOrcamentoController($utilDAO, $data, $BASE_URL) {

    $total = 0;

    // ========================
    // somar produtos enviados
    // ========================
    if (!empty($_POST['produtos_string'])) {
        $produtos = explode(',', $_POST['produtos_string']);
        
        foreach ($produtos as $item) {
            list($id_prod, $qtd) = explode(':', $item);

            $produto = $utilDAO->buscarProduto($id_prod);

            if ($produto) {
                $preco = floatval($produto['preco_final']);
                $total += $preco * intval($qtd);
            }
        }
    }

    $id = $data["id"];
    $observacao = $data["observacao"];
    $status_atual = "Orçamento";
    $produtos_servicos = $_POST['produtos_string'] ?? '';
    $data_orcamento = $data["data_orcamento"];

    // atualiza no BD
    $utilDAO->atualizarOrcamento(
        $id,
        $total,
        $produtos_servicos,
        $status_atual,
        $observacao,
        $data_orcamento
    );

    // =======================
    // envio do email
    // =======================
    $ordem = $utilDAO->buscarOrdemPorId($id);
    $contato = $utilDAO->buscarContato($ordem["id_cliente"]);

    $corpo = "<h2>Resumo do Orçamento</h2>
        <p><strong>Cliente:</strong> {$contato['nome']}</p>
        <p><strong>Produto:</strong> {$ordem['equipamento_cliente']}</p>
        <p><strong>Diagnóstico:</strong> {$ordem['observacao']}</p>";

    // lista de produtos
    if (!empty($produtos_servicos)) {
        $produtos = explode(',', $produtos_servicos);
        $corpo .= "<h3>Itens:</h3><ul>";

        foreach ($produtos as $item) {
            list($id_prod, $qtd) = explode(':', $item);
            $produto = $utilDAO->buscarProduto($id_prod);

            if ($produto) {
                $preco = floatval($produto['preco_final']);
                $subtotal = $preco * intval($qtd);

                $corpo .= "<li>{$produto['nome']} — Qtd: $qtd — R$ ".number_format($preco,2,',','.') ." — Subtotal: R$ ".number_format($subtotal,2,',','.')."</li>";
            }
        }

        $corpo .= "</ul>";
    }

    $corpo .= "<p><strong>Total:</strong> R$ ".number_format($total,2,',','.')."</p>";

    enviarEmail($contato["email"], $contato["nome"], $corpo);

    $_SESSION["msg"] = "Orçamento criado com sucesso!";
    return safeRedirect($BASE_URL . "../views/ordem_servico_listar.php");
}
