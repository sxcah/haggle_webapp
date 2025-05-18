<?php
// filepath: c:\xampp\htdocs\haggle\database\create-comment.php

session_start();
header('Content-Type: application/json');
include 'connect_db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'You must be logged in to comment.']);
    exit();
}

// Validate input
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
$content = trim($_POST['content'] ?? '');

if ($post_id <= 0 || $content === '') {
    echo json_encode(['success' => false, 'error' => 'Invalid post or empty comment.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Insert comment into database
$stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $post_id, $user_id, $content);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to add comment.']);
}

$stmt->close();
$conn->close();
?>