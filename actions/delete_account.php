<?php 
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  session_unset();
  $_SESSION['message'] = "Você não está logado. Faça login para deletar a conta.";
  $_SESSION['message_type'] = "danger";
  header("Location: ../index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_submit'])) {
  // Abrir a conexão com o banco de dados
  include '../config/db_connection.php';
  $conn = open_connection();

  // Preparar a instrução SQL
  $sql = "DELETE FROM user WHERE email = ?";

  // Verificar se o usuário existe e processar o resultado
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