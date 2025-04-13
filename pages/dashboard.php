<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  session_unset();
  $_SESSION['message'] = "Você não está logado. Faça login para acessar o painel.";
  $_SESSION['message_type'] = "danger";
  header("Location: ../index.php");
  exit();
}
?>

<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="../assets/img/nevoa_logo.png">
  <?php include '../includes/bootstrap_styles.php' ?>
  <link rel="stylesheet" href="../assets/css/bootstrap_custom.css">
  <title>Dashboard</title>
</head>

<body>
  <?php include '../includes/toast.php' ?>

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../assets/img/nevoa_logo.png" alt="Logo" width="35" height="35" class="d-inline-block">
        <span class="ms-2 align-middle">NévoaCore</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle p-0" href="#" role="button" data-bs-toggle="dropdown">
              <img src="../assets/img/profile_image.png" alt="Perfil" class="rounded-circle" style="width:35px; height:35px; object-fit:cover;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="../actions/logout.php">Deslogar</a></li>
              <li><a class="dropdown-item" href="">Alterar Senha</a></li>
              <li><a class="dropdown-item" href="">Excluir Conta</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <?php include '../includes/footer.php' ?>
  <?php include '../includes/bootstrap_script.php' ?>
  <script src="../assets/js/toast.js"></script>
</body>

</html>