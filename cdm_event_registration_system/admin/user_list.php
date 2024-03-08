<!-- user_list.php -->
<?php
// Start the session
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php"); // Redirect to your login page
    exit(); // Stop further execution
}
require_once "../connection/connection.php";

$conn = connection();

// $sql = "SELECT * FROM  user_login_list";
$sql = "SELECT 
            user_login_list.student_no, 
            user_login_list.first_name, 
            user_login_list.last_name, 
            user_login_list.gender, 
            user_login_list.age, 
            user_login_list.email, 
            user_login_list.contact_no, 
            CONCAT(
                DATE_FORMAT(user_login_list.added_at, '%Y-%m-%d'),
                ' on ',
                DATE_FORMAT(user_login_list.added_at, '%h:%i %p')
            ) as formatted_added_at,
            (
                SELECT COUNT(*)
                FROM event_registrations
                WHERE event_registrations.user_id = user_login_list.id
                    AND event_registrations.status = 'Approved'
            ) as registered_events
        FROM user_login_list";
$users = $conn->query($sql) or die ($conn->error);
$row = $users->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!---Boxicons CSS-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="../css/dash-board.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>User List</title>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .logout-form {
          margin-left: 32px; /* Adjust the margin as needed */
		  margin-bottom: 20px;

        }
    </style>
  
</head>

<body>
  
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <img src="../img/logo.jpg" alt="">
            </div>

            <span class="logo_name">Colegio De <br> Montalban</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <!---Dashboard-->
                <li><a href="admin_dashboard.php">
                        <i class="bx bxs-dashboard "></i>
                        <span class="link-name">Dashboard</span>
                    </a></li>
                <!--User List-->

                <li><a href="user_list.php">
                        <i class="bx bxs-user"></i>
                        <span class="link-name">User List</span>
                    </a></li>

                <!--Event Management-->
                <li><a href="event_management.php">
                        <i class="bx bx-bar-chart-square icon"></i>
                        <span class="link-name">Event<span class="management">Management</span></span>
                    </a></li>

                <!--Calendar Events-->

                <li><a href="event_calendar.php">
                        <i class="bx bxs-calendar-event"></i>
                        <span class="link-name">Calendar Events</span>
                    </a></li>
              
            </ul>
            <ul>
                <!--Log out-->
                <form id="logoutForm" action="admin_logout.php" method="post" class="nav-item logout-form" onsubmit="return confirmLogout();">
                    <button type="submit" name="admin_logout" class="btn btn-link" style="color: #9EB8D9; font-size: 20px; background-color: transparent;">Log out</button>
                </form>

                <!--Dark Mode-->
                <li class="mode">
                    <a href="#">
                        <i class="bx bxs-moon icon moon"></i>
                        <span class="link-name">Dark Mode</span>
                    </a>

                    <div class="mode-toggle">
                        <span class="switch"></span>
                    </div>
                </li>
            </ul>
        </div>

    </nav>
    <section class="dashboard">
                <div class="top">
                <i class="bx bx-menu sidebar-toggle" onclick="toggleLogoutForm()"></i>
            </div>

        <div class="dash-content">
           
            <div class="activity">
                <div class="title">
                    <i class="bx bx-time"></i>
                    <span class="text">CDM EVENT REGISTRATION SYSTEM</span>
                </div>
                <span class="list">List of all users</span>

            <table class="table table-borderless">
        <thead>
            <tr>
                <th class="text-center">Student no.</th>
                <th class="text-center">Name</th>
                <th class="text-center">Gender</th>
                <th class="text-center">Age</th>
                <th class="text-center">Registered Events</th>
                <th class="text-center">Added at</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $users->fetch_assoc()) : ?>
                <tr>
                    <td class="text-center border-0"><?php echo $row['student_no']; ?></td>
                    <td class="text-center border-0"><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                    <td class="text-center border-0"><?php echo $row['gender']; ?></td>
                    <td class="text-center border-0"><?php echo $row['age']; ?></td>
                    <td class="text-center border-0"><?php echo $row['registered_events']; ?></td>
                    <td class="text-center border-0"><?php echo $row['formatted_added_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table> 

<!-- Add this code where you want to place the "View All" link -->


   </div>
      </section>

    <script src="../js/script.js"></script>

    <script>
    // JavaScript function to confirm logout
    function confirmLogout() {
        var confirmationMessage = "Are you sure you want to logout?";
        return confirm(confirmationMessage);
    }
</script>

<script>
    function confirmLogout() {
        var confirmationMessage = "Are you sure you want to logout?";
        return confirm(confirmationMessage);
    }

    // Function to toggle the closed class on the logout form
    function toggleLogoutForm() {
        var logoutForm = document.getElementById('logoutForm');
        logoutForm.classList.toggle('closed');
    }
</script>


</body>

</html>