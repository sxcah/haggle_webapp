<?php

session_start();
include 'connect_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['register_info'])) {
    $userInfo = $_SESSION['register_info'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into user_information table
    $stmt = $conn->prepare("INSERT INTO user_information (firstname, lastname, email, contact_number, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssss",
        $userInfo['firstname'],
        $userInfo['lastname'],
        $userInfo['email'],
        $userInfo['contact-number'],
        $userInfo['address']
    );
    $stmt->execute();
    $userInfoId = $stmt->insert_id;
    $stmt->close();

    // Insert into user_account table
    $stmt = $conn->prepare("INSERT INTO user_account (user_info_id, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userInfoId, $username, $hashedPassword);
    $stmt->execute();
    $stmt->close();

    // Clear session data
    unset($_SESSION['register_info']);

    // Redirect to login page
    header("Location: ../forms/login.html");
    exit();
} else {
    echo "<script>window.alert('Invalid request or missing session data.')</script>";
}
?>