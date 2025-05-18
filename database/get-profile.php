<?php
// filepath: c:\xampp\htdocs\haggle\database\get-profile.php

session_start();
header('Content-Type: application/json');
include 'connect_db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT ui.firstname, ui.lastname, ui.email, ui.contact_number, ui.address, ua.username
     FROM user_account ua
     JOIN user_information ui ON ua.user_info_id = ui.id
     WHERE ua.id = ?"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'firstname' => $row['firstname'],
        'lastname' => $row['lastname'],
        'email' => $row['email'],
        'contact_number' => $row['contact_number'],
        'address' => $row['address'],
        'username' => $row['username']
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Profile not found.']);
}
$stmt->close();
$conn->close();
?>