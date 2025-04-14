<?php
include '../includes/session_start.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_login'])) {

  $email_login = isset($_POST['email_login']) ? $_POST['email_login'] : '';
  $password_login = isset($_POST['password_login']) ? $_POST['password_login'] : '';

  // Validar os campos de entrada
  if (empty($email_login) || empty($password_login)) {
    $_SESSION['message'] = 'Todos os campos devem ser preenchidos';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/login.php');
    exit();
  }

  // Abrir a conexão com o banco de dados
  include  '../config/db_connection.php';
  $conn = open_connection();

  // Preparar a instrução SQL
  $sql = "SELECT * FROM user WHERE email = ?";

  // Executar a instrução SQL
  $result = mysqli_execute_query($conn, $sql, [$email_login]);

  // Verificar se o usuário existe e processar o resultado
  if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Fechar a conexão com o banco de dados
    close_connection($conn);

    // Verificar a senha
    if (password_verify($password_login, $user['password'])) {
      // Login realizado com sucesso
      session_unset();
      $_SESSION['user_email'] = $email_login;
      $_SESSION['role'] = $user['role'];
      $_SESSION['logged_in'] = true;
      $_SESSION['message'] = 'Login realizado com sucesso';
      $_SESSION['message_type'] = 'success';
      header('Location: ../pages/dashboard.php');
      exit();
    } else {
      // Falha no login
      session_unset();
      $_SESSION['logged_in'] = false;
      $_SESSION['message'] = 'E-mail ou senha incorretos';
      $_SESSION['message_type'] = 'error';
      header('Location: ../pages/login.php');
      exit();
    }
  } else {
    // Falha no login
    session_unset();
    $_SESSION['logged_in'] = false;
    $_SESSION['message'] = 'E-mail ou senha incorretos';
    $_SESSION['message_type'] = 'error';

    // Fechar a conexão com o banco de dados
    close_connection($conn);
    header('Location: ../pages/login.php');
    exit();
  }
} else {
  session_unset();
  $_SESSION['message'] = 'Acesso não autorizado';
  $_SESSION['message_type'] = 'error';
  header('Location: ../index.php');
  exit();
}
