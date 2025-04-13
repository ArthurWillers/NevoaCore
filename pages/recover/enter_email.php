<?php session_start(); 

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $_POST['email_recover_password'] = $_SESSION['user_email'];
    $_POST['submit_recover_password'] = true;
    header('Location: ../../actions/recover_password/send_email_recover_password.php');
    exit();
}
?>

<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="../../assets/img/nevoa_logo.png">
  <?php include '../../includes/bootstrap_styles.php' ?>
  <link rel="stylesheet" href="../../assets/css/bootstrap_custom.css">
  <title>Alterar Senha</title>
</head>

<body>
  <?php include '../../includes/toast.php' ?>

  <nav class="navbar navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../../assets/img/nevoa_logo.png" alt="Logo" width="35" height="35" class="d-inline-block">
        <span class="ms-2 align-middle">NévoaCore</span>
      </a>
      <a class="btn btn-outline-light" href="../../index.php">Voltar</a>
    </div>
  </nav>

  <div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card shadow-lg p-4">
      <h3 class="text-center mb-4">Alterar Senha</h3>
      <form method="POST" action="../../actions/recover_password/send_email_recover_password.php">
        <div class="input-group mb-3">
          <input type="email" name="email_recover_password" class="form-control" placeholder="Digite seu E-mail" required>
        </div>
        <button type="submit" name="submit_recover_password" class="btn btn-primary w-100" disabled>Entrar</button>
      </form>
    </div>
  </div>

  <?php include '../../includes/footer.php' ?>
  <?php include '../../includes/bootstrap_script.php' ?>
  <script src="../../assets/js/toast.js"></script>
  <script src="../../assets/js/enter_email.js"></script>
</body>

</html>