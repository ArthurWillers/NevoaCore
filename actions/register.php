<?php
include '../includes/session_start.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_register'])) {

  $username_register = isset($_POST['username_register']) ? $_POST['username_register'] : '';
  $email = isset($_POST['email']) ? $_POST['email'] : '';
  $password_register = isset($_POST['password_register']) ? $_POST['password_register'] : '';
  $confirm_password_register = isset($_POST['confirm_password_register']) ? $_POST['confirm_password_register'] : '';

  // Validar dados de entrada
  if (empty($username_register) || empty($email) || empty($password_register) || empty($confirm_password_register)) {
    $_SESSION['message'] = 'Todos os campos devem ser preenchidos';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/register.php');
    exit();
  }

  // Verificar se o email é válido
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = 'E-mail inválido';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/register.php');
    exit();
  }

  // Verificar se as senhas coincidem
  if ($password_register !== $confirm_password_register) {
    $_SESSION['message'] = 'As senhas não coincidem';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/register.php');
    exit();
  }

  // Abre a conexão com o banco de dados
  include  '../config/db_connection.php';
  $conn = open_connection();

  // Verificar se o e-mail já está cadastrado
  $sql = "SELECT * FROM user WHERE email = ?";
  $result = mysqli_execute_query($conn, $sql, [$email]);
  if (mysqli_num_rows($result) > 0) {
    $_SESSION['message'] = 'Este e-mail já está cadastrado';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/register.php');
    exit();
  }

  // Verificar se o nome de usuário já está cadastrado
  $sql = "SELECT * FROM user WHERE username = ?";
  $result = mysqli_execute_query($conn, $sql, [$username_register]);
  if (mysqli_num_rows($result) > 0) {
    $_SESSION['message'] = 'Este nome de usuário já está cadastrado';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/register.php');
    exit();
  }

  // Gerar hash da senha
  $hashed_password = password_hash($password_register, PASSWORD_BCRYPT);

  // Inserir novo usuário no banco de dados
  $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
  if (mysqli_execute_query($conn, $sql, [$username_register, $email, $hashed_password])) {
    // Cadastro realizado com sucesso
    $_SESSION['message'] = 'Cadastro realizado com sucesso';
    $_SESSION['message_type'] = 'success';
    header('Location: ../pages/login.php');
    exit();
  } else {
    // Erro ao cadastrar o usuário
    $_SESSION['message'] = 'Erro ao cadastrar usuário';
    $_SESSION['message_type'] = 'error';
    header('Location: ../pages/register.php');
    exit();
  }
} else {
  session_unset();
  $_SESSION['message'] = 'Acesso não autorizado';
  $_SESSION['message_type'] = 'error';
  header('Location: ../index.php');
  exit();
}
