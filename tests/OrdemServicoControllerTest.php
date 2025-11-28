<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../controllers/OrdemServicoController.php';

class FakeOrdemServicoDAO {
    public $recebido = null;
    public $simularErro = false;

    public function criarOrdemServico($data) {
        if ($this->simularErro) {
            throw new Exception("Erro simulado");
        }
        $this->recebido = $data;
    }

    public function editarOrdemServico($data) {
        if ($this->simularErro) {
            throw new Exception("Erro simulado");
        }
        $this->recebido = $data;
    }

    public function deletarOrdemServico($id) {
        if ($this->simularErro) {
            throw new Exception("Erro simulado");
        }
        $this->recebido = $id;
    }
}

class OrdemServicoControllerTest extends TestCase {

    public function testCriarOrdemServicoComSucesso() {
        $fakeDAO = new FakeOrdemServicoDAO();
        $data = ["cliente" => "João", "equipamento" => "Notebook"];

        $resultado = criarOrdemServicoController($fakeDAO, $data);

        $this->assertEquals("ok", $resultado);
        $this->assertEquals($data, $fakeDAO->recebido);
    }

    public function testCriarOrdemServicoComErro() {
        $fakeDAO = new FakeOrdemServicoDAO();
        $fakeDAO->simularErro = true;

        $resultado = criarOrdemServicoController($fakeDAO, ["x" => 1]);

        $this->assertEquals("erro", $resultado);
    }

    public function testEditarOrdemServico() {
        $fakeDAO = new FakeOrdemServicoDAO();
        $data = ["id" => 1, "cliente" => "Maria"];

        $resultado = editarOrdemServicoController($fakeDAO, $data);

        $this->assertEquals("ok", $resultado);
        $this->assertEquals($data, $fakeDAO->recebido);
    }

    public function testDeletarOrdemServico() {
        $fakeDAO = new FakeOrdemServicoDAO();

        $resultado = deletarOrdemServicoController($fakeDAO, 5);

        $this->assertEquals("ok", $resultado);
        $this->assertEquals(5, $fakeDAO->recebido);
    }
}
