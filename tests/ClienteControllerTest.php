<?php
namespace Tests;
if (!defined('TEST_MODE')) {
    define('TEST_MODE', true);
}
use PHPUnit\Framework\TestCase;

/****************************************************
 * FAKE / MOCK PARA EVITAR QUALQUER CONEXÃO REAL
 ****************************************************/

// Fake da classe PDO para impedir chamada real ao driver
if (!class_exists('PDO')) {
    class PDO {}
}

// Variáveis de conexão fake (evitam erro em connection.php)
$GLOBALS['db_host'] = "FAKE_HOST";
$GLOBALS['db_user'] = "FAKE_USER";
$GLOBALS['db_pass'] = "FAKE_PASS";
$GLOBALS['db_name'] = "FAKE_DB";

// Fake da conexão PDO global usada no sistema
$GLOBALS['conn'] = null;

// Fake do BASE_URL global usado em controllers
$GLOBALS['BASE_URL'] = "/FAKE/";

// Fake do clienteDAO global (o setUp sobrescreve)
$GLOBALS['clienteDAO'] = null;

// Mock para impedir envio real de e-mail
if (!function_exists('enviarEmail')) {
    function enviarEmail($email, $nome, $corpo)
    {
        echo "EMAIL-FAKE-ENVIADO-PARA: $email";
    }
}

/****************************************************
 * IMPORTAÇÃO DOS ARQUIVOS DO PROJETO
 ****************************************************/
require_once __DIR__ . '/../controllers/ClienteController.php';
require_once __DIR__ . '/../DAO/ClienteDAO.php';
require_once __DIR__ . '/../config/email.php';


class ClienteControllerTest extends TestCase
{
    private $clienteDAO;
    private $conn;
    private $BASE_URL;

    protected function setUp(): void
    {
        // Mock da conexão PDO
        $this->conn = $this->createMock(\PDO::class);

        // Mock do ClienteDAO
        $this->clienteDAO = $this->createMock(\ClienteDAO::class);

        // Substitui o DAO real pelo mock
        $GLOBALS['clienteDAO'] = $this->clienteDAO;

        // BASE_URL fake
        $this->BASE_URL = "/fake-app/";
    }

    /** ----------------------------------------------
     *  Teste de funções simples       
     * ---------------------------------------------- */
    public function testLimparCaracteresEspeciais()
    {
        $this->assertSame("12345678900", limparCaracteresEspeciais("123.456.789-00"));
        $this->assertSame("11222333000199", limparCaracteresEspeciais("11.222.333/0001-99"));
    }

    /** ----------------------------------------------
     *  Criar Cliente
     * ---------------------------------------------- */
    public function testCriarClienteController()
    {
        $_SESSION = [];

        $dados = [
            "nome" => "João",
            "email" => "teste@teste.com",
            "telefone" => "1199999999"
        ];

        $this->clienteDAO
            ->expects($this->once())
            ->method('criarCliente')
            ->with($dados);

        ob_start();
        criarClienteController($this->conn, $dados, $this->BASE_URL);
        ob_end_clean();

        $this->assertSame("Cliente cadastrado com sucesso!", $_SESSION["msg"]);
    }

    /** ----------------------------------------------
     *  Editar Cliente
     * ---------------------------------------------- */
    public function testEditarClienteController()
    {
        $_SESSION = [];

        $dados = [
            "id" => 1,
            "nome" => "Maria",
            "email" => "a@a.com",
            "telefone" => "11-9999-9999",
            "cpf" => "123.456.789-00",
            "cnpj" => "11.222.333/0001-99",
            "cep" => "12.345-678"
        ];

        $this->clienteDAO
            ->expects($this->once())
            ->method('editarCliente')
            ->with($this->callback(function ($data) {
                return $data["cpf"] === "12345678900"
                    && $data["cnpj"] === "11222333000199"
                    && $data["cep"] === "12345678";
            }));

        ob_start();
        editarClienteController($this->conn, $dados, $this->BASE_URL);
        ob_end_clean();

        $this->assertSame("Cadastro atualizado com sucesso!", $_SESSION["msg"]);
    }

    /** ----------------------------------------------
     *  Deletar Cliente
     * ---------------------------------------------- */
    public function testDeletarClienteController()
    {
        $_SESSION = [];

        $dados = ["id" => 5];

        $this->clienteDAO
            ->method('buscarOrdensPorCliente')
            ->willReturn([["id" => 1]]);

        $this->clienteDAO
            ->expects($this->once())
            ->method('reatribuirOrdens')
            ->with(5, 1);

        $this->clienteDAO
            ->expects($this->once())
            ->method('deletarCliente')
            ->with(5);

        ob_start();
        deletarClienteController($this->conn, $dados, $this->BASE_URL);
        ob_end_clean();

        $this->assertSame("Cadastro removido com sucesso!", $_SESSION["msg"]);
    }

    /** ----------------------------------------------
     *  Testar solicitação de exclusão (mockando email)
     * ---------------------------------------------- */
    public function testSolicitarExclusaoController()
    {
        $dados = ["cpf" => "123.456.789-00"];

        $this->clienteDAO
            ->method('buscarClientePorCpf')
            ->willReturn([
                "id" => 10,
                "nome" => "Daniel",
                "email" => "d@d.com"
            ]);

        ob_start();
        solicitarExclusaoController($this->conn, $dados, $this->BASE_URL);
        $output = ob_get_clean();

        $this->assertMatchesRegularExpression('/EMAIL-FAKE-ENVIADO-PARA/', $output);
    }
}
