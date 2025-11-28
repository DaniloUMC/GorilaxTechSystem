<?php
// DAO/UtilDAO.php

class UtilDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // =======================
    // Login
    // =======================
    public function login($email) {
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =======================
    // Enviar Serviço — buscar cliente e ordem
    // =======================
    public function buscarIdClienteOrdem($id) {
        $query = "SELECT id_cliente FROM ordem_servico WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarContato($id_cliente) {
        $query = "SELECT email, nome FROM contatos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id_cliente);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarStatusOrdem($id, $status_atual) {
        $query = "UPDATE ordem_servico SET status_atual = :status_atual WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":status_atual", $status_atual);
        return $stmt->execute();
    }

    // =======================
    // Definir Entregue
    // =======================
    public function definirEntregue($id) {
        $query = "UPDATE ordem_servico SET entregue = true WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // =======================
    // Alterar Senha
    // =======================
    public function buscarUsuarioPorEmail($email) {
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarSenha($email, $novaSenha) {

        // Criptografa a senha
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        $query = "UPDATE usuarios SET senha = :senha WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":senha", $senhaHash);
        $stmt->bindParam(":email", $email);

        return $stmt->execute();
    }
    // =======================
    // Confirmar Orçamento
    // =======================
    public function confirmarOrcamento($id) {
        $orc_aprovado = true;
        $stmt = $this->conn->prepare("UPDATE ordem_servico SET orc_aprovado = :orc_aprovado WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":orc_aprovado", $orc_aprovado);
        return $stmt->execute();
    }

    // =======================
    // Criar Orçamento
    // =======================
    public function buscarProduto($id_prod) {
        $query = "SELECT * FROM produtos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id_prod);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarOrcamento($id, $valor_total_orcamento, $produtos_servicos, $status_atual, $observacao, $data_orcamento) {
        $query = "UPDATE ordem_servico 
                  SET valor_total_orcamento = :valor_total_orcamento,
                      produtos_servicos = :produtos_servicos,
                      status_atual = :status_atual,
                      observacao = :observacao,
                      data_orcamento = :data_orcamento
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":valor_total_orcamento", $valor_total_orcamento);
        $stmt->bindParam(":produtos_servicos", $produtos_servicos);
        $stmt->bindParam(":status_atual", $status_atual);
        $stmt->bindParam(":observacao", $observacao);
        $stmt->bindParam(":data_orcamento", $data_orcamento);
        return $stmt->execute();
    }

    public function buscarOrdemPorId($id) {
        $query = "SELECT * FROM ordem_servico WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
