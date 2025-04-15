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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_submit'])) {
  $old_email = $_POST['original_email'];
  $new_email = $_POST['update_email']; // agora pode ser vazio
  $username = $_POST['update_username'];
  $role = $_POST['update_role'];
  $password = isset($_POST['update_password']) ? $_POST['update_password'] : null;
  $hashed_password = $password ? password_hash($password, PASSWORD_DEFAULT) : null;

  mysqli_begin_transaction($conn);
  try {
    // Verifica se o novo e-mail foi preenchido
    if (!empty($new_email)) {
      if ($password) {
        mysqli_execute_query(
          $conn,
          "UPDATE user SET email = ?, username = ?, role = ?, password = ? WHERE email = ?",
          [$new_email, $username, $role, $hashed_password, $old_email]
        );
      } else {
        mysqli_execute_query(
          $conn,
          "UPDATE user SET email = ?, username = ?, role = ? WHERE email = ?",
          [$new_email, $username, $role, $old_email]
        );
      }
      // Atualiza o email nas outras tabelas
      mysqli_execute_query(
        $conn,
        "UPDATE verification_code SET fk_user_email = ? WHERE fk_user_email = ?",
        [$new_email, $old_email]
      );
    } else {
      // Mantém o mesmo email, atualiza apenas username, role, senha
      if ($password) {
        mysqli_execute_query(
          $conn,
          "UPDATE user SET username = ?, role = ?, password = ? WHERE email = ?",
          [$username, $role, $hashed_password, $old_email]
        );
      } else {
        mysqli_execute_query(
          $conn,
          "UPDATE user SET username = ?, role = ? WHERE email = ?",
          [$username, $role, $old_email]
        );
      }
    }

    mysqli_commit($conn);
    $_SESSION['message'] = "Usuário atualizado com sucesso.";
    $_SESSION['message_type'] = "success";
  } catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['message'] = "Erro ao atualizar: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
  }
  close_connection($conn);
  header("Location: ./admin.php");
  exit();
} else {
  close_connection($conn);
  $_SESSION['message'] = "Erro ao processar a solicitação.";
  $_SESSION['message_type'] = "danger";
  header("Location: ./admin.php");
  exit();
}
