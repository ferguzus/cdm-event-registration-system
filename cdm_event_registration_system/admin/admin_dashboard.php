<!-- admin_dashboard.php -->
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



// Count the total number of users
$sql_total_users = "SELECT COUNT(*) as total_users FROM user_login_list";
$result_total_users = $conn->query($sql_total_users);
$row_total_users = $result_total_users->fetch_assoc();
$total_users = $row_total_users['total_users'];

// Count the total number of categories
$sql_total_categories = "SELECT COUNT(DISTINCT category) as total_categories FROM event_registrations";
$result_total_categories = $conn->query($sql_total_categories);
$row_total_categories = $result_total_categories->fetch_assoc();
$total_categories = $row_total_categories['total_categories'];

// Count the total number of events
$sql_total_events = "SELECT COUNT(DISTINCT event) as total_events FROM event_registrations";
$result_total_events = $conn->query($sql_total_events);
$row_total_events = $result_total_events->fetch_assoc();
$total_events = $row_total_events['total_events'];

// Count the number of users registered for each event
$sql_basketball_registrations = "SELECT COUNT(DISTINCT user_id) as basketball_registrations FROM event_registrations WHERE event = 'Basketball' AND status = 'Approved'";
$result_basketball_registrations = $conn->query($sql_basketball_registrations);
$row_basketball_registrations = $result_basketball_registrations->fetch_assoc();
$basketball_registrations = $row_basketball_registrations['basketball_registrations'];

$sql_volleyball_registrations = "SELECT COUNT(DISTINCT user_id) as volleyball_registrations FROM event_registrations WHERE event = 'Volleyball' AND status = 'Approved'";
$result_volleyball_registrations = $conn->query($sql_volleyball_registrations);
$row_volleyball_registrations = $result_volleyball_registrations->fetch_assoc();
$volleyball_registrations = $row_volleyball_registrations['volleyball_registrations'];

$sql_christmas_choir_registrations = "SELECT COUNT(DISTINCT user_id) as christmas_choir_registrations FROM event_registrations WHERE event = 'Christmas Choir' AND status = 'Approved'";
$result_christmas_choir_registrations = $conn->query($sql_christmas_choir_registrations);
$row_christmas_choir_registrations = $result_christmas_choir_registrations->fetch_assoc();
$christmas_choir_registrations = $row_christmas_choir_registrations['christmas_choir_registrations'];

$sql_quiz_bee_registrations = "SELECT COUNT(DISTINCT user_id) as quiz_bee_registrations FROM event_registrations WHERE event = 'Quiz Bee' AND status = 'Approved'";
$result_quiz_bee_registrations = $conn->query($sql_quiz_bee_registrations);
$row_quiz_bee_registrations = $result_quiz_bee_registrations->fetch_assoc();
$quiz_bee_registrations = $row_quiz_bee_registrations['quiz_bee_registrations'];

// Count the total number of users registered for all events
$sql_total_registered_events = "SELECT COUNT(DISTINCT user_id) as total_registered_events FROM event_registrations WHERE status = 'Approved'";
$result_total_registered_events = $conn->query($sql_total_registered_events);
$row_total_registered_events = $result_total_registered_events->fetch_assoc();
$total_registered_events = $row_total_registered_events['total_registered_events'];

// Fetch user details with registered events
$sql = "SELECT 
            user_login_list.student_no, 
            user_login_list.first_name, 
            user_login_list.last_name, 
            user_login_list.gender, 
            user_login_list.age, 
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
        FROM user_login_list
        ORDER BY added_at DESC
        LIMIT 5";

$users = $conn->query($sql) or die($conn->error);
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
    <title>Dashboard</title>

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
            <ul >

            <!-- kuhain ko mmaya -->
            <form id="logoutForm" action="admin_logout.php" method="post" class="nav-item logout-form" onsubmit="return confirmLogout();">
                <button type="submit" name="admin_logout" class="btn btn-link" style="color: #9EB8D9; font-size: 20px; background-color: transparent;">Log out</button>
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
      <section class="dashboard">
      <div class="top">
          <i class="bx bx-menu sidebar-toggle" onclick="toggleLogoutForm()"></i>
      </div>

          
        <div class="dash-content">
          <div class="overview">
            <div class="title">
            <i class="bx bx-tachometer"></i>
            <span class="text">Dashboard</span>
          </div>
          
          <!--3-Boxes-->
          <div class="boxes">
             <div class="box box1">
              <span class="text"><?php echo $total_users; ?></span> <!-- no. of users -->
              <i class="bx bx-user"></i>
                <span class="text">Users</span>
             </div>
             <div class="box box2">
              <span class="text">3</span>
              <i class="bx bx-category"></i>
              <span class="text">Categories</span>
                
             </div>
             <div class="box box3">
              <span class="text">4</span>
             <i class="bx bx-bar-chart-square icon"></i>
                <span class="text">Events</span>
             </div>
             
            
          
          </div>
     
          
          <!--
          <section class="main-content">
            <div class="card">
                <span class="text">1</span>
                <i class="bx bx-user"></i>
                <span class="text">Users</span>
            </div>
            <div class="card">
                <span class="text">4</span>
              <i class="bx bx-user-check"></i>
                <span class="text">Total Event <br>Registration</span>
            </div>
            <div class="card">
                <span class="text">3</span>
                <i class="bx bx-category"></i>
                <span class="text">Categories</span>
            </div>
            <div class="card">
                <span class="text">4</span>
              <i class="bx bx-scatter-chart"></i>
                <span class="text">Events</span>
            </div>
          </section>

          <section class="main-content">
            <div class="card">
                <span class="text">1</span>
              <i class="bx bx-basketball"></i>
                <span class="text">Basketball <br>Registration</span>
            </div>
            <div class="card">
                <span class="text">1</span>
              <i class="uil uil-volleyball"></i>
                <span class="text">VolleyBall <br> Registration</span>
            </div>
            <div class="card">
                <span class="text">1</span>
              <i class="bx bx-music"></i>
                <span class="text">Christmas Choir<br>Registration</span>
            </div>
            <div class="card">
                <span class="text">1</span>
                <i class="bx bx-dice-2"></i>
                <span class="text">Quiz Bee <br> Registration</span>
            </div>
          </section>
      -->
       </div>
       <div class="activity">
             <div class="title">
              <i class="bx bx-time"></i>
              <span class="text">Recent User Added </span>
             </div>         
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
<div class="view-all">
    <a href="user_list.php">View All</a>
</div>

   </div>
      </section>
        <script src="../js/script.js"></script>0

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