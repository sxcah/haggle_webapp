<?php

session_start();
include 'connect_db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['register_info'])) {
    $userInfo = $_SESSION['register_info'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM user_account WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'Username already exists. Please choose a different username.']);
        $stmt->close();
        exit();
    }
    $stmt->close();

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
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'error' => 'Failed to save user information.']);
        $stmt->close();
        exit();
    }
    $userInfoId = $stmt->insert_id;
    $stmt->close();

    // Insert into user_account table
    $stmt = $conn->prepare("INSERT INTO user_account (user_info_id, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userInfoId, $username, $hashedPassword);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'error' => 'Failed to create user account.']);
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Clear session data
    unset($_SESSION['register_info']);

    echo json_encode(['success' => true, 'redirect' => '../forms/login.html']);
    exit();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request or missing session data.']);
    exit();
}
?>