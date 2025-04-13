<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_recover_password'])) {

  $email = isset($_SESSION['email_recover_password']) ? $_SESSION['email_recover_password'] : '';
  $verification_code = isset($_POST['verification_code']) ? $_POST['verification_code'] : '';
  $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
  $confirm_new_password = isset($_POST['confirm_new_password']) ? $_POST['confirm_new_password'] : '';
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // Verificar se o e-mail está disponível na sessão
  if (empty($email)) {
    $_SESSION['message'] = 'Problema ao recuperar a senha. Tente novamente.';
    $_SESSION['message_type'] = 'error';
    header('Location: ../../pages/recover/enter_email.php');
    exit();
  }

  // Validar se todos os campos foram preenchidos
  if (empty($verification_code) || empty($new_password) || empty($confirm_new_password)) {
    $_SESSION['message'] = 'Preencha todos os campos';
    $_SESSION['message_type'] = 'error';
    header('Location: ../../pages/recover/recover_password.php');
    exit();
  }

  // Verificar se as senhas coincidem
  if ($new_password !== $confirm_new_password) {
    $_SESSION['message'] = 'As senhas não coincidem';
    $_SESSION['message_type'] = 'error';
    header('Location: ../../pages/recover/recover_password.php');
    exit();
  }

  // Verificar se o código de verificação tem 8 caracteres
  if (strlen($verification_code) < 8) {
    $_SESSION['message'] = 'O código de verificação deve ter 8 caracteres';
    $_SESSION['message_type'] = 'error';
    header('Location: ../../pages/recover/recover_password.php');
    exit();
  }

  // Abrir a conexão com o banco de dados
  include  '../../config/db_connection.php';
  $conn = open_connection();

  // Verificar se o código de verificação existe no banco de dados
  $sql = "SELECT * FROM verification_code WHERE code = ? AND fk_user_email = ?";
  $result = mysqli_execute_query($conn, $sql, [$verification_code, $email]);
  if (mysqli_num_rows($result) > 0) {
    // Atualizar a senha do usuário
    $sql = "UPDATE user SET password = ? WHERE email = ?";
    mysqli_execute_query($conn, $sql, [$hashed_password, $email]);

    // Excluir o código de verificação do banco de dados
    $sql = "DELETE FROM verification_code WHERE code = ? AND fk_user_email = ?";
    mysqli_execute_query($conn, $sql, [$verification_code, $email]);

    close_connection($conn);

    $_SESSION['message'] = 'Senha alterada com sucesso';
    $_SESSION['message_type'] = 'success';
    header('Location: ../../pages/login.php');
    exit();
  } else {
    close_connection($conn);
    $_SESSION['message'] = 'Código de verificação inválido ou expirado';
    $_SESSION['message_type'] = 'error';
    header('Location: ../../pages/recover/recover_password.php');
    exit();
  }
} else {
  session_unset();
  $_SESSION['message'] = 'Acesso não autorizado';
  $_SESSION['message_type'] = 'error';
  header('Location: ../index.php');
  exit();
}
