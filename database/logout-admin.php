<?php
// filepath: c:\xampp\htdocs\haggle\database\logout-admin.php

session_start();
header('Content-Type: application/json');

// Unset all admin session variables
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

// Optionally destroy the session if only admins use this session
// session_destroy();

echo json_encode(['success' => true]);
?>