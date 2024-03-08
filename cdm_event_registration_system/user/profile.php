<!-- profile.php -->

<?php
session_start();

require_once "../connection/connection.php";

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$conn = connection();
$sql = "SELECT * FROM user_login_list WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "User not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <link rel="stylesheet" type="text/css" href="../css/profile.css"> <!-- Add this line -->
</head>
<body>
    <div class="center">
        <h1>Profile</h1>

        <div class="row">
            <div class="col-md-6">
                <div class="txt_field">
                    <div class="input-box">
                        <span class="details">
                            <label>Student Number:</label>
                            <input type="text" readonly value="<?php echo $user['student_no']; ?>">
                        </span>
                    </div>

                    <div class="input-box">
                        <span class="details">
                            <label>Email:</label>
                            <input type="text" readonly value="<?php echo $user['email']; ?>">
                        </span>
                    </div>

                    <div class="input-box">
                        <span class="details">
                            <label>First Name:</label>
                            <input type="text" readonly value="<?php echo $user['first_name']; ?>">
                        </span>
                    </div>

                    <div class="input-box">
                        <span class="details">
                            <label>Age:</label>
                            <input type="text" readonly value="<?php echo $user['age']; ?>">
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="txt_field">
                    <div class="input-box">
                        <span class="details">
                            <label>Username:</label>
                            <input type="text" readonly value="<?php echo $user['user_name']; ?>">
                        </span>
                    </div>

                    <div class="input-box">
                        <span class="details">
                            <label>Contact Number:</label>
                            <input type="text" readonly value="<?php echo $user['contact_no']; ?>">
                        </span>
                    </div>

                    <div class="input-box">
                        <span class="details">
                            <label>Last Name:</label>
                            <input type="text" readonly value="<?php echo $user['last_name']; ?>">
                        </span>
                    </div>

                    <div class="input-box">
                        <span class="details">
                            <label>Gender:</label>
                            <input type="text" readonly value="<?php echo $user['gender']; ?>">
                        </span>
                    </div>
                </div>
            </div>
        </div>



        <div class="form-btn">
                <a href="calendar_event.php" class="btn btn-secondary" style="margin-left: 290px;">Back</a>
                <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
            </div>

    </div>
</body>
</html>