<!-- event_calendar.php -->
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!---Boxicons CSS-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="../css/dash-board.css">
    <link rel="stylesheet" href="../css/calendar.css">
    <title>Calendar Events</title>

	<style>
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
            <span class="logo_name">Colegio De Montalban</span>
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
            <!--Log out-->
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

        <!--Calendar Events -->
        <div class="container">
            <div class="left">
                <div class="calendar">
                    <div class="month">
                        <i class="bx bx-chevron-left prev"></i>
                        <div class="date">december 2023</div>
                        <i class="bx bx-chevron-right next"></i>
                    </div>
                    <div class="weekdays">
                        <div>Sun</div>
                        <div>Mon</div>
                        <div>Tue</div>
                        <div>Wed</div>
                        <div>Thu</div>
                        <div>Fri</div>
                        <div>Sat</div>
                    </div>
                    <div class="days"></div>
                    <div class="goto-today">
                        <div class="goto">
                            <input type="text" placeholder="mm/yyyy" class="date-input" />
                            <button class="goto-btn">Go</button>
                        </div>
                        <button class="today-btn">Today</button>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="today-date">
                    <div class="event-day">sun</div>
                    <div class="event-date">3th december 2023</div>
                </div>
                <div class="events"></div>
                <!-- Removed modal related HTML and JavaScript -->
            </div>
            <!-- <button class="add-event">
                <i class="bx bx-plus-medical"></i>
            </button> -->
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