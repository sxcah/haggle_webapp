<?php
// filepath: c:\xampp\htdocs\haggle\database\get-single-post.php

session_start();
header('Content-Type: application/json');
include 'connect_db.php';

$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($post_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid post ID.']);
    exit();
}

$stmt = $conn->prepare(
    "SELECT p.id, p.content, p.created_at, ua.username, p.user_id
     FROM posts p
     JOIN user_account ua ON p.user_id = ua.id
     WHERE p.id = ?"
);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'post' => [
            'id' => $row['id'],
            'content' => htmlspecialchars($row['content']),
            'created_at' => $row['created_at'],
            'username' => htmlspecialchars($row['username']),
            'user_id' => $row['user_id']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Post not found.']);
}

$stmt->close();
$conn->close();
?>