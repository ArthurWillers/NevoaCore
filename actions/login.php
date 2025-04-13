<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_login'])) {

  $email = isset($_POST['email_login']) ? $_POST['email_login'] : '';
  $password = isset($_POST['password_login']) ? $_POST['password_login'] : '';

  // Validate input
  if (empty($email) || empty($password)) {
    $_SESSION['message'] = 'Todos os campos devem ser preenchidos';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/login.php');
    exit();
  }

  // opening the database connection
  include  '../config/db_connection.php';
  $conn = open_connection();

  // Prepare the SQL statement
  $sql = "SELECT * FROM user WHERE email = ?";

  // Execute the SQL statement
  $result = mysqli_execute_query($conn, $sql, [$email]);

  // Check if the user exists and process the result
  if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Close the database connection
    close_connection($conn);

    // Verify the password
    if (password_verify($password, $user['password'])) {
      // Sucefully Login
      session_unset();
      $_SESSION['user_email'] = $email;
      $_SESSION['logged_in'] = true;
      $_SESSION['message'] = 'Login realizado com sucesso';
      $_SESSION['message_type'] = 'success';
      header('Location: ../pages/dashboard.php');
      exit();
    } else {
      // Failed Login
      session_unset();
      $_SESSION['logged_in'] = false;
      $_SESSION['message'] = 'E-mail ou senha incorretos';
      $_SESSION['message_type'] = 'error';
      header('Location: ../pages/login.php');
      exit();
    }
  } else {
    // Failed Login
    session_unset();
    $_SESSION['logged_in'] = false;
    $_SESSION['message'] = 'E-mail ou senha incorretos';
    $_SESSION['message_type'] = 'error';

    // Close the database connection
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
