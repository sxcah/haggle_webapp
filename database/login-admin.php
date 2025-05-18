<?php
// filepath: c:\xampp\htdocs\haggle\database\login-admin.php

session_start();
header('Content-Type: application/json');
include 'connect_db.php';

// Get and sanitize input
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'error' => 'Username and password are required.']);
    exit();
}

// Query for admin user (plain text password as requested)
$stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($password === $row['password']) {
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_username'] = $row['username'];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid username or password.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid username or password.']);
}

$stmt->close();
$conn->close();
?>