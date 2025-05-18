<?php
// filepath: c:\xampp\htdocs\haggle\database\logout.php

session_start();
session_unset();
session_destroy();

// For AJAX logout, just return a success message
echo json_encode(['success' => true]);
exit();
?>