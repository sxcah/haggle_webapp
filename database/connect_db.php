<?php
include 'variables.php';
// Database connection

$conn = new mysqli($servername, $username, $password, $database, $second_port);

if ($conn->connect_error) {
    $conn = new mysqli($servername, $username, $password, $database, $second_port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}
?>