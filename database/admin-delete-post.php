<?php
// filepath: c:\xampp\htdocs\haggle\database\admin-delete-post.php

header('Content-Type: application/json');
include 'connect_db.php';

$post_id = intval($_POST['post_id'] ?? 0);

if ($post_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid post ID.']);
    exit();
}

// Delete post (comments will be deleted if you have ON DELETE CASCADE)
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Delete failed or post not found.']);
}

$stmt->close();
$conn->close();
?>