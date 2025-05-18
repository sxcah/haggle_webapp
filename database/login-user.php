<?php
session_start();
include 'connect_db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT ua.id, ua.username, ua.password, ui.firstname, ui.lastname 
                            FROM user_account ua
                            JOIN user_information ui ON ua.user_info_id = ui.id
                            WHERE ua.username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['lastname'] = $row['lastname'];
            echo json_encode(['success' => true]);
            exit();
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid username or password.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid username or password.']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit();
}
?>