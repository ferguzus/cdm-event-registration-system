<!-- admin_side_bar.php -->
<?php
session_start(); // Start the session to access user data

// Check if the user is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to the login page
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Your Dashboard, <?php echo $_SESSION['admin_username']; ?>!</h1>
        
        <ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="user_list.php">User List</a>
    </li>
    
    
    <li class="nav-item">
        <a class="nav-link" href="event_management.php">Event Management</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="event_calendar.php">Event Calendar</a>
    </li>
    <form action="admin_logout.php" method="post">
            <button type="submit" name="admin_logout" class="btn btn-link">Logout</button>
        </form>
</ul>

    </div>
</body>
</html>


