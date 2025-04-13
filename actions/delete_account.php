<?php 
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  session_unset();
  $_SESSION['message'] = "Você não está logado. Faça login para deletar a conta.";
  $_SESSION['message_type'] = "danger";
  header("Location: ../index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_submit'])) {
  // opening the database connection
  include '../config/db_connection.php';
  $conn = open_connection();

  // Prepare the SQL statement
  $sql = "DELETE FROM user WHERE email = ?";

  // Check if the user exists and process the result
  if (mysqli_execute_query($conn, $sql, [$_SESSION['user_email']])) {
    session_unset();
    $_SESSION['message'] = 'Conta excluída com sucesso';
    $_SESSION['message_type'] = 'success';
    header('Location: ../index.php');
    exit();
  } else {
    session_unset();
    $_SESSION['message'] = 'Erro ao excluir a conta';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/dashboard.php');
    exit();
  }
} else {
  session_unset();
  $_SESSION['message'] = 'Acesso não autorizado';
  $_SESSION['message_type'] = 'error';
  header('Location: ../index.php');
  exit();
}


?>