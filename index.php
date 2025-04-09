<?php 
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  header("Location: ../pages/dashboard.php");
  exit();
}
?>

<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="./assets/img/nevoa_logo.png">
  <?php include './includes/bootstrap_styles.php' ?>
  <link rel="stylesheet" href="./assets/css/bootstrap_custom.css">
  <title>NévoaCore</title>
</head>

<body>
  <div class="container-fluid min-vh-100 text-center text-white bg-primary d-flex flex-column justify-content-center align-items-center">
    <img src="./assets/img/nevoa_logo.png" alt="NévoaCore Logo" width="120" class="mb-4" />
    <h1 class="display-4 fw-bold">NévoaCore</h1>
    <p class="lead">Eu sou a luz que ilumina o caminho</p>
    <p class="lead">Um núcleo descentralizado para mentes livres</p>
    <div class="d-flex justify-content-center gap-3 mt-4">
      <a href="./pages/register.php" class="btn btn-outline-light btn-lg">Criar Conta</a>
      <a href="./pages/login.php" class="btn btn-outline-light btn-lg">Entrar</a>
    </div>
  </div>

  <?php include './includes/footer.php' ?>
  <?php include './includes/bootstrap_script.php' ?>
</body>

</html>