<?php
// filepath: c:\xampp\htdocs\haggle\database\create-post.php

session_start();
header('Content-Type: application/json');
include 'connect_db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'You must be logged in to create a post.']);
    exit();
}

// Get and validate input
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');

if ($title === '' || $content === '') {
    echo json_encode(['success' => false, 'error' => 'Title and content are required.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Insert post into database
$stmt = $conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $title, $content);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to create post.']);
}

$stmt->close();
$conn->close();
?>