<!-- admin_logout.php -->

<?php
// Check if a session is already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Logout logic
if (isset($_POST["admin_logout"])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the admin login page
    header("Location: admin_login.php");
    exit();
}
?>