<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_recover_password'])) {
    $email = isset($_POST['email_recover_password']) ? $_POST['email_recover_password'] : '';
} elseif (isset($_SESSION['email_recover_password'])) {
    $email = $_SESSION['email_recover_password'];
    unset($_SESSION['email_recover_password']);
} else {
    $_SESSION['message'] = 'Dados não fornecidos';
    $_SESSION['message_type'] = 'error';
    header('Location: ../../pages/recover/enter_email.php');
    exit();
}

// Validar o e-mail
if (empty($email)) {
    $_SESSION['message'] = 'O campo de E-mail deve ser preenchido';
    $_SESSION['message_type'] = 'error';
    header('Location: ../../pages/recover/enter_email.php');
    exit();
}

// Abrir a conexão com o banco de dados
include '../../config/db_connection.php';
$conn = open_connection();

// Verificar se o e-mail existe na tabela de usuários
$sql = "SELECT * FROM user WHERE email = ?";
$result = mysqli_execute_query($conn, $sql, [$email]);
if (mysqli_num_rows($result) > 0) {
    // Gerar um token único de 8 caracteres
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    do {
        $verification_code = '';
        for ($i = 0; $i < 8; $i++) {
            $verification_code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        $sql = "SELECT code FROM verification_code WHERE code = ?";
        $result = mysqli_execute_query($conn, $sql, [$verification_code]);
        $has_duplicate = mysqli_num_rows($result) > 0;
        mysqli_free_result($result);
    } while ($has_duplicate);

    // Excluir o código anterior, se existir
    $sql = "DELETE FROM verification_code WHERE fk_user_email = ?";
    mysqli_execute_query($conn, $sql, [$email]);

    // Inserir o novo código na tabela de códigos de verificação
    $sql = "INSERT INTO verification_code (code, fk_user_email) VALUES (?, ?)";
    mysqli_execute_query($conn, $sql, [$verification_code, $email]);

    close_connection($conn);

    // Enviar o e-mail
    include '../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../config'); 
    $dotenv->load();

    $SMTP_HOST = $_ENV['SMTP_HOST'];
    $SMTP_PORT = (int) $_ENV['SMTP_PORT'];
    $SMTP_USER = $_ENV['SMTP_USER'];
    $SMTP_PASS = $_ENV['SMTP_PASS'];

    $html_message = '
    <!DOCTYPE html>
    <html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Recuperação de Senha - NevoaCore</title>
        <style>
            body {
                font-family: "Times New Roman", Times, serif;
                font-size: 16px;
                color: #333;
                line-height: 1.5;
                background-color: #ffffff;
            }
            .container {
                margin: 20px;
                padding: 20px;
            }
            .code {
                font-size: 24px;
                font-weight: bold;
                background: #f5f5f5;
                padding: 10px 15px;
                display: inline-block;
                letter-spacing: 2px;
                border: 1px solid #ccc;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <p>Caro usuário,</p>
            <p>Utilize o código abaixo para redefinir sua senha:</p>
            <p class="code">' . $verification_code . '</p>
            <p>Atenciosamente,</p>
            <p>Equipe NevoaCore</p>
        </div>
    </body>
    </html>
    ';

    // Criar uma nova instância do PHPMailer
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = $SMTP_HOST;
    $mail->Port = $SMTP_PORT;
    $mail->SMTPSecure = $SMTP_PORT === 465 ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPAuth = true;
    $mail->Username = $SMTP_USER;
    $mail->Password = $SMTP_PASS;
    $mail->CharSet = 'UTF-8';
    $mail->setFrom($SMTP_USER, 'Suporte NevoaCore');
    $mail->addReplyTo($SMTP_USER, 'Suporte NevoaCore');
    $mail->addAddress($email);
    $mail->Subject = 'Recuperação de Senha - NevoaCore';
    $mail->msgHTML($html_message);
    $mail->AltBody = 'Utilize o código a seguir para redefinir sua senha: ' . $verification_code;

    // Removido o uso da função save_mail baseada em IMAP
    // function save_mail($mail) {
    //     $path = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail';
    //     $imapStream = imap_open($path, $mail->Username, $mail->Password);
    //     $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
    //     imap_close($imapStream);
    //     return $result;
    // }

    if (!$mail->send()) {
        $_SESSION['message'] = 'Erro ao enviar e-mail: ' . $mail->ErrorInfo;
        $_SESSION['message_type'] = 'error';
        header('Location: ../../pages/recover/enter_email.php');
        exit();
    }

    // Chamadas ao save_mail() foram removidas

    $_SESSION['email_recover_password'] = $email;
    $_SESSION['message'] = 'Foi enviado no seu e-mail um código para redefinir sua senha';
    $_SESSION['message_type'] = 'success';
    header('Location: ../../pages/recover/recover_password.php');
    exit();
} else {
    close_connection($conn);
    session_unset();
    $_SESSION['message'] = 'E-mail não encontrado';
    $_SESSION['message_type'] = 'error';
    header('Location: ../../pages/recover/enter_email.php');
    exit();
}
