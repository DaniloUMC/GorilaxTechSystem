<?php
// DAO/ProdutoDAO.php

class ProdutoDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Criar produto
    public function criarProduto($data) {
        $query = "INSERT INTO produtos (nome, tipo, marca, preco_custo, preco_final, descricao) 
                  VALUES (:nome, :tipo, :marca, :preco_custo, :preco_final, :descricao)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $data["nome"]);
        $stmt->bindParam(":tipo", $data["tipo"]);
        $stmt->bindParam(":marca", $data["marca"]);
        $stmt->bindParam(":preco_custo", $data["preco_custo"]);
        $stmt->bindParam(":preco_final", $data["preco_final"]);
        $stmt->bindParam(":descricao", $data["descricao"]);

        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    // Editar produto
    public function editarProduto($data) {
        $query = "UPDATE produtos 
                  SET nome = :nome, tipo = :tipo, marca = :marca, 
                      preco_custo = :preco_custo, preco_final = :preco_final, descricao = :descricao 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $data["id"]);
        $stmt->bindParam(":nome", $data["nome"]);
        $stmt->bindParam(":tipo", $data["tipo"]);
        $stmt->bindParam(":marca", $data["marca"]);
        $stmt->bindParam(":preco_custo", $data["preco_custo"]);
        $stmt->bindParam(":preco_final", $data["preco_final"]);
        $stmt->bindParam(":descricao", $data["descricao"]);

        return $stmt->execute();
    }

    // Deletar produto
    public function deletarProduto($id) {
        $stmt = $this->conn->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Listar produtos por busca
    public function listarProdutosPorBusca($busca) {
        $busca = trim($busca);
        $stmt = $this->conn->prepare("SELECT * FROM produtos WHERE nome LIKE :busca ORDER BY nome ASC");
        $stmt->bindValue(":busca", "$busca%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar produto por ID
    public function buscarProdutoPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($stmt);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
