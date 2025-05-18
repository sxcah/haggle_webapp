<?php
// filepath: c:\xampp\htdocs\haggle\database\get-comments.php

header('Content-Type: application/json');
include 'connect_db.php';

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if ($post_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid post ID.']);
    exit();
}

$stmt = $conn->prepare(
    "SELECT c.id, c.content, c.created_at, c.user_id, ua.username
     FROM comments c
     JOIN user_account ua ON c.user_id = ua.id
     WHERE c.post_id = ?
     ORDER BY c.created_at ASC"
);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = [
        'id' => $row['id'],
        'content' => htmlspecialchars($row['content']),
        'created_at' => $row['created_at'],
        'username' => htmlspecialchars($row['username']),
        'user_id' => $row['user_id'] // Add this line (you'll need to select c.user_id in your SQL)
    ];
}

echo json_encode([
    'success' => true,
    'comments' => $comments
]);

$stmt->close();
$conn->close();
?>