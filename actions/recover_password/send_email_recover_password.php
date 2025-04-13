<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_recover_password'])) {

    $email = isset($_POST['email_recover_password']) ? $_POST['email_recover_password'] : '';

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

        // Send the email (soon)

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
}
