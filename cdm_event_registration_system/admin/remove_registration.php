<!-- remove_registration.php -->
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['category']) && isset($_POST['event'])) {
    $user_id = $_POST['user_id'];
    $category = $_POST['category'];
    $event = $_POST['event'];

    // Assuming you have a database connection function named `connection`
    require_once "../connection/connection.php";
    $conn = connection();

    // Insert the removed registration to notifications
    $sql_insert_notification = "INSERT INTO notifications (user_id, category, event, status) VALUES (?, ?, ?, 'Removed')";
    $stmt_insert_notification = mysqli_prepare($conn, $sql_insert_notification);

    if ($stmt_insert_notification) {
        mysqli_stmt_bind_param($stmt_insert_notification, "iss", $user_id, $category, $event);
        mysqli_stmt_execute($stmt_insert_notification);
        mysqli_stmt_close($stmt_insert_notification);
    } else {
        echo "Error inserting notification for removed registration.";
    }

    // Delete the registration
    $sql_delete_registration = "DELETE FROM event_registrations WHERE user_id = ? AND category = ? AND event = ?";
    $stmt_delete_registration = mysqli_prepare($conn, $sql_delete_registration);

    if ($stmt_delete_registration) {
        mysqli_stmt_bind_param($stmt_delete_registration, "iss", $user_id, $category, $event);
        mysqli_stmt_execute($stmt_delete_registration);
        mysqli_stmt_close($stmt_delete_registration);

        // Redirect back to the event registrations page
        header("Location: event_management.php");
        exit();
    } else {
        echo "Error deleting registration.";
    }

    mysqli_close($conn);
} else {
    // Redirect if the form is not submitted
    header("Location: event_management.php");
    exit();
}
?>