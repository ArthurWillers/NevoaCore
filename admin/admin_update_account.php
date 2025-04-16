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
  if (empty($_POST['original_email']) || empty($_POST['original_username']) || empty($_POST['update_role'])) {
    close_connection($conn);
    $_SESSION['message'] = "Erro ao processar a solicitação.";
    $_SESSION['message_type'] = "danger";
    header("Location: ./admin.php");
    exit();
  }
  if (empty($_POST['update_email']) && empty($_POST['update_username']) && empty($_POST['update_password'])) {
    close_connection($conn);
    $_SESSION['message'] = "Nenhum dado alterado.";
    $_SESSION['message_type'] = "danger";
    header("Location: ./admin.php");
    exit();
  }
  if (!filter_var($_POST['update_email'], FILTER_VALIDATE_EMAIL)) {
    close_connection($conn);
    $_SESSION['message'] = "Email inválido.";
    $_SESSION['message_type'] = "danger";
    header("Location: ./admin.php");
    exit();
  }

  $new_email = empty($_POST['update_email']) ? $_POST['original_email'] : $_POST['update_email'];
  $new_username = empty($_POST['update_username']) ? $_POST['original_username'] : $_POST['update_username'];
  $role = $_POST['update_role'];
  $password = isset($_POST['update_password']) ? password_hash($_POST['update_password'], PASSWORD_DEFAULT) : null;

  mysqli_begin_transaction($conn);
  try {
    // Verifica se o novo email já está em uso
    $check_email_result = mysqli_execute_query(
      $conn,
      "SELECT email FROM user WHERE email = ?",
      [$_POST['update_username']]
    );
    if (mysqli_num_rows($check_email_result) > 0) {
      throw new Exception("O novo email já está sendo utilizado.");
    }

    // Verifica se o novo username já está em uso
    $check_username_result = mysqli_execute_query(
      $conn,
      "SELECT username FROM user WHERE username = ?",
      [$_POST['update_email']]
    );
    if (mysqli_num_rows($check_username_result) > 0) {
      throw new Exception("O novo username já está sendo utilizado.");
    }

    // Atualiza os dados do usuário
    if (isset($password)) {
      mysqli_execute_query($conn, "UPDATE user SET email = ?, username = ?, password = ?, role = ? WHERE email = ?", [$new_email, $new_username, $password, $role, $_POST['original_email']]);
    } else {
      mysqli_execute_query($conn, "UPDATE user SET email = ?, username = ?, role = ? WHERE email = ?", [$new_email, $new_username, $role, $_POST['original_email']]);
    }

    mysqli_commit($conn);
    $_SESSION['message'] = "Usuário atualizado com sucesso.";
    $_SESSION['message_type'] = "success";
  } catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['message'] = "Erro ao atualizar: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
  }

  if ($_SESSION['user_email'] === $_POST['original_email']) {
    $_SESSION['user_email'] = $new_email;
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
