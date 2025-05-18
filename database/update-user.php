<?php
// filepath: c:\xampp\htdocs\haggle\database\update-user.php

header('Content-Type: application/json');
include 'connect_db.php';

$user_id = intval($_POST['user_id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($user_id <= 0 || $username === '' || $email === '') {
    echo json_encode(['success' => false, 'error' => 'Invalid input.']);
    exit();
}

// Get user_info_id for this user
$stmt = $conn->prepare("SELECT user_info_id FROM user_account WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_info_id);
$stmt->fetch();
$stmt->close();

if (!$user_info_id) {
    echo json_encode(['success' => false, 'error' => 'User info not found.']);
    exit();
}

// Update username in user_account
$stmt = $conn->prepare("UPDATE user_account SET username = ? WHERE id = ?");
$stmt->bind_param("si", $username, $user_id);
$stmt->execute();
$stmt->close();

// Update email in user_information
$stmt = $conn->prepare("UPDATE user_information SET email = ? WHERE id = ?");
$stmt->bind_param("si", $email, $user_info_id);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => true]);
$conn->close();
?>