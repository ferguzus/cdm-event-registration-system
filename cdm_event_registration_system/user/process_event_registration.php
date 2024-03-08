<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Initialize the registration message and status
$registration_message = "";
$status = '';

// Check if the necessary parameters are provided in the POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category']) && isset($_POST['event'])) {
    $category = $_POST['category'];
    $event = $_POST['event'];

    // Assuming you have a database connection function named `connection`
    require_once "../connection/connection.php";
    $conn = connection();

    // Assuming you have a user_id stored in the session
    $user_id = $_SESSION['user_id'];

    // Check if the user has already registered for the specified event
    $sql_check_registration = "SELECT * FROM event_registrations WHERE user_id = ? AND category = ? AND event = ?";
    $stmt_check_registration = mysqli_prepare($conn, $sql_check_registration);

    if ($stmt_check_registration) {
        mysqli_stmt_bind_param($stmt_check_registration, "iss", $user_id, $category, $event);
        mysqli_stmt_execute($stmt_check_registration);
        mysqli_stmt_store_result($stmt_check_registration);

// If a registration already exists, prevent a new registration
if (mysqli_stmt_num_rows($stmt_check_registration) > 0) {
    // Fetch the registration status
    $sql_fetch_status = "SELECT status FROM event_registrations WHERE user_id = ? AND category = ? AND event = ?";
    $stmt_fetch_status = mysqli_prepare($conn, $sql_fetch_status);

    if ($stmt_fetch_status) {
        mysqli_stmt_bind_param($stmt_fetch_status, "iss", $user_id, $category, $event);
        mysqli_stmt_execute($stmt_fetch_status);
        mysqli_stmt_bind_result($stmt_fetch_status, $status);

// Fetch the result
mysqli_stmt_fetch($stmt_fetch_status);

// Set the registration status message
$registration_message = "You have already registered for this event.";
$status_message = "Your registration status: $status";

mysqli_stmt_close($stmt_fetch_status);
} else {
    $registration_message = "Error fetching registration status.";
}
} else {
    // Set the default status to 'PENDING'
    $status = 'PENDING';

    // Insert the registration into the database with the status
    $sql_insert_registration = "INSERT INTO event_registrations (user_id, category, event, registration_time, status) VALUES (?, ?, ?, NOW(), ?)";
    $stmt_insert_registration = mysqli_prepare($conn, $sql_insert_registration);

    if ($stmt_insert_registration) {
        mysqli_stmt_bind_param($stmt_insert_registration, "isss", $user_id, $category, $event, $status);
        mysqli_stmt_execute($stmt_insert_registration);
        mysqli_stmt_close($stmt_insert_registration);

        // Set both messages
        $registration_message = "Registration successful!";
        $status_message = "Your registration status: $status";
    } else {
        $registration_message = "Error processing registration.";
    }
}

        mysqli_stmt_close($stmt_check_registration);
    } else {
        $registration_message = "Error checking existing registration.";
    }

    mysqli_close($conn);
} else {
    // Redirect to the event categories page if necessary parameters are not provided
    header("Location: select_category.php");
    exit();
}
?> 

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/process.css"> <!-- Add this line -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Event Registration</title>
</head>

<body>
<div class="center">
        <h1><?php echo $registration_message; ?></h1>
        <?php if ($status_message): ?>
            <h2><?php echo $status_message; ?></h2>
        <?php endif; ?>
        <div class="registration-message">
            <div class="bottom-text">Check your notification.</div>
        </div>

        <!-- Add this button section after the registration message -->
        <div class="button-container">
            <?php if ($registration_message && $status !== 'Approved'): ?>
                <!-- Cancel Button with Confirmation -->
                <form id="cancelForm" action="cancel_registration.php" method="post">
                    <input type="hidden" name="category" value="<?php echo $category; ?>">
                    <input type="hidden" name="event" value="<?php echo $event; ?>">
                    <button type="submit" name="cancel" onclick="confirmCancellation()" class="btn btn-danger" style="margin-top:3px; height: 40px;">Cancel</button>
                </form>
            <?php else: ?>
                <!-- Display a message or disable the button when the status is Approved -->
                <span class="btn btn-disabled">Cancel Disabled</span>
            <?php endif; ?>

            <!-- Button 1: Return to Home -->
            <form action="user_sidebar.php" method="post">
                <button type="submit" class="return-to-home-button" name="returnhome">Return to Home</button>
            </form>

            <!-- Button 2: Register Another -->
            <form action="select_category.php" method="post">
                <button type="submit" class="register-another-button" name="registeranother">Register Another</button>
            </form>
        </div>
    </div>

    <script>
        // JavaScript function to confirm cancellation
        function confirmCancellation() {
            var confirmCancel = confirm("Are you sure you want to cancel your registration?");
            if (!confirmCancel) {
                event.preventDefault(); // Prevent form submission if the user clicks "Cancel"
            }
        }
    </script>
</body>

</html>