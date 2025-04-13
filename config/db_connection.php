<?php
function open_connection() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "NevoaCore";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    mysqli_set_charset($conn, "utf8");

    return $conn;
}

function close_connection($conn) {
    mysqli_close($conn);
}
?>