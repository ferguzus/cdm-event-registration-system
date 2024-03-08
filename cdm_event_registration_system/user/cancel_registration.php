<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel'])) {
    // Assuming you have a database connection function named `connection`
    require_once "../connection/connection.php";
    $conn = connection();

    // Assuming you have a user_id stored in the session
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $event = $_POST['event'];

    // Retrieve registration details before deletion
    $sql_select_registration = "SELECT * FROM event_registrations WHERE user_id = ? AND category = ? AND event = ?";
    $stmt_select_registration = mysqli_prepare($conn, $sql_select_registration);

    if ($stmt_select_registration) {
        mysqli_stmt_bind_param($stmt_select_registration, "iss", $user_id, $category, $event);
        mysqli_stmt_execute($stmt_select_registration);
        $result_select_registration = mysqli_stmt_get_result($stmt_select_registration);
        $registration_details = mysqli_fetch_assoc($result_select_registration);

        // Insert the canceled registration details into the canceled_registrations table
        $sql_insert_canceled_registration = "INSERT INTO canceled_registrations (user_id, category, event, timestamp, status) VALUES (?, ?, ?, ?, 'Cancelled')";
        $stmt_insert_canceled_registration = mysqli_prepare($conn, $sql_insert_canceled_registration);

        if ($stmt_insert_canceled_registration) {
            mysqli_stmt_bind_param($stmt_insert_canceled_registration, "isss", $user_id, $category, $event, $registration_details['registration_time']);
            mysqli_stmt_execute($stmt_insert_canceled_registration);
            mysqli_stmt_close($stmt_insert_canceled_registration);
        }

        // Update the status to "Cancelled" in the event_registrations table
        $sql_update_status = "UPDATE event_registrations SET status = 'Cancelled' WHERE user_id = ? AND category = ? AND event = ?";
        $stmt_update_status = mysqli_prepare($conn, $sql_update_status);

        if ($stmt_update_status) {
            mysqli_stmt_bind_param($stmt_update_status, "iss", $user_id, $category, $event);
            mysqli_stmt_execute($stmt_update_status);
            mysqli_stmt_close($stmt_update_status);
        }

        // Remove the user's registration for the specified event
        $sql_cancel_registration = "DELETE FROM event_registrations WHERE user_id = ? AND category = ? AND event = ?";
        $stmt_cancel_registration = mysqli_prepare($conn, $sql_cancel_registration);

        if ($stmt_cancel_registration) {
            mysqli_stmt_bind_param($stmt_cancel_registration, "iss", $user_id, $category, $event);
            mysqli_stmt_execute($stmt_cancel_registration);
            mysqli_stmt_close($stmt_cancel_registration);

            $cancellation_message = "Event registration canceled successfully!";
        } else {
            $cancellation_message = "Error canceling event registration.";
        }

        mysqli_stmt_close($stmt_select_registration);
    } else {
        $cancellation_message = "Error selecting existing registration.";
    }

    mysqli_close($conn);
} else {
    header("Location: user_sidebar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">
    <style>
        body {
            display: flex;
            background: #4070f4;
            height: 100vh;
            padding: 10px;
            overflow: hidden;
            margin: 0;
        }

        .center {
            max-width: 600px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 55%;
            background: #FFF;
            border-radius: 10px;
            padding: 20px 30px 3px;
            opacity: 0.9;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 50vh;
        }

        .center h1 {
            position: relative;
            color: #333;
            text-align: center;
            padding: 0 0 2px 0;
            margin: 5px;
            font-size: 40px;
            font-family: 'Poppins', sans-serif; /* Apply Poppins font */
        }

        .center h1::before {
            content: '';
            position: absolute;
            bottom: 0;
            height: 3px;
            width: 30px;
            background: #4070f4;
            align-items: center;
        }

        .registration-message {
            margin-top: 20px;
            color: #333;
            font-size: 18px;
            font-family: 'Poppins', sans-serif; /* Apply Poppins font */
        }

        /* Add this style section for button styling */

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #4070f4;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 10px; /* Add margin to separate the buttons */
            font-family: 'Poppins', sans-serif; /* Apply Poppins font */
            text-decoration: none; /* Remove default underline style */
            display: inline-block;
        }

        .btn-primary:hover {
            background-color: #305099;
        }
    </style>
</head>

<body>
    <!-- Display cancellation message -->
    <div class="center">
        <h1><?php echo $cancellation_message; ?></h1>
        <div class="registration-message">
            <!-- Additional content or messages can be added here if needed -->
        </div>

        <!-- Add a button to return to the home page -->
        <a href="user_sidebar.php" class="btn btn-primary">Return to Home</a>
    </div>
</body>

</html>