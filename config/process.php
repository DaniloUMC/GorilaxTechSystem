<?php
    session_start();
    
    include_once("connection.php");
    include_once("connection.php");
    include_once("email.php");
    include_once("url.php");
    $data = $_POST;

   if (!empty($_POST["type"]) && $_POST["type"] === "pesquisa_inteligente_produto") {
    include_once("connection.php");
    $busca = trim($_POST["busca"]);

    $query = "SELECT * FROM produtos WHERE nome LIKE :busca ORDER BY nome ASC";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":busca", "$busca%");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($produtos) {
        echo "<table class='table' id='contatos-table'>";
        echo "<thead>
                    <tr>
                        <th style='text-align: center;' scope='col'>Nº</th>
                        <th style='text-align: center;' scope='col'>Nome</th>
                        <th style='text-align: center;' scope='col'>Tipo</th>
                        <th style='text-align: center;' scope='col'>Marca</th>
                        <th style='text-align: center;' scope='col'>Preço de Custo</th>
                        <th style='text-align: center;' scope='col'>Preço Final</th>
                        <th style='text-align: center;' scope='col'></th>
                    </tr>
                </thead>>";
        foreach ($produtos as $p) {
            echo "<tr>
                    <td style='text-align: center;'>{$p['id']}</td>
                    <td style='text-align: center;'>".htmlspecialchars($p['nome'])."</td>
                    <td style='text-align: center;'>".htmlspecialchars($p['tipo'])."</td>
                    <td style='text-align: center;'>".htmlspecialchars($p['marca'])."</td>
                    <td style='text-align: center;'>R$ ".number_format($p['preco_custo'], 2, ',', '.')."</td>
                    <td style='text-align: center;'>R$ ".number_format($p['preco_final'], 2, ',', '.')."</td>
                    <td style='text-align: center;' class='actions'>
                                <a title='Visualizar' href='".$BASE_URL. "../show_produto.php?id=". $p['id'] ."'><i class='fas fa-eye check-icon'></i></a>
                                <a title='Editar' href='".  $BASE_URL . "../edit_produto.php?id=".$p['id'] ."'><i class='fas fa-edit edit-icon'></i></a>
                                <form class='delete-form' action='". $BASE_URL. "/config/process.php' method='POST'>
                                    <input type='hidden' name='type' value='delete_prod'>
                                    <input type='hidden' name='id' value='".$p["id"]."'>
                                    <button title='Excluir' type='submit' class='delete-btn'><i class='fas fa-trash delete-icon'></i></button>
                                </form>
                            </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Nenhum produto encontrado.</p>";
    }

    exit; // Impede que o restante do process.php rode
}


    
    function limparCaracteresEspeciais($valor) {
        return preg_replace("/[.\/\-]/", "", $valor);
    }
    function formatarRealBD($valor) {


        $valor = str_replace(",", ".", $valor);
        $valor = str_replace("R$", "", $valor); 
        $valor = str_replace(" ", "", $valor);// substitui vírgula por ponto
        $valor = number_format((float)$valor, 2, ".", ""); // 2 casas decimais
            
        return $valor;
    }

    if (!empty($idClienteSelecionado)) {
    // usa o id da sessão para filtrar
        $id = $idClienteSelecionado;

        $queryprod  = "SELECT * FROM produtos WHERE id = :id ORDER BY id DESC"; 
        $query  = "SELECT * FROM contatos WHERE id = :id ORDER BY id DESC"; 
        $queryordem  = "SELECT * FROM ordem_servico WHERE id = :id ORDER BY id DESC";         

        $stmt = $conn->prepare($query);
        $stmtprod = $conn->prepare($queryprod);
        $stmtordem = $conn->prepare($queryordem);

        $stmt->bindParam(":id", $id);
        $stmtprod->bindParam(":id", $id);
        $stmtordem->bindParam(":id", $id);

        $stmt->execute();
        $stmtprod->execute();
        $stmtordem->execute();

        $contatos = $stmt->fetchAll();
        $produtos = $stmtprod->fetchAll();
        $ordem_servicos = $stmtordem->fetchAll();

        // limpa a sessão para não manter o filtro sempre
        unset($_SESSION['pesquisa_inteligente']);
    } 
  
    else {
        // sem filtro — lista tudo (seu código atual)
        $queryprod  = "SELECT * FROM produtos ORDER BY id DESC"; 
        $query  = "SELECT * FROM contatos ORDER BY id DESC"; 
        $queryordem  = "SELECT * FROM ordem_servico ORDER BY id DESC";         

        $stmt = $conn->prepare($query);
        $stmtprod = $conn->prepare($queryprod);
        $stmtordem = $conn->prepare($queryordem);

        $stmt->execute();
        $stmtprod->execute();
        $stmtordem->execute();

        $contatos = $stmt->fetchAll();
        $produtos = $stmtprod->fetchAll();
        $ordem_servicos = $stmtordem->fetchAll();
    }
        
    if(!empty($data)){   

        if($data["type"] === "entregue"){
            $id = $data["id"];
            $query = "UPDATE ordem_servico SET entregue = true WHERE id= :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);

            try {
                $stmt->execute();
                $_SESSION["msg"] = "Atualizado como entregue!";
        
            } catch (PDOException $e) {
                echo $e->getMessage();
            }


            header("location:" .$BASE_URL. "../ordem_servico_listar.php");

        }
        if($data["type"] === "confirmar_exclusao"){
            
            $cpf = limparCaracteresEspeciais( $data["cpf"]);


            $query = "SELECT * FROM contatos WHERE cpf = :cpf";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(":cpf", $cpf);
            $stmt->execute();
            $contato = $stmt->fetch();


            $corpo = "
            <h2>Exclusão</h2>
            <p><strong>" .$contato["nome"] ."</strong></p>
          
            <p><strong>Apartir deste momento, seus dados foram excluidos da nossa base.</p>
            ";

            $stmt2 = $conn->prepare("select * FROM ordem_servico WHERE id_cliente = :id");
            $stmt2->bindParam(":id", $contato["id"]);
            $stmt2->execute();
            $ordem = $stmt2->fetch();
            
            if ($ordem) {
                $id_default = "1"; 
                $stmt2 = $conn->prepare("update ordem_servico set id_cliente = :id_default WHERE id_cliente = :id_cliente ");
                $stmt2->bindParam(":id_cliente", $id);
                $stmt2->bindParam(":id_default", $contato["id"]);
                $stmt2->execute();   
            }
            
            $query = "DELETE  FROM contatos WHERE id= :id";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $contato["id"]);
            $stmt->execute();

            enviarEmail($contato["email"], $contato["nome"], $corpo);
       
            header("location:https://media.tenor.com/buPx8dUsXH8AAAAM/jake-gyllenhaal-bye-bye.gif");
        }


        if($data["type"] === "confirmar_orcamento"){
            $id = $data["id"];
            $status_atual = "Serviço";
            $stmt2 = $conn->prepare("UPDATE ordem_servico SET status_atual = :status_atual WHERE id = :id");
            $stmt2->bindParam(":id", $data["id"]);
            $stmt2->bindParam(":status_atual", $status_atual);
            $stmt2->execute();

            header("location:https://i.pinimg.com/originals/3d/92/cf/3d92cf4be9b5fe218db9d64fc9f85ec0.gif");
        }

        if($data["type"] === "solicita_exclusao"){
            
             
            $cpf = limparCaracteresEspeciais( $data["cpf"]);


            $query = "SELECT * FROM contatos WHERE cpf = :cpf";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(":cpf", $cpf);
            $stmt->execute();
            $contato = $stmt->fetch();


            $corpo = "
            <h2>Solicitação de exclusão</h2>
            <p><strong>Cliente:</strong>" .$contato["nome"] ."</p>
            <p><strong>cpf:</strong>".$cpf."</p>
            <p><strong>Foi solicitado a exclusão dos seu dados, para prosseguir, acesse o seguinte link:</p>
            <p>
                <a href='http://localhost/GorilaxTech/confirmar_exclusao_bd.php?id=".$contato["id"]."' target='_blank'>
                    http://localhost/GorilaxTech/confirmar_exclusao_bd.php?id=".$contato["id"]."
                </a>
            </p>
            <p>Agradecemos a confiança!</p>
            ";


            enviarEmail($contato["email"], $contato["nome"], $corpo);


        
            try {
                $stmt->execute();

                header("location:" .$BASE_URL. "../index.php");

            } catch (PDOException $e) {
                echo "Algo não saiu como planejado";
                echo "<br>";
                echo $e;
            }
        }


       if (!empty($data['pesquisa_inteligente'])) {
            // pega o id enviado via POST
            $idCliente = $data['pesquisa_inteligente'];

            // salva na sessão para ser consumido pela tela de listagem
            $_SESSION['pesquisa_inteligente'] = $idCliente;

            // redireciona para a listagem (sem parâmetros GET) e interrompe execução
            header("Location: " . $BASE_URL . "../clientes_listar.php");
            exit;
        }

        if($data["type"] === "create"){
            $nome = $data["nome"];
            $email = $data["email"];
            $telefone = limparCaracteresEspeciais($data["telefone"]);          
            $cpf = limparCaracteresEspeciais($data["cpf"]);           
            $empresa = $data["empresa"];           
            $cnpj = limparCaracteresEspeciais($data["cnpj"]);           
            $cep = limparCaracteresEspeciais($data["cep"]); 
            $complemento = $data["complemento"];          

            $query = "INSERT INTO contatos (nome, email, telefone, cpf, empresa, cnpj, cep, complemento ) VALUES (:nome, :email, :telefone, :cpf, :empresa, :cnpj, :cep, :complemento)";
            
            $stmt = $conn->prepare($query);

            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":telefone", $telefone);
            $stmt->bindParam(":cpf", $cpf);
            $stmt->bindParam(":empresa", $empresa);
            $stmt->bindParam(":cnpj", $cnpj);
            $stmt->bindParam(":cep", $cep);
            $stmt->bindParam(":complemento", $complemento);


            $corpo = "
            <h2>Cadastro Realizado</h2>
            <p>Seja Bem vindo a Gorilax Tech<strong> " .$nome ."</strong></p>
            <p><strong>É com muito prazer que o recebemos em nossa base de dados </p>
        
            ";

            enviarEmail($email, $nome, $corpo);

            try {
                $stmt->execute();
                $_SESSION["msg"] = "Cadastrado com sucesso!";

                header("location:" .$BASE_URL. "../clientes_listar.php");

            } catch (PDOException $e) {
                echo "CPF ou Email Já existe em nosso sistema";
            }
        
        }
        if($data["type"] === "create_prod"){
            $nome = $data["nome"];
            $tipo = $data["tipo"];
            $marca = $data["marca"];
            $preco_custo = formatarRealBD($data["preco_custo"]);   
            $preco_final = formatarRealBD($data["preco_final"]);            
            $descricao = $data["descricao"];       
            $query = "INSERT INTO produtos (nome, tipo, marca, preco_custo, preco_final, descricao ) VALUES ( :nome, :tipo, :marca, :preco_custo, :preco_final, :descricao)";           
           
            $stmt = $conn->prepare($query);

            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":tipo", $tipo);
            $stmt->bindParam(":marca", $marca);
            $stmt->bindParam(":preco_custo", $preco_custo);
            $stmt->bindParam(":preco_final", $preco_final);
            $stmt->bindParam(":descricao", $descricao);
         
            try {
                $stmt->execute();
                $_SESSION["msg"] = "Produto cadastrado com sucesso!";

                header("location:" .$BASE_URL. "../produtos_listar.php");

            } catch (PDOException $e) {
                echo "Algo não saiu como planejado";
                echo "<br>";
                echo $e;
            }
        
        }
         
        if($data["type"] === "enviar_servico"){
            $id = $data["id"];

            $query = "SELECT id_cliente FROM ordem_servico WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $contato = $stmt->fetch();


            $id_cliente =  $contato["id_cliente"];

            $query = "SELECT email, nome FROM contatos WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id_cliente);
            $stmt->execute();
            $contato = $stmt->fetch();


            
            $corpo = "
            <h2>Equipamento Bancada</h2>
            <p><strong>Cliente:</strong>" .$contato["nome"] ."</p>
            <p><strong>Seu equipamento se encontra na bancada, segue link para acesso:</p>
            <p>
                <a href='https://www.twitch.tv/gorilaxtech' target='_blank'>
                    https://www.twitch.tv/gorilaxtech
                </a>
            </p>
            ";

            enviarEmail($contato["email"], $contato["nome"], $corpo);

            $status_atual = "Finalizado";

            $query_os = "UPDATE ordem_servico SET status_atual = :status_atual WHERE id = :id ";
            
            $stmt_os = $conn->prepare($query_os);
            $stmt_os->bindParam(":id", $id);
            $stmt_os->bindParam(":status_atual", $status_atual);
            $stmt_os->execute();


            try {
                $_SESSION["msg"] = "Link criado e enviado com sucesso!";

                header("location:" .$BASE_URL. "../ordem_servico_listar.php");

            } catch (PDOException $e) {
                echo "Algo não saiu como planejado";
                echo "<br>";
                echo $e;
            }


         }

        if($data["type"] === "create_ordem_servico"){
            $id_cliente = $data["id"];
            $equipamento_cliente = $data["equipamento"];
            $data_abertura = $data["data_entrada"];
            $pre_diagnostico = $data["pre_diagnostico"];
            $observacao = $data["observacao"];
            $status_atual = "O.S";


            $query = "INSERT INTO ordem_servico (id_cliente, equipamento_cliente, data_abertura, pre_diagnostico, observacao, status_atual) VALUES ( :id_cliente, :equipamento_cliente, :data_abertura, :pre_diagnostico, :observacao, :status_atual)";
            
            $stmt = $conn->prepare($query);

            $stmt->bindParam(":id_cliente", $id_cliente);
            $stmt->bindParam(":equipamento_cliente", $equipamento_cliente);
            $stmt->bindParam(":data_abertura", $data_abertura);
            $stmt->bindParam(":pre_diagnostico", $pre_diagnostico);
            $stmt->bindParam(":observacao", $observacao);
            $stmt->bindParam(":status_atual", $status_atual);
           
         
            try {
                $stmt->execute();
                $_SESSION["msg"] = "Ordem de Serviço cadastrada com sucesso!";

                header("location:" .$BASE_URL. "../ordem_servico_listar.php");

            } catch (PDOException $e) {
                echo "Algo não saiu como planejado";
                echo "<br>";
                echo $e;
            }
        }

        if($data["type"] === "create_orcamento"){
            
            $total = 0;

            if (isset($_POST['produtos_string']) && !empty($_POST['produtos_string'])) {
                $produtos = explode(',', $_POST['produtos_string']);

                foreach ($produtos as $item) {
                    list($id, $qtd) = explode(':', $item);

                    // 🔍 Buscar preço do produto no banco
                    $query = "SELECT * FROM produtos WHERE id = :id";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(":id", $id);
                    $stmt->execute();

                    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($produto) {
                        $preco = floatval($produto['preco_final']);
                        $subtotal = $preco * intval($qtd);
                        $total += $subtotal;
                    }
                }
            }

             

            $id = $data["id"];
            $observacao = $data["observacao"];
            $status_atual = "Orçamento";
            $produtos_servicos = "produtos_servicos";
            $data_orcamento = $data["data_orcamento"];
            $produtos_servicos= $_POST['produtos_string'];
            
            $query = "UPDATE ordem_servico SET valor_total_orcamento = :valor_total_orcamento, produtos_servicos = :produtos_servicos, status_atual = :status_atual, observacao = :observacao, data_orcamento = :data_orcamento WHERE id = :id ";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":status_atual", $status_atual);
            $stmt->bindParam(":valor_total_orcamento", $total);
            $stmt->bindParam(":produtos_servicos", $produtos_servicos);
            $stmt->bindParam(":observacao", $observacao);
            $stmt->bindParam(":data_orcamento", $data_orcamento);
            $stmt->execute();


            $query = "SELECT * FROM ordem_servico WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $ordem = $stmt->fetch();

            $id =  $ordem["id_cliente"];

            $query = "SELECT email, nome FROM contatos WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $contato = $stmt->fetch();
            
        $corpo = "
                <h2>Resumo do Orçamento</h2>
                <p><strong>Cliente:</strong> " . $contato["nome"] . "</p>
                <p><strong>Produto principal:</strong> " . $ordem["equipamento_cliente"] . "</p>
                <p><strong>Diagnóstico:</strong> " . $ordem["observacao"] . "</p>
            ";

            $totalGeral = 0; // acumulador do total

            // 🔽 Adiciona os produtos
            if (isset($_POST['produtos_string']) && !empty($_POST['produtos_string'])) {
                $produtos = explode(',', $_POST['produtos_string']);
                
                $corpo .= "<h3>Itens do Orçamento:</h3><ul>";

                foreach ($produtos as $item) {
                    list($id, $qtd) = explode(':', $item);

                    // Buscar nome e preço do produto
                    $query = "SELECT * FROM produtos WHERE id = :id";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(":id", $id);
                    $stmt->execute();

                    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($produto) {
                        $nomeProduto = $produto['nome'];
                        $preco = floatval($produto['preco_final']);
                        $subtotal = $preco * intval($qtd);
                        $totalGeral += $subtotal;

                        $corpo .= "<li>$nomeProduto — Quantidade: $qtd — Valor unitário: R$ " . number_format($preco, 2, ',', '.') . " — Subtotal: R$ " . number_format($subtotal, 2, ',', '.') . "</li>";
                    } else {
                        $corpo .= "<li>Produto não encontrado (ID $id)</li>";
                    }
                }

                $corpo .= "</ul>";
            }

            // 🔽 Total geral
            $corpo .= "
                <p><strong>Total:</strong> R$ " . number_format($totalGeral, 2, ',', '.') . "</p>
                <p><strong>Para confirmar o seu orçamento, clique no link abaixo:</strong></p>
                <p>
                    <a href='http://localhost/GorilaxTech/confirmar_orcamento.php?id=" . $ordem["id"] . "' target='_blank'>
                        http://localhost/GorilaxTech/confirmar_orcamento.php?id=" . $ordem["id"] . "
                    </a>
                </p>
            ";


            enviarEmail($contato["email"], $contato["nome"], $corpo);
        
            try {
                $stmt->execute();
                $_SESSION["msg"] = "Ordem de Serviço atualizada para orçamento com sucesso!";

                header("location:" .$BASE_URL. "../ordem_servico_listar.php");

            } catch (PDOException $e) {
                echo "Algo não saiu como planejado";
                echo "<br>";
                echo $e;
            }
        
        }
    
        else if($data["type"] === "edit"){
            $id = $data["id"];
            $nome = $data["nome"];
            $email = $data["email"];
            $telefone = limparCaracteresEspeciais($data["telefone"]);          
            $cpf = limparCaracteresEspeciais($data["cpf"]);           
            $empresa = $data["empresa"];           
            $cnpj = limparCaracteresEspeciais($data["cnpj"]);           
            $cep = limparCaracteresEspeciais($data["cep"]); 
            $complemento = $data["complemento"];            


            $query = "UPDATE contatos SET nome= :nome, email = :email, telefone = :telefone,  cpf = :cpf, empresa = :empresa, cnpj = :cnpj, cep = :cep, complemento = :complemento  WHERE id= :id";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":nome", var: $nome);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":telefone", $telefone);
            $stmt->bindParam(":cpf", $cpf);
            $stmt->bindParam(":empresa", $empresa);
            $stmt->bindParam(":cnpj", $cnpj);
            $stmt->bindParam(":cep", $cep);
            $stmt->bindParam(":complemento", $complemento);
            
            try {
                $stmt->execute();
                $_SESSION["msg"] = "Cadastro Atualizado com sucesso!";
                header("location:" .$BASE_URL. "../clientes_listar.php");
        
            } catch (PDOException $e) {
                echo "CPF ou Email Já existe em nosso sistema";
            }
            

        }

         else if($data["type"] === "edit_prod"){
           

            $id = $data["id"];
            $nome = $data["nome"];
            $tipo = $data["tipo"];
            $marca = $data["marca"];
            $preco_custo = formatarRealBD($data["preco_custo"]);   
            $preco_final = formatarRealBD($data["preco_final"]);           
            $descricao = $data["descricao"];           
    
           


            $query = "UPDATE produtos SET nome= :nome, tipo = :tipo, marca = :marca,  preco_custo = :preco_custo, preco_final = :preco_final, descricao = :descricao  WHERE id= :id";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":nome", var: $nome);
            $stmt->bindParam(":tipo", $tipo);
            $stmt->bindParam(":marca", $marca);
            $stmt->bindParam(":preco_custo", $preco_custo);
            $stmt->bindParam(":preco_final", $preco_final);
            $stmt->bindParam(":descricao", $descricao);
         
            
            try {
                $stmt->execute();
                $_SESSION["msg"] = "Produto Atualizado com sucesso!";
                header("location:" .$BASE_URL. "../produtos_listar.php");
        
            } catch (PDOException $e) {
                echo $e;
            }
            

        }



        else if($data["type"] === "edit_ordem"){
           

            $id = $data["id"];
            
            $equipamento_cliente = $data["equipamento_cliente"];
            $complementos = $data["complementos"];
            $data_entrada = $data["data_entrada"];
            $pre_diagnostico = $data["pre_diagnostico"];
            $observacao = $data["observacao"];
           


            $query = "UPDATE ordem_servico SET equipamento_cliente= :equipamento_cliente, complementos = :complementos, data_abertura = :data_entrada,  pre_diagnostico = :pre_diagnostico, observacao = :observacao  WHERE id= :id";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":equipamento_cliente", var: $equipamento_cliente);
            $stmt->bindParam(":complementos", $complementos);
            $stmt->bindParam(":data_entrada", $data_entrada);
            $stmt->bindParam(":pre_diagnostico", $pre_diagnostico);
            $stmt->bindParam(":observacao", $observacao);
        
         
            
            try {
                $stmt->execute();
                $_SESSION["msg"] = "Ordem de Serviço Atualizada com sucesso!";
                header("location:" .$BASE_URL. "../ordem_servico_listar.php");
        
            } catch (PDOException $e) {
                echo $e;
            }
            

        }










        
        else if($data["type"] === "delete"){
            $id = $data["id"];

            $stmt2 = $conn->prepare("select * FROM ordem_servico WHERE id_cliente = :id");
            $stmt2->bindParam(":id",$id);
            $stmt2->execute();
            $ordem = $stmt2->fetch();
          
            if ($ordem) {
                $id_default = "1"; 
                $stmt2 = $conn->prepare("update ordem_servico set id_cliente = :id_default WHERE id_cliente = :id_cliente ");
                $stmt2->bindParam(":id_cliente", $id);
                $stmt2->bindParam(":id_default", $id_default);
                $stmt2->execute();   
            }
            
            $query = "DELETE FROM contatos WHERE id= :id";
            $stmt = $conn->prepare(query: $query);
            $stmt->bindParam(":id", $id);
     
            try {
                $stmt->execute();
                $_SESSION["msg"] = "Cadastro removido com sucesso!";
                
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            header("location:" .$BASE_URL. "../clientes_listar.php");
        }

        else if($data["type"] === "delete_prod"){
            $id = $data["id"];
            $query = "DELETE FROM produtos WHERE id= :id";
            $stmt = $conn->prepare(query: $query);
            $stmt->bindParam(":id", $id);

            try {
                $stmt->execute();
                $_SESSION["msg"] = "Produto removido com sucesso!";
        
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            header("location:" .$BASE_URL. "../produtos_listar.php");
        }
            else if($data["type"] === "delete_ordem_servico"){
            $id = $data["id"];
            $query = "DELETE FROM ordem_servico WHERE id= :id";
            $stmt = $conn->prepare(query: $query);
            $stmt->bindParam(":id", $id);

            try {
                $stmt->execute();
                $_SESSION["msg"] = "Ordem de Serviço removida com sucesso!";
        
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            header("location:" .$BASE_URL. "../ordem_servico_listar.php");
        }
        else if($data["type"] === "login"){

            $email = $data["email"];
            $senha = $data["senha"];
            $query = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ( $senha == $usuario["senha"]) {

                $_SESSION["usuario"] = $usuario["nome"];
                header("location:" .$BASE_URL. "../clientes_listar.php");

                exit();
            } else {
                
                echo "senha ou email incorretos";
            }
             
        }
        else if($data["type"] === "alterar_senha") {

            $email = $data["email"];
            $frase = $data["frase"];
            $novaSenha = $data["senha"];

            // Frase correta de validação (personalize como quiser)
            $frase_correta = "GorilaxTech10";

            if (trim(strtolower($frase)) !== strtolower($frase_correta)) {
                echo "❌ Frase de validação incorreta.";
                exit;
            }

            // Verifica se o usuário existe
            $query = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Atualiza a senha sem hash
                $update = "UPDATE usuarios SET senha = :senha WHERE email = :email";
                $stmt2 = $conn->prepare($update);
                $stmt2->bindParam(":senha", $novaSenha);
                $stmt2->bindParam(":email", $email);
                $stmt2->execute();
                header("location:" .$BASE_URL. "../index.php");

            } else {
                echo "❌ Usuário não encontrado.";
            }
        }

     
    }
    else{
        $id;

        if(!empty($_GET["id"])){
            $id = $_GET["id"];
        }

        if(!empty($id)){

            $query = "SELECT * FROM contatos WHERE id = :id";
            $queryprod = "SELECT * FROM produtos WHERE id = :id";
            $queryordem = "SELECT * FROM ordem_servico WHERE id = :id";

            $stmt = $conn->prepare($query);
            $stmtprod = $conn->prepare($queryprod);
            $stmtordem = $conn->prepare($queryordem);

            $stmt->bindParam(":id", $id);
            $stmtprod->bindParam(":id", $id);
            $stmtordem->bindParam(":id", $id);


            $stmt->execute();
            $stmtprod->execute();
            $stmtordem->execute();

            $contato = $stmt->fetch();
            $produto = $stmtprod->fetch();
            $ordem = $stmtordem->fetch();

            $querycliente = "SELECT * FROM contatos WHERE id = :id_cliente";
            $stmtbuscarcliente = $conn->prepare($querycliente);
            $stmtbuscarcliente->bindParam(":id_cliente", $ordem["id_cliente"]);
            $stmtbuscarcliente->execute();
            $cliente = $stmtbuscarcliente->fetch();



        }else{
                $idClienteSelecionado = $_SESSION['pesquisa_inteligente'] ?? null;

                if (!empty($idClienteSelecionado)) {
                    // usa o id da sessão para filtrar
                    $id = $idClienteSelecionado;

                    $queryprod  = "SELECT * FROM produtos WHERE id = :id ORDER BY id DESC"; 
                    $query  = "SELECT * FROM contatos WHERE id = :id ORDER BY id DESC"; 
                    $queryordem  = "SELECT * FROM ordem_servico WHERE id = :id ORDER BY id DESC";         

                    $stmt = $conn->prepare($query);
                    $stmtprod = $conn->prepare($queryprod);
                    $stmtordem = $conn->prepare($queryordem);

                    $stmt->bindParam(":id", $id);
                    $stmtprod->bindParam(":id", $id);
                    $stmtordem->bindParam(":id", $id);

                    $stmt->execute();
                    $stmtprod->execute();
                    $stmtordem->execute();

                    $contatos = $stmt->fetchAll();
                    $produtos = $stmtprod->fetchAll();
                    $ordem_servicos = $stmtordem->fetchAll();

                    // limpa a sessão para não manter o filtro sempre
                    unset($_SESSION['pesquisa_inteligente']);

                } 
   
    
   
        }
    }
    $conn = null;
    
?>