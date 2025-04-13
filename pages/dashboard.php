<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  session_unset();
  $_SESSION['message'] = "Você não está logado. Faça login para deletar a conta.";
  $_SESSION['message_type'] = "danger";
  header("Location: ../index.php");
  exit();
}

$_SESSION['email_recover_password'] = $_SESSION['user_email'] ?? null;
?>

<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="../assets/img/nevoa_logo.png">
  <?php include '../includes/bootstrap_styles.php' ?>
  <link rel="stylesheet" href="../assets/css/bootstrap_custom.css">
  <title>Dashboard - NévoaCore</title>
</head>

<body>
  <?php include '../includes/toast.php' ?>

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
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
              <img src="../assets/img/profile_image.png" alt="Perfil" class="rounded-circle" style="width:35px; height:35px;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="../actions/logout.php">Deslogar</a></li>
              <li><a class="dropdown-item" href="../actions/recover_password/send_email_recover_password.php">Alterar Senha</a></li>
              <li><a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#excluirContaModal">Excluir Conta</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Delete account modal -->
  <div class="modal fade" id="excluirContaModal" tabindex="-1" aria-labelledby="excluirContaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="excluirContaModalLabel">Excluir Conta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="excluirContaForm" action="../actions/delete_account.php" method="POST">
            <div class="mb-3">
              <label for="confirm_email" class="form-label">Digite seu e-mail para confirmar:</label>
              <input type="email" class="form-control" name="delete_confirm_email" id="delete_confirm_email" required>
              <div id="emailFeedback" class="form-text text-danger d-none">O e-mail não confere.</div>
            </div>
            <button type="submit" id="deleteAccountBtn" class="btn btn-danger" name="delete_submit" disabled>Excluir Conta</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include '../includes/footer.php' ?>
  <?php include '../includes/bootstrap_script.php' ?>
  <script src="../assets/js/toast.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const confirmEmailInput = document.getElementById('delete_confirm_email');
      const deleteBtn = document.getElementById('deleteAccountBtn');
      const emailFeedback = document.getElementById('emailFeedback');
      
      // Get the current logged-in user's email from the PHP session
      const userEmail = "<?php echo $_SESSION['user_email'] ?? ''; ?>";
      
      confirmEmailInput.addEventListener('input', () => {
        if (confirmEmailInput.value === userEmail) {
          deleteBtn.disabled = false;
          emailFeedback.classList.add('d-none');
        } else {
          deleteBtn.disabled = true;
          if (confirmEmailInput.value.trim() !== '') {
            emailFeedback.classList.remove('d-none');
          } else {
            emailFeedback.classList.add('d-none');
          }
        }
      });
    });
  </script>
</body>

</html>