<?php 

session_start();
include 'connect_db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact-number'];
    $address = $_POST['address'];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM user_information WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'Email already exists. Please use a different email.']);
        exit();
    }
    $stmt->close();

    $_SESSION['register_info'] = [
        'firstname' => $first_name,
        'lastname' => $last_name,
        'email' => $email,
        'contact-number' => $contact_number,
        'address' => $address
    ];

    echo json_encode(['success' => true, 'redirect' => '../forms/register-account.html']);
    exit();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid Request method']);
    exit();
}
?>