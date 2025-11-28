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
  <link rel="stylesheet" href="css/style.css">
</head>

<div class="login-page">

    <div class="login-card">

        <h2 class="login-title">Acesso ao Sistema</h2>

        <?php if(isset($printMsg) && $printMsg != '') : ?>
            <p id="msg"><?= $printMsg?></p>
        <?php endif; ?>

        <form action="config/process.php" method="POST" id="login-form">
            <input type="hidden" name="type" value="login">

            <div class="input-group-lg">
                <i class="fas fa-user icon"></i>
                <input type="email" id="email" name="email" placeholder="Seu Email" required>
            </div>

            <div class="input-group-lg">
                <i class="fas fa-lock icon"></i>
                <input type="password" id="senha" name="senha" placeholder="Senha" required>
            </div>

            <div class="login-options">
                <label><input type="checkbox"> Lembrar-me</label>
                <a href="views/alterar_senha.php">Esqueceu a senha?</a>
            </div>

            <button type="submit" class="login-btn">Entrar</button>
        </form>

        <div class="signup-link">
            <p>Remover seus dados? <a href="views/solicita_exclusao_bd.php">Clique aqui</a></p>
        </div>

    </div>

</div>

</body>
</html>
