<?php session_start(); ?>

<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="../assets/img/nevoa_logo.png">
  <?php include '../includes/bootstrap_styles.php' ?>
  <link rel="stylesheet" href="../assets/css/bootstrap_custom.css">
  <title>Login</title>
</head>

<body>
  <?php include '../includes/toast.php' ?>
  <?php session_unset();?>

  <nav class="navbar navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../assets/img/nevoa_logo.png" alt="Logo" width="35" height="35" class="d-inline-block">
        <span class="ms-2 align-middle">NévoaCore</span>
      </a>
      <a class="btn btn-outline-light" href="../index.php">Voltar</a>
    </div>
  </nav>

  <div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card shadow-lg p-4">
      <h3 class="text-center mb-4">Login</h3>
      <form method="POST" action="../actions/login.php">

        <div class="input-group mb-3">
          <input type="email" name="email_login" class="form-control" placeholder="Digite seu E-mail" required>
        </div>

        <div class="input-group">
          <input id="password_login" type="password" name="password_login" class="form-control" placeholder="Digite sua senha" required>
          <button class="btn btn-outline-secondary" type="button" onclick="toggle_password_visibility('password_login', this)">
            <i class="bi bi-eye-slash"></i>
          </button>
        </div>
        <div class="form-text">
          <a href="./recover/enter_email.php" class="text-decoration-none">Esqueceu sua senha?</a>
        </div>

        <button type="submit" name="submit_login" class="btn btn-primary w-100 mt-3" disabled>Entrar</button>

        <div class="text-center mt-3">
          <span>Ainda não tem uma conta?</span>
          <a href="./register.php" class="text-decoration-none cursor-pointer">Cadastre-se</a>
        </div>
      </form>
    </div>
  </div>

  <?php include '../includes/footer.php' ?>
  <?php include '../includes/bootstrap_script.php' ?>
  <script src="../assets/js/login.js"></script>
  <script src="../assets/js/toast.js"></script>
  <script src="../assets/js/toggle_password_visibility.js"></script>
</body>

</html>