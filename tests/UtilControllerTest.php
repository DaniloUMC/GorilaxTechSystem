<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../controllers/UtilController.php';
require_once __DIR__ . '/../DAO/UtilDAO.php';

class UtilControllerTest extends TestCase
{
    private $daoMock;
    private $BASE_URL = "/sistema/";

    protected function setUp(): void
    {
        $this->daoMock = $this->createMock(UtilDAO::class);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /** ============================
     *  LOGIN
     *  ============================ */
    public function testLoginSucesso()
    {
        global $utilDAO;
        $utilDAO = $this->daoMock;

        $data = ["email" => "teste@exemplo.com", "senha" => "123"];

        $this->daoMock->method("login")->willReturn([
            "nome" => "Danilo",
            "senha" => "123"
        ]);

        $this->expectOutputRegex('/Location/');

        loginController($data, $this->BASE_URL);
    }

    public function testLoginFalha()
    {
        global $utilDAO;
        $utilDAO = $this->daoMock;

        $data = ["email" => "erro@exemplo.com", "senha" => "senhaerrada"];

        $this->daoMock->method("login")->willReturn(["nome" => "X", "senha" => "123"]);

        $this->expectOutputString("❌ Senha ou email incorretos.");

        loginController($data, $this->BASE_URL);
    }


    /** ============================
     *  ENVIAR SERVIÇO
     *  ============================ */
    public function testEnviarServico()
    {
        $data = ["id" => 10];

        $this->daoMock->method("buscarIdClienteOrdem")->willReturn(["id_cliente" => 5]);
        $this->daoMock->method("buscarContato")->willReturn(["nome" => "João", "email" => "a@a.com"]);

        $this->daoMock->expects($this->once())->method("atualizarStatusOrdem");

        $this->expectOutputRegex("/Location/");

        enviarServicoController($this->daoMock, $data, $this->BASE_URL);
    }


    /** ============================
     * DEFINIR ENTREGUE
     * ============================ */
    public function testDefinirEntregue()
    {
        $data = ["type" => "entregue", "id" => 3];

        $this->daoMock->expects($this->once())->method("definirEntregue")->with(3);

        $this->expectOutputRegex("/Location/");

        definirEntregueController($this->daoMock, $data, $this->BASE_URL);
    }


    /** ============================
     * ALTERAR SENHA
     * ============================ */
    public function testAlterarSenhaSucesso()
    {
        $email = "danilo@teste.com";
        $frase = "GorilaxTech10";
        $novaSenha = "nova123";

        $this->daoMock->method("buscarUsuarioPorEmail")->willReturn(["email" => $email]);
        $this->daoMock->expects($this->once())->method("atualizarSenha");

        $this->expectOutputRegex("/Location/");

        alterarSenhaController($this->daoMock, $email, $frase, $novaSenha, $this->BASE_URL);
    }

    public function testAlterarSenhaFraseErrada()
    {
        $this->expectOutputString("❌ Frase de validação incorreta.");

        alterarSenhaController($this->daoMock, "x", "frase_errada", "123", $this->BASE_URL);
    }

    public function testAlterarSenhaUsuarioNaoExiste()
    {
        $this->daoMock->method("buscarUsuarioPorEmail")->willReturn(null);

        $this->expectOutputString("❌ Usuário não encontrado.");

        alterarSenhaController($this->daoMock, "b@b.com", "GorilaxTech10", "999", $this->BASE_URL);
    }


    /** ============================
     * CONFIRMAR ORÇAMENTO
     * ============================ */
    public function testConfirmarOrcamento()
    {
        $data = ["id" => 8];

        $this->daoMock->expects($this->once())->method("confirmarOrcamento")->with(8);

        $this->expectOutputRegex('/Location/');

        confirmarOrcamentoController($this->daoMock, $data);
    }


    /** ============================
     * CRIAR ORÇAMENTO
     * ============================ */
    public function testCreateOrcamento()
    {
        $_POST["produtos_string"] = "1:2,3:1";

        $data = [
            "id" => 1,
            "observacao" => "Teste",
            "data_orcamento" => "2025-11-28"
        ];

        $this->daoMock->method("buscarProduto")->willReturn(
            ["preco_final" => 10.00, "nome" => "Produto X"]
        );

        $this->daoMock->method("buscarOrdemPorId")->willReturn([
            "id" => 1,
            "id_cliente" => 5,
            "observacao" => "Diagnóstico"
        ]);

        $this->daoMock->method("buscarContato")->willReturn([
            "nome" => "Cliente",
            "email" => "cliente@ex.com"
        ]);

        $this->daoMock->expects($this->once())->method("atualizarOrcamento");

        $this->expectOutputRegex("/Location/");

        createOrcamentoController($this->daoMock, $data, $this->BASE_URL);
    }
}
