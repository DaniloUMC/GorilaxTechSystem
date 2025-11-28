<?php

class FakeProdutoDAO {

    public $recebido = null;
    public $simularErro = false;

    public function criarProduto($data) {
        if ($this->simularErro) { throw new Exception("Erro simulado"); }
        $this->recebido = $data;
    }

    public function editarProduto($data) {
        if ($this->simularErro) { throw new Exception("Erro simulado"); }
        $this->recebido = $data;
    }

    public function deletarProduto($id) {
        if ($this->simularErro) { throw new Exception("Erro simulado"); }
        $this->recebido = $id;
    }
}
