<?php
    include_once(__DIR__ . '/../config/url.php');
    include_once(__DIR__ . '/../config/process.php');



    //limpa a mensagem

    if(isset($_SESSION['msg'])){
        $printMsg = $_SESSION['msg'];
        $_SESSION['msg']='';
    }

     $publicas = [
    "index.php",
    "login.php",
    "alterar_senha.php",
    "solicita_exclusao_bd.php"
    ];

    $paginaAtual = basename($_SERVER["PHP_SELF"]);

    if (!in_array($paginaAtual, $publicas) && !isset($_SESSION["usuario"])) {
    header("Location: " . $BASE_URL . "login.php");
    exit();
    }

?>

<!DOCTYPE html>
<html lang="en" id="responsivo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastros</title>
  <!-- BOOTSTRAP -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous" />
  <!-- FONT AWESOME -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
  <!-- CSS -->
  <link rel="stylesheet" href="<?= $BASE_URL ?>../css/style.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>../css/responsivo.css">

  
    
</head>
<body>
