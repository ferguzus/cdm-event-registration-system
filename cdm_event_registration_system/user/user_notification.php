<?php
session_start();

// Assuming you have a database connection function named `connection`
require_once "../connection/connection.php";
$conn = connection();

// Initialize $data array
$data = [];

// Fetch cancelled event registrations
$sql_cancelled_registrations = "
SELECT category, event, registration_time, 'Cancelled' AS status
FROM canceled_registrations
WHERE user_id = ?
";
$stmt_cancelled_registrations = mysqli_prepare($conn, $sql_cancelled_registrations);

if ($stmt_cancelled_registrations) {
    mysqli_stmt_bind_param($stmt_cancelled_registrations, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt_cancelled_registrations);
    mysqli_stmt_bind_result($stmt_cancelled_registrations, $category, $event, $registration_time, $status);

    // Fetch the result
    while (mysqli_stmt_fetch($stmt_cancelled_registrations)) {
        $data[] = array('category' => $category, 'event' => $event, 'registration_time' => $registration_time, 'status' => $status);
    }

    mysqli_stmt_close($stmt_cancelled_registrations);
} else {
    echo "Error fetching cancelled event registration information.";
    exit();
}

// Fetch approved (PENDING) event registrations
$sql_approved_registrations = "
SELECT category, event, registration_time, status
FROM event_registrations
WHERE user_id = ? AND status = 'PENDING'
";
$stmt_approved_registrations = mysqli_prepare($conn, $sql_approved_registrations);

if ($stmt_approved_registrations) {
    mysqli_stmt_bind_param($stmt_approved_registrations, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt_approved_registrations);
    mysqli_stmt_bind_result($stmt_approved_registrations, $category, $event, $registration_time, $status);

    // Fetch the result
    while (mysqli_stmt_fetch($stmt_approved_registrations)) {
        $data[] = array('category' => $category, 'event' => $event, 'registration_time' => $registration_time, 'status' => $status);
    }

    mysqli_stmt_close($stmt_approved_registrations);
} else {
    echo "Error fetching approved (PENDING) event registration information.";
    exit();
}

// Fetch removed event registrations from notifications
$sql_removed_registrations = "
SELECT category, event, timestamp, 'Removed' AS status
FROM notifications
WHERE user_id = ? AND status = 'Removed'
";
$stmt_removed_registrations = mysqli_prepare($conn, $sql_removed_registrations);

if ($stmt_removed_registrations) {
    mysqli_stmt_bind_param($stmt_removed_registrations, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt_removed_registrations);
    mysqli_stmt_bind_result($stmt_removed_registrations, $category, $event, $timestamp, $status);

    // Fetch the result
    while (mysqli_stmt_fetch($stmt_removed_registrations)) {
        $data[] = array('category' => $category, 'event' => $event, 'registration_time' => $timestamp, 'status' => $status);
    }

    mysqli_stmt_close($stmt_removed_registrations);
} else {
    echo "Error fetching removed event registration information.";
    exit();
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css"> <!-- Include your profile styles -->
    <title>Event Registrations</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <table>
        <thead>
            <tr>
                <th colspan="5">Notification</th>
            </tr>
            <tr>
                <th>Category</th>
                <th>Event</th>
                <th>Registration Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row) : ?>
                <tr>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['event']; ?></td>
                    <td><?php echo $row['registration_time']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="user_sidebar.php">Back</a>
</body>

</html>