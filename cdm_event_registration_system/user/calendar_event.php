<?php
session_start(); // Start the session to access user data

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php"); // Redirect to the login page
    exit();
}
require_once "../connection/connection.php";

$conn = connection();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="../css/dash-board.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 0; /* Set border to 0 for both th and td */
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
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
                  <!-- <li><a href="calendar_event.php"> -->
                    <!-- <i class="bx bxs-dashboard "></i>
                    <span class="link-name">Dashboard</span>
                  </a></li> -->
                    <!--User List-->
                    
                  <li><a href="profile.php">
                    <i class="bx bxs-user"></i>
                    <span class="link-name">Profile</span>
                  </a></li>
                      <!--User List
                         <div class="notification">
                            <li><a href="#">
                             <i class="bx bxs-user"></i>
                             <div class="count">+99</div>
                             <span class="link-name">Notifications</span>
                            </a></li>
                           </div>
                            -->
               
                    <!--Event Management-->
                  <li><a href="select_category.php">
                <i class="bx bx-bar-chart-square icon"></i>
                    <span class="link-name">Register<span class="management"> Event</span></span>
                  </a></li>
               
                    <!--Calendar Events-->
                   
                  <li><a href="calendar_event.php">
                    <i class="bx bxs-calendar-event"></i>
                    <span class="link-name">Calendar Events</span>
                  </a></li>
                  
                  
                 
            </ul>
            <ul >
            <form id="logoutForm" action="user_logout.php" method="post" class="nav-item logout-form" onsubmit="return confirmLogout();">
                <button type="submit" name="user_logout" class="btn btn-link" style="color: #9EB8D9; font-size: 20px; background-color: transparent;">Log out</button>
            </form>



                <!--Dark Mode-->
                <li class="mode nav-item">
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
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary position-fixed top-0 end-0 mt-3 mr-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Notification
</button>


            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">My Notification</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                            <!-- Include the content of user_notification.php here -->
                            <?php
                            // Assuming you have a database connection function named `connection`
                            require_once "../connection/connection.php";
                            $conn = connection();

                            // Initialize $data array
                            $data = [];

                            // Fetch all event registrations (cancelled, approved, and pending)
                            $sql_registrations = "
                            SELECT category, event, timestamp, 'Cancelled' AS status
                            FROM canceled_registrations
                            WHERE user_id = ?
                            
                            UNION ALL
                            
                            SELECT category, event, registration_time, 'Approved' AS status
                            FROM event_registrations
                            WHERE user_id = ? AND status = 'APPROVED'
                            
                            UNION ALL
                            
                            SELECT category, event, timestamp, 'Removed' AS status
                            FROM notifications
                            WHERE user_id = ? AND status = 'Removed'
                            
                            UNION ALL
                            
                            SELECT category, event, registration_time, 'Pending' AS status
                            FROM event_registrations
                            WHERE user_id = ? AND status = 'PENDING'
                            
                            ORDER BY timestamp DESC
                        ";
                        

                            $stmt_registrations = mysqli_prepare($conn, $sql_registrations);

                            if ($stmt_registrations) {
                                mysqli_stmt_bind_param($stmt_registrations, "iiii", $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']);
                                mysqli_stmt_execute($stmt_registrations);
                                mysqli_stmt_bind_result($stmt_registrations, $category, $event, $registration_time, $status);

                                // Fetch the result
                                while (mysqli_stmt_fetch($stmt_registrations)) {
                                    $data[] = array('category' => $category, 'event' => $event, 'registration_time' => $registration_time, 'status' => $status);
                                }

                                mysqli_stmt_close($stmt_registrations);
                            } else {
                                echo "Error fetching event registration information: " . mysqli_error($conn);
                                exit();
                            }

                            // Close the database connection
                            mysqli_close($conn);

                            // Sort the data array by registration_time in descending order
                            usort($data, function ($a, $b) {
                                return strtotime($b['registration_time']) - strtotime($a['registration_time']);
                            });
                            ?>

                            <table>
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Event</th>
                                        <th>Registration Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $row): ?>
                                        <tr>
                                            <td style="color: black;"><?php echo $row['category']; ?></td>
                                            <td style="color: black;"><?php echo $row['event']; ?></td>
                                            <td style="color: black;"><?php echo date('Y-m-d \o\n h:i A', strtotime($row['registration_time'])); ?></td>
                                            <td style="color: black;"><?php echo $row['status']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

        </ul>
    </div>
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