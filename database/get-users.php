<?php
// filepath: c:\xampp\htdocs\haggle\database\get-users.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
include 'connect_db.php';

$users = [];
$query = "SELECT ua.id, ua.username, ui.email 
          FROM user_account ua
          LEFT JOIN user_information ui ON ua.user_info_id = ui.id
          ORDER BY ua.id ASC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            'id' => $row['id'],
            'username' => htmlspecialchars($row['username']),
            'email' => htmlspecialchars($row['email'] ?? '')
        ];
    }
    echo json_encode(['success' => true, 'users' => $users]);
} else {
    echo json_encode(['success' => false, 'users' => []]);
}

$conn->close();
?>