//header.php

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
    


      function buscarCliente(termo) {
          const box = document.getElementById('suggestions');
          const campo = document.getElementById('search');
          box.innerHTML = "";

          if (!termo || termo.trim().length < 2) return;

          fetch('../config/process.php?term=' + encodeURIComponent(termo))
          .then(response => {
              if (!response.ok) throw new Error('Arquivo process.php não encontrado');
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
                      form.action = '../config/process.php';
                      

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

 


        function buscarProduto(valor) {
            const tabela = document.getElementById("tabelaProdutos");

            // Só busca após 3 letras (pode mudar para 8 se quiser)
            if (valor.trim().length < 3) {
                return;
            }

            const formData = new FormData();
            formData.append("type", "pesquisa_inteligente_produto");
            formData.append("busca", valor);

            fetch("../config/process.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(html => {
                tabela.innerHTML = html; // Atualiza tabela dinamicamente
            })
            .catch(err => console.error("Erro na busca:", err));
        }
    
    
    //headerl






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







  // Fecha as sugestões ao clicar fora da área
  document.addEventListener('click', function(e) {
      const box = document.getElementById('suggestions');
      const campo = document.getElementById('search');
      if (!box.contains(e.target) && e.target !== campo) {
          box.innerHTML = "";
      }
  });

  window.buscarCliente = buscarCliente;



    function buscarProduto(valor) {
        const tabela = document.getElementById("tabelaProdutos");

        // Só busca após 3 letras (pode mudar para 8 se quiser)
        if (valor.trim().length < 3) {
            return;
        }

        const formData = new FormData();
        formData.append("type", "pesquisa_inteligente_produto");
        formData.append("busca", valor);

        fetch("../config/process.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(html => {
            tabela.innerHTML = html; // Atualiza tabela dinamicamente
        })
        .catch(err => console.error("Erro na busca:", err));
    }






   





function buscarProduto(valor) {
const tabela = document.getElementById("tabelaProdutos");

// Se o campo estiver vazio, recarrega todos os produtos
if (valor.trim().length === 0) {
    fetch("produtos_listar_tabela.php") // <-- arquivo PHP que lista todos os produtos
        .then(res => res.text())
        .then(html => {
            tabela.innerHTML = html;
        })
        .catch(err => console.error("Erro ao recarregar produtos:", err));
    return;
}

// Só busca após 3 letras (pode mudar para 8 se quiser)
if (valor.trim().length < 3) {
    return;
}

const formData = new FormData();
formData.append("type", "pesquisa_inteligente_produto");
formData.append("busca", valor);

fetch("../config/process.php", {
    method: "POST",
    body: formData
})
.then(res => res.text())
.then(html => {
    tabela.innerHTML = html; // substitui o conteúdo da tabela filtrada
})
.catch(err => console.error("Erro na busca:", err));
}


//selecionar produtos.php


// 🔍 Filtro de produtos
function filtrarProdutos() {
const val = document.getElementById('busca').value.toLowerCase();
document.querySelectorAll('#tabelaProdutos tr').forEach(tr => {
    const nome = tr.querySelector('.prod-nome').innerText.toLowerCase();
    tr.style.display = nome.includes(val) ? '' : 'none';
});
}

// 🟩 Reaplica seleção anterior
window.inicializarSelecionados = function(listaSelecionados) {
if (!Array.isArray(listaSelecionados) || listaSelecionados.length === 0) return;
document.querySelectorAll('#tabelaProdutos tr').forEach(tr => {
    const chk = tr.querySelector('.chk-produto');
    const qtdInput = tr.querySelector('.quantidade');
    const prod = JSON.parse(chk.getAttribute('data-json'));
    const encontrado = listaSelecionados.find(p => Number(p.id) === Number(prod.id));
    if (encontrado) {
        chk.checked = true;
        qtdInput.value = Number(encontrado.quantidade) || 1;
    }
});
};

// 🟢 Envia produtos para a janela principal
function enviarSelecionados() {
const selecionados = [];
document.querySelectorAll('#tabelaProdutos tr').forEach(tr => {
    const chk = tr.querySelector('.chk-produto');
    if (!chk || !chk.checked) return;
    const prod = JSON.parse(chk.getAttribute('data-json'));
    const qtd = tr.querySelector('.quantidade').value || 1;
    prod.quantidade = Number(qtd);
    selecionados.push(prod);
});

try {
    if (window.opener && !window.opener.closed && typeof window.opener.adicionarProdutosSelecionados === 'function') {
        window.opener.adicionarProdutosSelecionados(selecionados);
        window.close();
        return;
    }
} catch(e) {
    console.warn("Não foi possível usar opener:", e);
}

// fallback com localStorage
try {
    localStorage.setItem('produtosSelecionados_temp', JSON.stringify(selecionados));
    window.close();
} catch(e) {
    alert("Erro ao enviar seleção: " + e.message);
}
}

// 🟠 Se popup abriu de novo, tenta restaurar do localStorage
(function applyLocalStorageIfExists() {
try {
    const temp = localStorage.getItem('produtosSelecionados_temp');
    if (temp) {
        const arr = JSON.parse(temp);
        if (Array.isArray(arr) && arr.length) {
            inicializarSelecionados(arr);
            localStorage.removeItem('produtosSelecionados_temp');
        }
    }
} catch(e) {}
})();


//solicita_exclusao_bd.php


  


let produtos = [];

function adicionar() {
const nome = document.getElementById("produto").value;
const qtd = document.getElementById("quantidade").value;
if (!nome) return;

produtos.push({ nome, qtd });
atualizarTabela();

// salvar dados localmente para manter no orçamento principal
sessionStorage.setItem("produtos", JSON.stringify(produtos));
}

function atualizarTabela() {
const tbody = document.querySelector("#tabela tbody");
tbody.innerHTML = "";
produtos.forEach(p => {
const tr = document.createElement("tr");
tr.innerHTML = `<td>${p.nome}</td><td>${p.qtd}</td>`;
tbody.appendChild(tr);
});
}

// caso já tenha produtos salvos
window.onload = () => {
const salvos = sessionStorage.getItem("produtos");
if (salvos) {
produtos = JSON.parse(salvos);
atualizarTabela();
}
};


//testeprincipal.php


function fecharProdutos() {
document.getElementById("overlay").style.display = "none";
}



