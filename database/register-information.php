<?php 

session_start();
include 'connect_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact-number'];
    $address = $_POST['address'];

    $_SESSION['register_info'] = [
        'firstname' => $first_name,
        'lastname' => $last_name,
        'email' => $email,
        'contact-number' => $contact_number,
        'address' => $address
    ];

    header("Location: ../forms/register-account.html");
    exit();
} else {
    echo "<script>window.alert('Invalid Request method')</script>";
}
?>