<?php
    DEFINE('DB_USERNAME', 'root');
    DEFINE('DB_PASSWORD', '');
    DEFINE('DB_HOST', 'localhost');
    DEFINE('DB_DATABASE', 'factory');


    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($conn->connect_error) {
        die("Connection failed<br>: " . $conn->connect_error);
    }
    mysqli_set_charset($conn, "utf8");

?>