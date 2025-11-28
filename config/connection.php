<?php


if (defined('TEST_MODE') && TEST_MODE === true) {

    // Criamos um PDO FAKE que não usa driver nenhum
    $conn = new class extends PDO {
        public function __construct() {}
    };

    return; // impede execução da conexão real
}


//secrets


try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

?>
