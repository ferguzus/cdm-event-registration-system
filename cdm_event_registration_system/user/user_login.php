<!-- user_login.php -->

<?php
session_start(); // Start the session to access user data

require_once "../connection/connection.php";

// Logout logic
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();
}


// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: user_sidebar.php"); // Redirect to index page
    exit();
}

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $pass = $_POST["password"];

    // Call your connection function
    $conn = connection(); // Make sure to define your connection function

    $sql = "SELECT * FROM user_login_list WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($user) {
        if (password_verify($pass, $user["pass_word"])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['user_name']; // Set the username

            // Redirect to the dashboard or home page
            header("Location: user_sidebar.php");
            exit();
        } else {
            $error_message = "Password does not match";
        }
    } else {
        $error_message = "Email does not match";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MFY6L6IFaaNjsqPQr4H3jz3Lm8KbVRVo6zA/qvuN8l+2LrFLXggzuiRabC1Imw3V" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/login.css">

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

        <form action="user_login.php" method="post">
        <h1>LOGIN</h1>
            <div class="form-group">
            <label for="username" class="text">Email:</label>
                <input type="email" placeholder="Enter Email:" name="email" class="form-control">
            </div>
            <div class="form-group">
            <label for="password" class="text">Password:</label>
                <input type="password" placeholder="Enter Password:" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="btn btn-primary">
            </div>

            <p>Don't have account? <a href="sign_up.php">Create Account</a></p>
        </form>
        <p>Admin? <a href="../admin/admin_login.php">Login here</a></p>



 
    </div>

    

</body>
</html>