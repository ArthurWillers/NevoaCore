<?php
include '../includes/session_start.php';
include '../config/db_connection.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  session_unset();
  $_SESSION['message'] = "Você não está logado. Faça login para acessar o painel.";
  $_SESSION['message_type'] = "danger";
  header("Location: ../index.php");
  exit();
}

$conn = open_connection();
$result = mysqli_execute_query($conn, "SELECT role FROM user WHERE email = ?", [$_SESSION['user_email']]);

if ($result) {
  $row = mysqli_fetch_assoc($result);
  if ($row['role'] !== 'admin') {
    close_connection($conn);
    $_SESSION['message'] = "Você não tem permissão para acessar esta página.";
    $_SESSION['message_type'] = "danger";
    header("Location: ../pages/dashboard.php");
    exit();
  }
} else {
  close_connection($conn);
  session_unset();
  $_SESSION['message'] = "Erro ao verificar permissões.";
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
  <title>Admin - NévoaCore</title>
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
              <li><a class="dropdown-item" href="../pages/dashboard.php">Dashboard</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <h1 class="text-center w-100 py-3">Pagina de Administrador</h1>
    <div class="row">
      <div class="col-12">
        <h2 class="text-center">Gerenciar Usuários</h2>
        <table class="table table-bordered mt-3 table-striped">
          <thead class="text-center">
            <tr>
              <th scope="col">Email</th>
              <th scope="col">Nome de Usuário</th>
              <th scope="col">Cargo</th>
              <th scope="col">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $user_query = mysqli_query($conn, "SELECT email, username, role FROM user");
            while ($row = mysqli_fetch_assoc($user_query)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['email']) . "</td>";
              echo "<td>" . htmlspecialchars($row['username']) . "</td>";
              echo "<td>" . htmlspecialchars($row['role']) . "</td>";
              echo "<td class='text-center'>
                      <div class='d-flex justify-content-center gap-2'>
                        <button title=\"Editar\" class=\"btn btn-primary\" onclick=\"open_update_modal('" . htmlspecialchars($row['email']) . "','" . htmlspecialchars($row['username']) . "','" . htmlspecialchars($row['role']) . "')\">
                          <i class='bi bi-pencil'></i>
                        </button>
                        <button title=\"Excluir\" class=\"btn btn-danger\" onclick=\"open_delete_modal('" . htmlspecialchars($row['email']) . "','" . htmlspecialchars($row['username']) . "','" . htmlspecialchars($row['role']) . "')\">
                          <i class='bi bi-trash3'></i>
                        </button>
                      </div>
                    </td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Editar Usuário -->
    <div class="modal fade" id="update_account_modal" tabindex="-1" aria-labelledby="update_account_label" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="update_account_label">Editar Dados do Usuário</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="./admin_update_account.php" method="POST">
              <div class="mb-3">
                <label class="form-label">Email Atual:</label>
                <input type="email" class="form-control" id="original_email" name="original_email" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Novo Email (opcional):</label>
                <input type="email" class="form-control" id="update_email" name="update_email" maxlength="255" placeholder="Deixe em branco para não alterar">
              </div>
              <div class="mb-3">
                <label class="form-label">Nome de Usuário:</label>
                <input type="text" class="form-control" id="original_username" name="original_username" maxlength="255" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Novo Nome de Usuário (opcional):</label>
                <input type="text" class="form-control" id="update_username" name="update_username" maxlength="255" placeholder="Deixe em branco para não alterar">
              </div>
              <div class="mb-3">
                <label class="form-label">Cargo:</label>
                <select class="form-select" id="update_user_info_role" name="update_role" required>
                  <option value="admin">admin</option>
                  <option value="user">user</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Nova Senha (opcional):</label>
                <input type="text" class="form-control" id="update_user_info_password" name="update_password" placeholder="Deixe em branco para manter">
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-primary" name="update_submit">Salvar Alterações</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Excluir Usuário -->
    <div class="modal fade" id="delete_account_modal" tabindex="-1" aria-labelledby="delete_account_label" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="delete_account_label">Excluir Conta</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="./admin_delete_account.php" method="POST">
              <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="text" class="form-control" id="delete_email" name="delete_email" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Nome de Usuário:</label>
                <input type="text" class="form-control" id="delete_user_info_username" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Cargo:</label>
                <input type="text" class="form-control" id="delete_user_info_role" readonly>
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
      function open_update_modal(email, username, role) {
        document.getElementById("update_email").value = "";
        document.getElementById("update_username").value = "";
        document.getElementById("original_email").value = email;
        document.getElementById("original_username").value = username;
        document.getElementById("update_user_info_role").value = role;
        document.getElementById("original_email").value = email;
        const modalUpdate = new bootstrap.Modal(document.getElementById("update_account_modal"));
        modalUpdate.show();
      }

      function open_delete_modal(email, username, role) {
        document.getElementById("email_feedback").classList.add("d-none");
        document.getElementById("delete_confirm_email").value = "";
        document.getElementById("delete_email").value = email;
        document.getElementById("delete_user_info_username").value = username;
        document.getElementById("delete_user_info_role").value = role;
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