<?php
session_start(); // Start the session to access user or admin data

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Determine the redirect based on the user type
if (isset($_POST["admin_logout"])) {
    // Redirect to the admin login page for admin logout
    header("Location: admin/admin_login.php");
} else {
    // Redirect to the user login page for user logout
    header("Location: user_login.php");
}

exit();
?>