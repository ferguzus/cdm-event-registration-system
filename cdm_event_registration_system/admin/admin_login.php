<?php
session_start();
require_once "../connection/connection.php";

if (isset($_POST["admin_login"])) {
    // Admin login logic here

    // For demonstration purposes, let's assume admin credentials
    $admin_username = "pogingadmin";
    $admin_hashed_password = password_hash("adminpogihaha", PASSWORD_DEFAULT); // Replace with the actual hashed password from your database

    $input_username = $_POST["username"];
    $input_password = $_POST["password"];

    $conn = connection();

    $sql = "SELECT * FROM admin_table WHERE admin_username = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $input_username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($result);

        if ($admin && password_verify($input_password, $admin['admin_password'])) {
            // Set session variables
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $admin['admin_username'];
            $_SESSION['admin_logged_in'] = true; // Set the admin login status
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid admin credentials";
        }
        
    }
}

// Include admin_logout.php only after the login logic
require_once "admin_logout.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">   
    <title>Admin Login-Form</title>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron&family=Poppins&display=swap');
        *{
        margin: 0;
        padding: 0;
        font-family: "Poppins";
        overflow: hidden;
}
        body{
            background-color: #3e6ef3;
        }
        .login-container {
            width: 400px;
            margin: 5rem auto;
            box-shadow: 0 15px 30px rgb(0, 0, 0, 0.3);
            border-radius: 15px;
            background-color: #fff;
            padding: 35px;
        }

    input{
        display: block;
        width: 89%;
        outline: none;
        padding: 15px;
        margin-top: 10px;
        border-radius: 10px;
        font-size: 0.9rem;
        border: 1px solid grey;
    }
   .form-btn input{
    width: 100%;
    display: block;
    margin-top: 15px;
    padding: 15px 0;
    background-color: #3e6ef3;
    color: #fff;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    border-radius: 10px;
    }
      h1{
        display: flex;
        align-items: center;
        justify-content: center;
      }
     p{
        padding: 15px ;
    }
    p a{
        color: #0E4BF1;
        text-decoration: none;
    }
      .text{
        font-size: 1rem;
        font-weight: 500;
      }

    </style>
</head>
<body>
    
    <div class="login-container">
        <?php
        if (isset($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        ?>    
        <form action="admin_login.php" method="post">
            <h1>LOGIN</h1>
            <div class="form-group">
                <label for="username" class="text">Username:</label>
                <input type="text" name="username"  placeholder="Enter Username:"class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password" class="text">Password:</label>
                <input type="password" name="password"   placeholder="Enter Password:"class="form-control" required>
            </div>
            <div class="form-btn">
                <input type="submit" value="Admin Login" name="admin_login" class="btn btn-primary">
            </div>
        </form>

        <p>User? <a href="../user/user_login.php">Login here</a></p>
    </div>
</body>
</html>