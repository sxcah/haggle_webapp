<?php
// filepath: c:\xampp\htdocs\haggle\database\check-users.php

include 'connect_db.php';

// Show all users in the database
echo "<h2>All Registered Users</h2>";

$result = $conn->query("SELECT ua.id, ua.username, ui.firstname, ui.lastname, ui.email FROM user_account ua JOIN user_information ui ON ua.user_info_id = ui.id");

if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Username</th><th>First Name</th><th>Last Name</th><th>Email</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['firstname']}</td>
                <td>{$row['lastname']}</td>
                <td>{$row['email']}</td>
              </tr>";
    }
    echo "</table>";
    echo "<p>Total users: " . $result->num_rows . "</p>";
} else {
    echo "No users found.";
}

// Show current session user (if any)
session_start();
if (isset($_SESSION['user_id'])) {
    echo "<h2>Current Logged In User (This Session)</h2>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
} else {
    echo "<h2>No user is logged in for this session.</h2>";
}
?>