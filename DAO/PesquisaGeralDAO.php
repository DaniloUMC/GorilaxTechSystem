<?php
// DAO/PesquisaGeralDAO.php

class PesquisaGeralDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Listar todos os registros (contatos, produtos e ordens)
    public function listarTudo($filtroStatus = '', $filtroData = '') {
        // Contatos
        $stmtContatos = $this->conn->prepare("SELECT * FROM contatos ORDER BY id DESC");
        $stmtContatos->execute();
        $contatos = $stmtContatos->fetchAll(PDO::FETCH_ASSOC);

        // Produtos
        $stmtProdutos = $this->conn->prepare("SELECT * FROM produtos ORDER BY id DESC");
        $stmtProdutos->execute();
        $produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

        // Ordens de serviço com filtros
        $queryOrdem = "SELECT * FROM ordem_servico WHERE 1=1 ";
        if (!empty($filtroStatus)) {
            $queryOrdem .= " AND status_atual = :status_atual ";
        }
        if (!empty($filtroData)) {
            $queryOrdem .= " AND data_abertura = :data_abertura ";
        }
        $queryOrdem .= " ORDER BY id DESC";

        $stmtOrdem = $this->conn->prepare($queryOrdem);
        if (!empty($filtroStatus)) {
            $stmtOrdem->bindParam(":status_atual", $filtroStatus);
        }
        if (!empty($filtroData)) {
            $stmtOrdem->bindParam(":data_abertura", $filtroData);
        }
        $stmtOrdem->execute();
        $ordem_servicos = $stmtOrdem->fetchAll(PDO::FETCH_ASSOC);

        // Adiciona o nome do cliente em cada OS
        foreach ($ordem_servicos as &$ordem) {
            $stmtCliente = $this->conn->prepare("SELECT nome FROM contatos WHERE id = :id_cliente");
            $stmtCliente->bindParam(":id_cliente", $ordem["id_cliente"], PDO::PARAM_INT);
            $stmtCliente->execute();
            $resultado = $stmtCliente->fetch(PDO::FETCH_ASSOC);
            $ordem["nome_cliente"] = $resultado ? $resultado["nome"] : "Cliente não encontrado";
        }

        return [
            'contatos' => $contatos,
            'produtos' => $produtos,
            'ordem_servicos' => $ordem_servicos
        ];
    }

    // Pesquisa geral por ID
    public function pesquisaGeral($id) {
        $resultado = [
            "contato" => null,
            "produto" => null,
            "ordem" => null,
            "cliente" => null
        ];

        // Contato
        $stmtContato = $this->conn->prepare("SELECT * FROM contatos WHERE id = :id");
        $stmtContato->bindParam(":id", $id);
        $stmtContato->execute();
        $resultado["contato"] = $stmtContato->fetch(PDO::FETCH_ASSOC);

        // Produto
        $stmtProduto = $this->conn->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmtProduto->bindParam(":id", $id);
        $stmtProduto->execute();
        $resultado["produto"] = $stmtProduto->fetch(PDO::FETCH_ASSOC);

        // Ordem de serviço
        $stmtOrdem = $this->conn->prepare("SELECT * FROM ordem_servico WHERE id = :id");
        $stmtOrdem->bindParam(":id", $id);
        $stmtOrdem->execute();
        $resultado["ordem"] = $stmtOrdem->fetch(PDO::FETCH_ASSOC);

        // Cliente relacionado à ordem
        if (!empty($resultado["ordem"]["id_cliente"])) {
            $stmtCliente = $this->conn->prepare("SELECT * FROM contatos WHERE id = :id_cliente");
            $stmtCliente->bindParam(":id_cliente", $resultado["ordem"]["id_cliente"]);
            $stmtCliente->execute();
            $resultado["cliente"] = $stmtCliente->fetch(PDO::FETCH_ASSOC);
        }

        return $resultado;
    }
}
