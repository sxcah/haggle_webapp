<?php
// filepath: c:\xampp\htdocs\haggle\database\delete-post.php
session_start();
header('Content-Type: application/json');
include 'connect_db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in.']);
    exit();
}

$post_id = intval($_POST['post_id'] ?? 0);
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Delete failed or not authorized.']);
}
$stmt->close();
$conn->close();
?>