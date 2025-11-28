<?php
// controllers/HtmlController.php

function renderAcoesOrdemServico($ordem_servico, $BASE_URL) {
    $html = '';

    if ($ordem_servico['status_atual'] == "Orçamento") {
        $html .= "
        <a title='Visualizar Orçamento' href='". $BASE_URL ."show_orcamento.php?id=" .$ordem_servico['id'] ."'><i class='fas fa-eye check-icon'></i></a>
        <form class='delete-form' action='{$BASE_URL}../config/process.php' method='POST'>
            <input type='hidden' name='type' value='enviar_servico'>
            <input type='hidden' name='id' value='{$ordem_servico["id"]}'>
            <button title='Enviar para Serviço' type='submit' class='delete-btn'>
                <i class='fa fa-wrench'></i>
            </button>
        </form>";
    }

    if ($ordem_servico['status_atual'] == "Serviço" || $ordem_servico['status_atual'] == "Finalizado") {
        $html .= "
        <a title='Visualizar Orçamento' href='". $BASE_URL ."show_orcamento.php?id=" .$ordem_servico['id'] ."'><i class='fas fa-eye check-icon'></i></a>";
    }

    if ($ordem_servico['status_atual'] == "Finalizado" && !$ordem_servico["entregue"]) {
        $html .= "
        <form class='delete-form' action='{$BASE_URL}../config/process.php' method='POST'>
            <input type='hidden' name='type' value='entregue'>
            <input type='hidden' name='id' value='{$ordem_servico["id"]}'>
            <button title='Definir como entregue' type='submit' class='delete-btn'>
                <i class='fa fa-cube'></i>
            </button>
        </form>";
    }

    if ($ordem_servico['status_atual'] == "O.S") {
        $html .= "
        <a title='Visualizar' href='". $BASE_URL ."show_ordem_servico.php?id=" .$ordem_servico['id'] ."'><i class='fas fa-eye check-icon'></i></a>
        <a title='Gerar Orçamento' href='{$BASE_URL}create_orcamento.php?id={$ordem_servico['id']}'><i class='fas fa-briefcase'></i></a>
        <a title='Editar' href='{$BASE_URL}edit_ordem_servico.php?id={$ordem_servico['id']}'><i class='fas fa-edit edit-icon'></i></a>
        <form class='delete-form' action='{$BASE_URL}../config/process.php' method='POST'>
            <input type='hidden' name='type' value='delete_ordem_servico'>
            <input type='hidden' name='id' value='{$ordem_servico["id"]}'>
            <button title='Deletar' type='submit' class='delete-btn'>
                <i class='fas fa-trash delete-icon'></i>
            </button>
        </form>";
    }

    return $html;
}
function formatarCPF($cpf) {
    // Remove tudo que não for número
    $cpf = preg_replace('/\D/', '', $cpf);

    // Formata CPF: 000.000.000-00
    if (strlen($cpf) === 11) {
        $cpf = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }

    return $cpf;
}

function formatarTelefone($telefone) {
    // Remove tudo que não for número
    $telefone = preg_replace('/\D/', '', $telefone);

    // Celular: (xx) xxxxx-xxxx
    if (strlen($telefone) === 11) {
        $telefone = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
    } 
    // Telefone fixo: (xx) xxxx-xxxx
    elseif (strlen($telefone) === 10) {
        $telefone = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
    }

    return $telefone;
}


function renderTabelaItens($itens, $conn) {
    $html = '';
    $valor_total = 0;

    foreach ($itens as $item) {
        list($idProduto, $quantidade) = explode(':', $item);

        $query = "SELECT nome, preco_final FROM produtos WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $idProduto);
        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produto) {
            $nome = htmlspecialchars($produto['nome']);
            $preco = number_format($produto['preco_final'], 2, ',', '.');
            $subtotal = $produto['preco_final'] * (int)$quantidade;
            $valor_total += $subtotal;
        } else {
            $nome = "Produto não encontrado";
            $preco = "0,00";
            $subtotal = 0;
        }

        $html .= "<tr>
                    <td>" . htmlspecialchars($idProduto) . "</td>
                    <td>$nome</td>
                    <td>" . htmlspecialchars($quantidade) . "</td>
                    <td>R$ $preco</td>
                    <td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>
                  </tr>";
    }

    return ['html' => $html, 'valor_total' => $valor_total];
}

function gerarQrCodeProduto($idProduto) {
    // Formata o ID com 6 dígitos
    $idFormatado = str_pad($idProduto, 6, '0', STR_PAD_LEFT); 

    // URL da API para gerar o QR Code
    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($idFormatado) . "&size=300x300";

    // Retorna o HTML do QR Code
    return '<div style="text-align: center;">
                <img src="' . $qrCodeUrl . '" alt="QR Code">
            </div>';
}
function gerarQrCodeContato($idContato) {
    // Formata o ID com 6 dígitos
    $idFormatado = str_pad($idContato, 6, '0', STR_PAD_LEFT); 

    // URL da API para gerar o QR Code
    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($idFormatado) . "&size=300x300";

    // Retorna o HTML do QR Code
    return '<div style="text-align: center;">
                <img src="' . $qrCodeUrl . '" alt="QR Code">
            </div>';
}