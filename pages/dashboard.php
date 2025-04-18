<?php
include '../includes/session_start.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  session_unset();
  $_SESSION['message'] = "Você não está logado. Faça login para acessar o painel.";
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
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar_content" aria-controls="navbar_content" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar_content">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle p-0" href="#" role="button" data-bs-toggle="dropdown">
              <img src="../assets/img/profile_image.png" alt="Perfil" class="rounded-circle" style="width:35px; height:35px;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="../actions/logout.php">Deslogar</a></li>
              <li><a class="dropdown-item" href="../actions/recover_password/send_email_recover_password.php">Alterar Senha</a></li>
              <li><a class="dropdown-item" href="javascript:void(0);" onclick="open_delete_modal('<?php echo htmlspecialchars($_SESSION['user_email']); ?>')">Excluir Conta</a></li>
              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li><a class="dropdown-item" href="../admin/admin.php">Página de Admin</a></li>
              <?php endif; ?>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="modal fade" id="delete_account_modal" tabindex="-1" aria-labelledby="delete_account_modal_label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="delete_account_modal_label">Excluir Conta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="../actions/delete_account.php" method="POST">
          <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="text" class="form-control" id="delete_email" name="delete_email" readonly>
              </div>
              <div class="mb-3">
                <label for="delete_confirm_email" class="form-label">Digite o e-mail para confirmar:</label>
                <input type="email" class="form-control" name="delete_confirm_email" id="delete_confirm_email" required>
                <div id="email_feedback" class="form-text text-danger d-none">O e-mail não confere.</div>
              </div>
              <div class="text-end">
                <button type="submit" id="delete_account_btn" class="btn btn-danger" name="delete_submit" disabled>Excluir Conta</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include '../includes/footer.php' ?>
  <?php include '../includes/bootstrap_script.php' ?>
  <script src="../assets/js/toast.js"></script>
  <script>
    function open_delete_modal(email) {
        document.getElementById("email_feedback").classList.add("d-none");
        document.getElementById("delete_confirm_email").value = "";
        document.getElementById("delete_email").value = email;
        const modal = new bootstrap.Modal(document.getElementById("delete_account_modal"));
        modal.show();
      }

      document.getElementById("delete_confirm_email").addEventListener("input", function() {
        const typedEmail = this.value;
        const userEmail = document.getElementById("delete_email").value;
        const feedback = document.getElementById("email_feedback");
        const deleteBtn = document.getElementById("delete_account_btn");

        if (typedEmail === "") {
          feedback.classList.add("d-none");
          deleteBtn.disabled = true;
        } else if (typedEmail === userEmail) {
          feedback.classList.add("d-none");
          deleteBtn.disabled = false;
        } else {
          feedback.classList.remove("d-none");
          deleteBtn.disabled = true;
        }
      });
  </script>
</body>

</html>