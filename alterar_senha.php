
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
  <!-- BOOTSTRAP -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous" />
  <!-- FONT AWESOME -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
  <!-- CSS -->
  <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="container" style="max-width: 500px; margin-top: 50px;">
    <h2 style="text-align:center;">Alterar Senha</h2>
    <form action="config/process.php" method="POST" style="margin-top:30px;">
        <input type="hidden" name="type" value="alterar_senha">

        <div class="form-group" style="margin-bottom:15px;">
            <label for="email"><strong>Email:</strong></label>
            <input type="email" name="email" id="email" class="form-control" required placeholder="Digite seu email">
        </div>

        <div class="form-group" style="margin-bottom:15px;">
            <label for="frase"><strong>Frase de validação:</strong></label>
            <input type="text" name="frase" id="frase" class="form-control" required placeholder="Ex: Minha cor favorita é azul">
        </div>

        <div class="form-group" style="margin-bottom:15px;">
            <label for="senha"><strong>Nova Senha:</strong></label>
            <input type="password" name="senha" id="senha" class="form-control" required placeholder="Digite a nova senha">
        </div>

        <div style="text-align:center;">
            <button type="submit" class="btn btn-primary" style="width:100%;">Salvar Nova Senha</button>
        </div>
    </form>
</div>

</body>
</html>
