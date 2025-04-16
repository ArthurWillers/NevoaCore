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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_submit']) && isset($_POST['delete_confirm_email']) && ($_POST['delete_confirm_email'] === $_POST['delete_email'])) {
  $email = $_POST['delete_email'];

  // Verifica se o usuário existe
  $result = mysqli_execute_query($conn, "SELECT * FROM user WHERE email = ?", [$email]);
  if (mysqli_num_rows($result) > 0) {
    // Deleta o usuário
    $delete_result = mysqli_execute_query($conn, "DELETE FROM user WHERE email = ?", [$email]);

    if ($delete_result) {
      $_SESSION['message'] = "Usuário deletado com sucesso.";
      $_SESSION['message_type'] = "success";
    } else {
      $_SESSION['message'] = "Erro ao deletar o usuário.";
      $_SESSION['message_type'] = "danger";
    }
  } else {
    $_SESSION['message'] = "Usuário não encontrado.";
    $_SESSION['message_type'] = "danger";
  }

  if ($_SESSION['user_email'] === $email) {
    session_unset();
    $_SESSION['message'] = "Sua conta foi deletada.";
    $_SESSION['message_type'] = "danger";
    header("Location: ../index.php");
    exit();
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
