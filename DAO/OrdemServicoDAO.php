<?php
// DAO/OrdemServicoDAO.php

class OrdemServicoDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Criar ordem de serviço
    public function criarOrdemServico($data) {
        $status_atual = "O.S";
        $query = "INSERT INTO ordem_servico 
                  (id_cliente, equipamento_cliente, data_abertura, pre_diagnostico, observacao, status_atual) 
                  VALUES (:id_cliente, :equipamento_cliente, :data_abertura, :pre_diagnostico, :observacao, :status_atual)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_cliente", $data["id"]);
        $stmt->bindParam(":equipamento_cliente", $data["equipamento"]);
        $stmt->bindParam(":data_abertura", $data["data_entrada"]);
        $stmt->bindParam(":pre_diagnostico", $data["pre_diagnostico"]);
        $stmt->bindParam(":observacao", $data["observacao"]);
        $stmt->bindParam(":status_atual", $status_atual);

        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    // Editar ordem de serviço
    public function editarOrdemServico($data) {
        $query = "UPDATE ordem_servico 
                  SET equipamento_cliente = :equipamento_cliente, 
                      complementos = :complementos, 
                      data_abertura = :data_entrada, 
                      pre_diagnostico = :pre_diagnostico, 
                      observacao = :observacao  
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $data["id"]);
        $stmt->bindParam(":equipamento_cliente", $data["equipamento_cliente"]);
        $stmt->bindParam(":complementos", $data["complementos"]);
        $stmt->bindParam(":data_entrada", $data["data_entrada"]);
        $stmt->bindParam(":pre_diagnostico", $data["pre_diagnostico"]);
        $stmt->bindParam(":observacao", $data["observacao"]);

        return $stmt->execute();
    }

    // Deletar ordem de serviço
    public function deletarOrdemServico($id) {
        $stmt = $this->conn->prepare("DELETE FROM ordem_servico WHERE id = :id");
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Buscar ordem por ID
    public function buscarOrdemPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM ordem_servico WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Listar ordens de serviço
    public function listarOrdens() {
        $stmt = $this->conn->prepare("SELECT * FROM ordem_servico ORDER BY data_abertura DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
