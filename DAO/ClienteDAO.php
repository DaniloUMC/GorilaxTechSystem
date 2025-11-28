<?php
// DAO/ClienteDAO.php

class ClienteDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Criar cliente
    public function criarCliente($data) {
        $query = "INSERT INTO contatos 
                    (nome, email, telefone, cpf, empresa, cnpj, cep, complemento)
                  VALUES
                    (:nome, :email, :telefone, :cpf, :empresa, :cnpj, :cep, :complemento)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $data["nome"]);
        $stmt->bindParam(":email", $data["email"]);
        $stmt->bindParam(":telefone", $data["telefone"]);
        $stmt->bindParam(":cpf", $data["cpf"]);
        $stmt->bindParam(":empresa", $data["empresa"]);
        $stmt->bindParam(":cnpj", $data["cnpj"]);
        $stmt->bindParam(":cep", $data["cep"]);
        $stmt->bindParam(":complemento", $data["complemento"]);

        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    // Editar cliente
    public function editarCliente($data) {
        $query = "UPDATE contatos 
                  SET nome = :nome, email = :email, telefone = :telefone, cpf = :cpf, 
                      empresa = :empresa, cnpj = :cnpj, cep = :cep, complemento = :complemento 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $data["id"]);
        $stmt->bindParam(":nome", $data["nome"]);
        $stmt->bindParam(":email", $data["email"]);
        $stmt->bindParam(":telefone", $data["telefone"]);
        $stmt->bindParam(":cpf", $data["cpf"]);
        $stmt->bindParam(":empresa", $data["empresa"]);
        $stmt->bindParam(":cnpj", $data["cnpj"]);
        $stmt->bindParam(":cep", $data["cep"]);
        $stmt->bindParam(":complemento", $data["complemento"]);

        return $stmt->execute();
    }

    // Deletar cliente
    public function deletarCliente($id) {
        $stmt = $this->conn->prepare("DELETE FROM contatos WHERE id = :id");
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Buscar cliente por CPF
    public function buscarClientePorCpf($cpf) {
        $stmt = $this->conn->prepare("SELECT * FROM contatos WHERE cpf = :cpf");
        $stmt->bindParam(":cpf", $cpf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar ordens de serviço de um cliente
    public function buscarOrdensPorCliente($id_cliente) {
        $stmt = $this->conn->prepare("SELECT * FROM ordem_servico WHERE id_cliente = :id_cliente");
        $stmt->bindParam(":id_cliente", $id_cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Reatribuir ordens para cliente padrão
    public function reatribuirOrdens($id_cliente, $id_default = 1) {
        $stmt = $this->conn->prepare("UPDATE ordem_servico SET id_cliente = :id_default WHERE id_cliente = :id_cliente");
        $stmt->bindParam(":id_cliente", $id_cliente);
        $stmt->bindParam(":id_default", $id_default);
        return $stmt->execute();
    }
}
