<?php
include 'connection.php';

$termo = $_GET['term'] ?? '';

if ($termo !== '') {
    // Se for uma sequência de 5 ou mais números → busca por CPF
    if (preg_match('/\d{5,}/', $termo)) {
        $stmt = $conn->prepare("SELECT id, nome, cpf FROM contatos WHERE cpf LIKE :termo LIMIT 10");
    } else {
        // Caso contrário, busca por nome
        $stmt = $conn->prepare("SELECT id, nome, cpf FROM contatos WHERE nome LIKE :termo LIMIT 10");
    }

    $stmt->bindValue(':termo', "%$termo%");
    $stmt->execute();

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($resultados);
}
?>
