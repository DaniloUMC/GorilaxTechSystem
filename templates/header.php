<?php
    include_once("config/url.php");    
    include_once("config/process.php");    
    include_once("config/search.php");    

    //limpa a mensagem

    if(isset($_SESSION['msg'])){
        $printMsg = $_SESSION['msg'];
        $_SESSION['msg']='';
    }

    if (!isset($_SESSION["usuario"])) {
        header("location:" .$BASE_URL. "../index.php");
        exit();
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastros</title>
  <!-- BOOTSTRAP -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous" />
  <!-- FONT AWESOME -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
  <!-- CSS -->
  <link rel="stylesheet" href="<?= $BASE_URL ?>css/style.css">
  
    
</head>
<body>
    
    <script>
        function toggleCampos() {
            const tipo = document.getElementById("tipo").value;
            const divMarca = document.getElementById("div_marca");
            const divPreco = document.getElementById("div_preco_custo");

            if (tipo === "Serviço") {
                divMarca.style.display = "none";
                div_preco_custo.style.display = "none";
            } else if (tipo === "Produto") {
                divMarca.style.display = "block";
                div_preco_custo.style.display = "block";
            } else {
                // Se voltar para "Selecione...", mostra tudo por padrão
                divMarca.style.display = "block";
                div_preco_custo.style.display = "block";
            }
        }
    </script>
    <script>
        document.getElementById('btnAdicionar').addEventListener('click', function(event) {
        event.preventDefault(); // ✅ Impede atualização da página

        const nome = document.getElementById('nomeItem').value.trim();
        const quantidade = document.getElementById('quantidadeItem').value.trim();

        if (nome === '' || quantidade === '') {
            alert('Preencha todos os campos!');
            return;
        }

        const tabela = document.getElementById('tabelaItens').getElementsByTagName('tbody')[0];
        const novaLinha = tabela.insertRow();

        const colunaNome = novaLinha.insertCell(0);
        const colunaQuantidade = novaLinha.insertCell(1);
        const colunaAcoes = novaLinha.insertCell(2);

        colunaNome.textContent = nome;
        colunaQuantidade.textContent = quantidade;

        const botaoRemover = document.createElement('button');
        botaoRemover.textContent = 'Remover';
        botaoRemover.onclick = function() {
            tabela.deleteRow(novaLinha.rowIndex - 1);
        };
        colunaAcoes.appendChild(botaoRemover);

        document.getElementById('nomeItem').value = '';
        document.getElementById('quantidadeItem').value = '';
        });
    </script>

    <script>
        function adicionarItem() {
        const nome = document.getElementById('nomeItem').value.trim();
        const quantidade = document.getElementById('quantidadeItem').value.trim();

        if (nome === '' || quantidade === '') {
            alert('Preencha todos os campos!');
            return;
        }

        // Pega a tabela e cria uma nova linha
        const tabela = document.getElementById('tabelaItens').getElementsByTagName('tbody')[0];
        const novaLinha = tabela.insertRow();

        // Adiciona as colunas
        const colunaNome = novaLinha.insertCell(0);
        const colunaQuantidade = novaLinha.insertCell(1);
        const colunaAcoes = novaLinha.insertCell(2);

        colunaNome.textContent = nome;
        colunaQuantidade.textContent = quantidade;

        // Botão de remover
        const botaoRemover = document.createElement('button');
        botaoRemover.textContent = 'Remover';
        botaoRemover.onclick = function() {
            tabela.deleteRow(novaLinha.rowIndex - 1);
        };
        colunaAcoes.appendChild(botaoRemover);

        // Limpa os campos
        document.getElementById('nomeItem').value = '';
        document.getElementById('quantidadeItem').value = '';
        }
    </script>
    <script>
            function formatarCPF(input) {
                let valor = input.value.replace(/\D/g, ''); // Remove tudo que não for número
                valor = valor.slice(0, 11); // Limita a 11 dígitos
                
                if (valor.length > 9) {
                    input.value = valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                } else if (valor.length > 6) {
                    input.value = valor.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
                } else if (valor.length > 3) {
                    input.value = valor.replace(/(\d{3})(\d{1,3})/, '$1.$2');
                } else {
                    input.value = valor;
                }
            }
            function formatarReais(input) {
                input.addEventListener("input", function() {
                    // Remove tudo que não for número
                    let valor = this.value.replace(/\D/g, "");

                    // Converte para reais (centavos)
                    valor = (valor / 100).toFixed(2);

                    // Substitui ponto por vírgula
                    valor = valor.replace(".", ",");

                    // Adiciona separador de milhar
                    

                    // Adiciona prefixo R$
                    this.value = "R$ " + valor;
                });
            }
            function formatarCEP(input) {
                let valor = input.value.replace(/\D/g, ''); // Remove tudo que não for número
                valor = valor.slice(0, 8); // Limita a 8 dígitos

                if (valor.length > 5) {
                    input.value = valor.replace(/(\d{5})(\d{1,3})/, '$1-$2'); // Formato 00000-000
                } else {
                    input.value = valor;
                }
            }

            function formatarTelefone(input) {
                let valor = input.value.replace(/\D/g, ''); // Remove tudo que não for número
                valor = valor.slice(0, 11); // Limita a 11 dígitos (para evitar entrada excessiva)

                if (valor.length > 10) {
                    input.value = valor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3'); // Formato (XX) XXXXX-XXXX
                } else if (valor.length > 6) {
                    input.value = valor.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3'); // Formato (XX) XXXX-XXXX
                } else if (valor.length > 2) {
                    input.value = valor.replace(/(\d{2})(\d{0,5})/, '($1) $2'); // Formato (XX) XXXX
                } else {
                    input.value = valor;
                }
            }

            function formatarCNPJ(input) {
                let valor = input.value.replace(/\D/g, ''); // Remove tudo que não for número
                valor = valor.slice(0, 14); // Limita a 14 dígitos

                if (valor.length > 12) {
                    input.value = valor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5'); // Formato XX.XXX.XXX/XXXX-XX
                } else if (valor.length > 8) {
                    input.value = valor.replace(/(\d{2})(\d{3})(\d{3})(\d{1,4})/, '$1.$2.$3/$4');
                } else if (valor.length > 5) {
                    input.value = valor.replace(/(\d{2})(\d{3})(\d{1,3})/, '$1.$2.$3');
                } else if (valor.length > 2) {
                    input.value = valor.replace(/(\d{2})(\d{1,3})/, '$1.$2');
                } else {
                    input.value = valor;
                }
            }
    
            function buscarCep() {
                let cep = document.getElementById("cep").value.replace(/\D/g, ''); // Remove caracteres não numéricos

                if (cep.length === 8) {
                    let url = `https://viacep.com.br/ws/${cep}/json/`;

                    fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById("rua").value = data.logradouro;
                            document.getElementById("bairro").value = data.bairro;
                            document.getElementById("cidade").value = data.localidade;
                            document.getElementById("estado").value = data.uf;
                        } else {
                            alert("CEP não encontrado.");
                        }
                    })
                    .catch(error => console.error("Erro ao buscar CEP:", error));
                }
            }
            
            function printPage() {
            // Cria um novo conteúdo a ser impresso
            var content = document.getElementById("view-contact-container").innerHTML;

            // Abre a janela de impressão e insere o conteúdo
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Imprimir Dados do Contato</title></head><body>');
            printWindow.document.write(content);  // Conteúdo da página a ser impresso
            printWindow.document.write('</body></html>');
            
            // Aguarda o carregamento completo da página antes de chamar o método de impressão
            printWindow.document.close(); // Fecha o documento para renderizar corretamente
            printWindow.print();  // Chama a função de impressão
        }
    </script>
   <script>
      function buscarCliente(termo) {
          const box = document.getElementById('suggestions');
          const campo = document.getElementById('search');
          box.innerHTML = "";

          if (!termo || termo.trim().length < 2) return;

          fetch('config/search.php?term=' + encodeURIComponent(termo))
          .then(response => {
              if (!response.ok) throw new Error('Arquivo search.php não encontrado');
              return response.json();
          })
          .then(data => {
              if (!data || data.length === 0) return;

              data.forEach(item => {
                  const div = document.createElement('div');
                  div.textContent = `${item.id} - ${item.nome} (${item.cpf})`;
                  div.classList.add('suggestion-item');

                  // 🔹 Ao clicar, envia via POST o ID para o arquivo process
                  div.onclick = () => {
                      campo.value = item.id; 
                      box.innerHTML = "";

                      // Cria dinamicamente um formulário e envia por POST
                      const form = document.createElement('form');
                      form.method = 'POST';
                      form.action = 'config/process.php';
                      

                      const input = document.createElement('input');
                      input.type = 'hidden';
                      input.name = 'pesquisa_inteligente'; // <-- nome solicitado
                      input.value = item.id; 

                      form.appendChild(input);
                      document.body.appendChild(form);
                      form.submit();
                  };

                  box.appendChild(div);
              });
          })
          .catch(err => console.error('Erro na busca:', err));
      }

      // Fecha as sugestões ao clicar fora da área
      document.addEventListener('click', function(e) {
          const box = document.getElementById('suggestions');
          const campo = document.getElementById('search');
          if (!box.contains(e.target) && e.target !== campo) {
              box.innerHTML = "";
          }
      });

      window.buscarCliente = buscarCliente;
    </script>
    <script>
        function buscarProduto(valor) {
            const tabela = document.getElementById("tabelaProdutos");

            // Só busca após 3 letras (pode mudar para 8 se quiser)
            if (valor.trim().length < 3) {
                return;
            }

            const formData = new FormData();
            formData.append("type", "pesquisa_inteligente_produto");
            formData.append("busca", valor);

            fetch("config/process.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(html => {
                tabela.innerHTML = html; // Atualiza tabela dinamicamente
            })
            .catch(err => console.error("Erro na busca:", err));
        }
    </script>


<header>
<nav class="navbar navbar-expand-lg navbar-dark ye-primary">
      <a class="navbar-brand" href="<?= $BASE_URL ?>clientes_listar.php">
        <img src="https://static.wixstatic.com/media/0204e8_f918fafcb4414d5c865ab6fcfcff09d7~mv2.png/v1/fill/w_480,h_412,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/0204e8_f918fafcb4414d5c865ab6fcfcff09d7~mv2.pngg" alt="Agenda">
      </a>
      <div>
        <div class="navbar-nav">
        <a class="nav-link active" id="home-link" href="<?= $BASE_URL ?>clientes_listar.php">Cadastros</a>
        <a class="nav-link active" id="home-link" href="<?= $BASE_URL ?>produtos_listar.php">Produtos</a>
        <a class="nav-link active" id="home-link" href="<?= $BASE_URL ?>ordem_servico_listar.php">Registros de Serviços</a>

        <a class="nav-link active" href="<?= $BASE_URL ?>/logout.php">Sair</a>
        </div>
      </div>
    </nav>
</header>