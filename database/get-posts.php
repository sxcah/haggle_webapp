<?php
// filepath: c:\xampp\htdocs\haggle\database\get-posts.php

session_start();
header('Content-Type: application/json');
include 'connect_db.php';

// Fetch recent posts with username
$query = "SELECT p.id, p.title, p.content, p.created_at, p.user_id, ua.username 
          FROM posts p 
          JOIN user_account ua ON p.user_id = ua.id 
          ORDER BY p.created_at DESC";

$result = $conn->query($query);

$posts = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = [
            'id' => $row['id'],
            'title' => htmlspecialchars($row['title']),
            'content' => htmlspecialchars($row['content']),
            'created_at' => $row['created_at'],
            'username' => htmlspecialchars($row['username']),
            'user_id' => $row['user_id'] // Add this line (you'll need to select p.user_id in your SQL)
        ];
    }
}

echo json_encode([
    'success' => true,
    'posts' => $posts
]);
?>