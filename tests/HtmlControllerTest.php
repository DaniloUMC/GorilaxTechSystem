<?php
namespace Tests;

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../controllers/HtmlController.php';

class HtmlControllerTest extends TestCase
{
    /** @test */
    public function testFormatarCpf()
    {
        $this->assertEquals("123.456.789-00", formatarCPF("12345678900"));
        $this->assertEquals("123.456.789-00", formatarCPF("123.456.789-00"));
    }

    /** @test */
    public function testFormatarTelefoneCelular()
    {
        $this->assertEquals("(11) 98765-4321", formatarTelefone("11987654321"));
    }

    /** @test */
    public function testFormatarTelefoneFixo()
    {
        $this->assertEquals("(11) 3456-7890", formatarTelefone("1134567890"));
    }

    /** @test */
    public function testRenderAcoesOrcamento()
    {
        $ordem = [
            "id" => 10,
            "status_atual" => "Orçamento",
            "entregue" => 0
        ];

        $BASE_URL = "http://localhost/";

        $html = renderAcoesOrdemServico($ordem, $BASE_URL);

        $this->assertStringContainsString("show_orcamento.php?id=10", $html);
        $this->assertStringContainsString("value='enviar_servico'", $html);
    }

    /** @test */
    public function testRenderAcoesServicoOuFinalizado()
    {
        $ordem = [
            "id" => 50,
            "status_atual" => "Serviço",
            "entregue" => 0
        ];

        $BASE_URL = "http://localhost/";
        $html = renderAcoesOrdemServico($ordem, $BASE_URL);

        $this->assertStringContainsString("show_orcamento.php?id=50", $html);
    }

    /** @test */
    public function testRenderTabelaItens()
    {
        // Mock PDO
        $pdoMock = $this->createMock(\PDO::class);
        $stmtMock = $this->createMock(\PDOStatement::class);

        // Espera prepare() retornar PDOStatement
        $pdoMock->method('prepare')->willReturn($stmtMock);

        // Para cada execução de fetch()
        $stmtMock->method('fetch')->willReturn([
            'nome' => "Teclado Gamer",
            'preco_final' => 150.00
        ]);

        $itens = ["1:2"]; // 2 unidades do ID 1

        $resultado = renderTabelaItens($itens, $pdoMock);

        $this->assertStringContainsString("Teclado Gamer", $resultado['html']);
        $this->assertEquals(300.00, $resultado['valor_total']);
    }

    /** @test */
    public function testGerarQrCodeProduto()
    {
        $html = gerarQrCodeProduto(25);

        $this->assertStringContainsString("000025", $html);
        $this->assertStringContainsString("img src=", $html);
    }

    /** @test */
    public function testGerarQrCodeContato()
    {
        $html = gerarQrCodeContato(999);

        $this->assertStringContainsString("000999", $html);
        $this->assertStringContainsString("img src=", $html);
    }
}
