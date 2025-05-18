<?php
// filepath: c:\xampp\htdocs\haggle\database\delete-user.php

header('Content-Type: application/json');
include 'connect_db.php';

$user_id = intval($_POST['user_id'] ?? 0);

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid user ID.']);
    exit();
}

// First, get the user_info_id to delete from user_information
$stmt = $conn->prepare("SELECT user_info_id FROM user_account WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_info_id);
$stmt->fetch();
$stmt->close();

// Delete from user_account
$stmt = $conn->prepare("DELETE FROM user_account WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Delete from user_information if user_info_id exists
if ($user_info_id) {
    $stmt = $conn->prepare("DELETE FROM user_information WHERE id = ?");
    $stmt->bind_param("i", $user_info_id);
    $stmt->execute();
    $stmt->close();
}

echo json_encode(['success' => true]);
$conn->close();
?>