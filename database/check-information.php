<?php

include 'register-information.php';

if (isset($_SESSION['register_info'])) {
    echo '<pre>';
    print_r($_SESSION['register_info']);
    echo '</pre>';
} else {
    echo "No register_info found in session.";
}
?>