<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastro de Orçamento</title>
<style>
  body { font-family: Arial; margin: 20px; }
  #overlay {
    display: none;
    position: fixed; top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
  }
  #janela {
    background: #fff;
    width: 80%; height: 80%;
    margin: 5% auto;
    border-radius: 10px;
    box-shadow: 0 0 10px #000;
    padding: 10px;
  }
  iframe {
    width: 100%; height: 90%;
    border: none;
  }
</style>
</head>
<body>

<h2>Cadastro de Orçamento</h2>
<form id="formOrcamento">
  <label>Cliente:</label><br>
  <input type="text" name="cliente" required><br><br>

  <label>Data:</label><br>
  <input type="date" name="data"><br><br>

  <label>Observações:</label><br>
  <textarea name="obs"></textarea><br><br>

  <button type="button" onclick="abrirProdutos()">Adicionar Produtos</button>
  <button type="submit">Salvar Orçamento</button>
</form>

<!-- Modal -->
<div id="overlay">
  <div id="janela">
    <button onclick="fecharProdutos()">Fechar</button>
    <iframe id="iframeProdutos" src=""></iframe>
  </div>
</div>

<script>
function abrirProdutos() {
  document.getElementById("iframeProdutos").src = "teste.html";
  document.getElementById("overlay").style.display = "block";
}

function fecharProdutos() {
  document.getElementById("overlay").style.display = "none";
}
</script>
</body>
</html>
