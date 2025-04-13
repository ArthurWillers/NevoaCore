<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_register'])) {

  $username = isset($_POST['username_register']) ? $_POST['username_register'] : '';
  $email = isset($_POST['email']) ? $_POST['email'] : '';
  $password = isset($_POST['password_register']) ? $_POST['password_register'] : '';
  $confirm_password = isset($_POST['confirm_password_register']) ? $_POST['confirm_password_register'] : '';


  // Validate input
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $_SESSION['message'] = 'Todos os campos devem ser preenchidos';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/register.php');
    exit();
  }

  // Check if passwords match
  if ($password !== $confirm_password) {
    $_SESSION['message'] = 'As senhas não coincidem';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/register.php');
    exit();
  }

  // opening the database connection
  include  '../config/db_connection.php';
  $conn = open_connection();

  // Check if email already exists
  $sql = "SELECT * FROM user WHERE email = ?";
  $result = mysqli_execute_query($conn, $sql, [$email]);
  if (mysqli_num_rows($result) > 0) {
    $_SESSION['message'] = 'Este e-mail já está cadastrado';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/register.php');
    exit();
  }

  // Check if username already exists
  $sql = "SELECT * FROM user WHERE username = ?";
  $result = mysqli_execute_query($conn, $sql, [$username]);
  if (mysqli_num_rows($result) > 0) {
    $_SESSION['message'] = 'Este nome de usuário já está cadastrado';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/register.php');
    exit();
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_BCRYPT);

  // Insert new user into the database
  $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
  if (mysqli_execute_query($conn, $sql, [$username, $email, $hashed_password])) {
    // Sucessfully registered
    $_SESSION['message'] = 'Cadastro realizado com sucesso';
    $_SESSION['message_type'] = 'success';
    header('Location: ../pages/login.php');
    exit();
  } else {
    // Error inserting user
    $_SESSION['message'] = 'Erro ao cadastrar usuário';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/register.php');
    exit();
  }
}
