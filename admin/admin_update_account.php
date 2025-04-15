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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_submit'])){
  $email = $_POST['update_email'];
  $username = $_POST['update_username'];
  $role = $_POST['update_role'];
  $password = isset($_POST['update_password']) ? $_POST['update_password'] : null;
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Verifica se o usuário existe
  $result = mysqli_execute_query($conn, "SELECT * FROM user WHERE email = ?", [$email]);
  if (mysqli_num_rows($result) > 0) {
    // Atualiza o usuário
    if ($password) {
      $update_result = mysqli_execute_query($conn, "UPDATE user SET username = ?, role = ?, password = ? WHERE email = ?", [$username, $role, $hashed_password, $email]);
    } else {
      $update_result = mysqli_execute_query($conn, "UPDATE user SET username = ?, role = ? WHERE email = ?", [$username, $role, $email]);
    }

    if ($update_result) {
      $_SESSION['message'] = "Usuário atualizado com sucesso.";
      $_SESSION['message_type'] = "success";
    } else {
      $_SESSION['message'] = "Erro ao atualizar o usuário.";
      $_SESSION['message_type'] = "danger";
    }
  } else {
    $_SESSION['message'] = "Usuário não encontrado.";
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
