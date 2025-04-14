<?php
include '../../includes/session_start.php';

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

    date_default_timezone_set('America/Sao_Paulo'); // Ajustar para o fuso horário do Brasil/São Paulo
    $expiration_time = date("d/m/Y H:i:s", strtotime("+3 minutes")); // Código expira em 3 minutos
    $html_message = '
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Recuperação de Senha - NévoaCore</title>
        <style>
            body {
                font-family: "Times New Roman", Times, serif;
                font-size: 22px;
                color: #333;
                line-height: 1.8;
                background-color: #f2f2f2;
                margin: 0;
                padding: 0;
            }
            .container {
                width: 90%;
                max-width: 650px;
                margin: 40px auto;
                background: #fff;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
            h2 {
                text-align: center;
                color: #0056b3;
                font-size: 30px;
                margin-bottom: 20px;
            }
            p {
                margin: 20px 0;
            }
            .justified {
                text-align: justify;
            }
            .centered {
                text-align: center;
            }
            .code {
                font-family: "Times New Roman", Times, serif;
                font-size: 34px;
                font-weight: bold;
                background: #f8f8f8;
                padding: 20px 30px;
                display: block;
                letter-spacing: 3px;
                border: 3px dashed #bbb;
                border-radius: 6px;
                margin: 20px auto;
                text-align: center;
                max-width: 100%;
            }
            .instructions {
                font-size: 20px;
                background: #e9f5ff;
                padding: 15px;
                border: 1px solid #b3d7ff;
                border-radius: 6px;
                margin: 30px 0;
            }
            .footer {
                font-size: 18px;
                color: #777;
                margin-top: 40px;
                padding-top: 20px;
                border-top: 2px solid #ccc;
                text-align: center;
                background-color: #f9f9f9;
            }
            @media (max-width: 480px) {
                body {
                    font-size: 20px;
                }
                h2 {
                    font-size: 26px;
                }
                .code {
                    font-size: 30px;
                    padding: 15px 20px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Recuperação de Senha - NévoaCore</h2>
            <p>Hello World,</p>
            <p class="justified">Recebemos sua solicitação de recuperação de senha em <strong>' . date("d/m/Y H:i:s") . '</strong>. Para continuar, utilize o código abaixo:</p>
            <p class="code">' . $verification_code . '</p>
            <div class="instructions">
                <p class="justified">Se você não solicitou esta recuperação, por favor, desconsidere este e-mail.</p>
                <p class="justified">Esse código é válido por 3 minutos. Caso expire, solicite um novo código.</p>
                <p class="justified">Este código vai expirar em <strong>' . $expiration_time . '</strong>.</p>
            </div>
            <p class="centered">Para sua segurança, recomendamos que você não compartilhe esse código com ninguém.</p>
            <div class="footer">
                <p class="centered">Este é um e-mail automático, por favor não responda.</p>
                <p class="centered">&copy; ' . date("Y") . ' NévoaCore.</p>
            </div>
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
