<!-- approve_registration.php-->
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

        require_once "../connection/connection.php";
        $conn = connection();

        // Update the status to 'Approved' for specific event registration
        $sql_update_status = "UPDATE event_registrations SET status = 'Approved' WHERE user_id = ? AND category = ? AND event = ?";
        $stmt_update_status = mysqli_prepare($conn, $sql_update_status);

        if ($stmt_update_status) {
            mysqli_stmt_bind_param($stmt_update_status, "iss", $user_id, $category, $event);

            if (mysqli_stmt_execute($stmt_update_status)) {
                mysqli_stmt_close($stmt_update_status);

                // Redirect back to the event registrations page
                header("Location: event_management.php");
                exit();
            } else {
                echo "Error executing statement: " . mysqli_stmt_error($stmt_update_status);
            }
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    } else {
        // Redirect if the user does not have permission
        header("Location: event_management.php");
        exit();
    }

?>