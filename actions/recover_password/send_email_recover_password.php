<?php
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

// Validate the email
if (empty($email)) {
    $_SESSION['message'] = 'O campo de E-mail deve ser preenchido';
    $_SESSION['message_type'] = 'error';
    header('Location: ../../pages/recover/enter_email.php');
    exit();
}

// Open the database connection
include '../../config/db_connection.php';
$conn = open_connection();

// Check if the email exists in the user table
$sql = "SELECT * FROM user WHERE email = ?";
$result = mysqli_execute_query($conn, $sql, [$email]);
if (mysqli_num_rows($result) > 0) {
    // Generate a unique 8-character token
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    do {
        $code = '';
        for ($i = 0; $i < 8; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        $sql = "SELECT code FROM verification_code WHERE code = ?";
        $result = mysqli_execute_query($conn, $sql, [$code]);
        $has_duplicate = mysqli_num_rows($result) > 0;
        mysqli_free_result($result);
    } while ($has_duplicate);

    // Delete the previous code, if it exists
    $sql = "DELETE FROM verification_code WHERE fk_user_email = ?";
    mysqli_execute_query($conn, $sql, [$email]);

    // Insert the new code into the verification_code table
    $sql = "INSERT INTO verification_code (code, fk_user_email) VALUES (?, ?)";
    mysqli_execute_query($conn, $sql, [$code, $email]);

    close_connection($conn);

    // Send the email

    include '../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../config'); 
    $dotenv->load();

    $SMTP_HOST = $_ENV['SMTP_HOST'];
    $SMTP_PORT = (int) $_ENV['SMTP_PORT'];
    $SMTP_USER = $_ENV['SMTP_USER'];
    $SMTP_PASS = $_ENV['SMTP_PASS'];

    $htmlMessage = '
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
            <p class="code">' . $code . '</p>
            <p>Atenciosamente,</p>
            <p>Equipe NevoaCore</p>
        </div>
    </body>
    </html>
    ';

    //Create a new PHPMailer instance
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
    $mail->msgHTML($htmlMessage);
    $mail->AltBody = 'Utilize o código a seguir para redefinir sua senha: ' . $code;

    function save_mail($mail) {
        $path = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail';
        $imapStream = imap_open($path, $mail->Username, $mail->Password);
        $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
        imap_close($imapStream);
        return $result;
    }

    if (!$mail->send()) {
        $_SESSION['message'] = 'Erro ao enviar e-mail: ' . $mail->ErrorInfo;
        $_SESSION['message_type'] = 'error';
        header('Location: ../../pages/recover/enter_email.php');
        exit();
    }
    
    save_mail($mail);
    $_SESSION['email_recover_password'] = $email;
    $_SESSION['message'] = 'Foi enviado no seu e-mail um código para redefinir sua senha';
    $_SESSION['message_type'] = 'success';
    header('Location: ../../pages/recover/reset_password.php');
    exit();
} else {
    close_connection($conn);
    session_unset();
    $_SESSION['message'] = 'E-mail não encontrado';
    $_SESSION['message_type'] = 'error';
    header('Location: ../../pages/recover/enter_email.php');
    exit();
}
