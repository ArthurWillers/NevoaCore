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
  <?php include './includes/toast.php' ?>

  <div class="container-fluid min-vh-100 text-center text-white bg-primary d-flex flex-column justify-content-center align-items-center">
    <img src="./assets/img/nevoa_logo.png" alt="NévoaCore Logo" width="120" class="mb-3" />
    <h1 class="display-4 fw-bold mb-3">NévoaCore</h1>
    <article class="lead">
      <p>A Névoa é o nosso lar.<br>
        A Névoa é a nossa proteção.</p>
      <p>A Névoa é a nossa força.<br>
        A Névoa é a nossa união.</p>
      <p>A Névoa é a nossa liberdade.<br>
        A Névoa é a nossa escolha.</p>
      <p>Na Névoa, somos todos iguais.<br>
        Na Névoa, somos todos livres.<br>
        Na Névoa, somos todos nós.<br>
        Na Névoa, somos todos um.<br>
        Na Névoa, somos todos.</p>
      <p>Das alturas, vemos tudo.<br>
        E no silêncio, decidimos.</p>
      <p>Eu sou a luz que ilumina o caminho.</p>
    </article>

    <div class="d-flex justify-content-center gap-3 mt-4">
      <a href="./pages/register.php" class="btn btn-outline-light btn-lg">Criar Conta</a>
      <a href="./pages/login.php" class="btn btn-outline-light btn-lg">Entrar</a>
    </div>
  </div>

  <footer class="border-top py-2 bg-body-tertiary">
    <div class="container d-flex justify-content-center align-items-center gap-3">
      <span>
        Desenvolvido por:
        <a href="https://github.com/ArthurWillers" target="_blank" class="text-decoration-none">
          <i class="bi bi-github"></i> Arthur Vinicius Willers
        </a>
      </span>
      <span>
        Repositório do projeto:
        <a href="https://github.com/ArthurWillers/NevoaCore" target="_blank" class="text-decoration-none">
          <i class="bi bi-code-slash"></i> NévoaCore
        </a>
      </span>
    </div>
  </footer>
  <?php include './includes/bootstrap_script.php' ?>
  <script src="./assets/js/toast.js"></script>
</body>

</html>