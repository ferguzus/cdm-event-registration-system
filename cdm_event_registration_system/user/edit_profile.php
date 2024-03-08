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

// Handle form submission for updating profile
if (isset($_POST['update_profile'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $contactnumber = $_POST['contact_number'];
    $username = $_POST['username'];

    $errors = array();

    // Validation logic for first name
    if (!preg_match('/^[a-zA-Z ]{1,25}$/', $firstname)) {
        array_push($errors, "First name must contain only alphabets and be up to 25 characters long");
    }

    // Validation logic for last name
    if (!preg_match('/^[a-zA-Z ]{1,20}$/', $lastname)) {
        array_push($errors, "Last name must contain only alphabets and be up to 20 characters long");
    }

    // Validation logic for consecutive spaces in first name and last name
    if (preg_match('/\s\s/', $firstname) || preg_match('/\s\s/', $lastname)) {
        array_push($errors, "First name and last name cannot contain consecutive spaces");
    }

    // Validation logic for contact number
    if (substr($contactnumber, 0, 2) !== "09" || !preg_match('/^\d{11}$/', $contactnumber)) {
        array_push($errors, "Contact number must start with '09' and be exactly 11 digits");
    }

    // Validation logic for age
    if ($age < 18 || $age > 40) {
        array_push($errors, "Please select an age between 18 and 40.");
    }

    if (count($errors) > 0) {
        echo "<div class='alert alert-danger'>";
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo "</div>";
    } else {
        // Update the user profile
        $updateSql = "UPDATE user_login_list SET 
                        first_name = '$firstname',
                        last_name = '$lastname',
                        gender = '$gender',
                        age = '$age',
                        email = '$email',
                        contact_no = '$contactnumber',
                        user_name = '$username'
                        WHERE id = '$user_id'";

        if (mysqli_query($conn, $updateSql)) {
            // Redirect to profile.php after a successful update
            header("Location: profile.php");
            exit();
        } else {
            echo "<div class='alert alert-danger left'>Error updating profile: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/profile.css">

    <script>
    function checkForSpaces() {
        var firstName = document.getElementById('firstname').value;
        var lastName = document.getElementById('lastname').value;

        // Check for non-alphabetic characters in first name and last name
        if (!/^[a-zA-Z]+$/.test(firstName) || !/^[a-zA-Z]+$/.test(lastName)) {
            alert('First name and last name must contain only alphabetic characters.');
            return false;
        }

        // Check for consecutive spaces in first name and last name
        if (/\s\s/.test(firstName) || /\s\s/.test(lastName)) {
            alert('First name and last name cannot contain consecutive spaces.');
            return false;
        }

        return true;
    }

    // Function to validate age
    function validateAge(input) {
        var age = input.value;

        // Check if the age is within the specified range (18 - 40)
        if (age < 18 || age > 40) {
            input.setCustomValidity("Please select an age between 18 and 40.");
        } else {
            input.setCustomValidity(""); // Clear any previous validation message
        }
    }
</script>



</head>

<style>
    .update-profile-btn,
    .cancel-btn {
        width: 150px;
        height: 40px;
        border-radius: 10px;
        background-color: #4070f4;
        color: white;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
    }

    .cancel-btn {
        background-color: #fff;
        color: #4070f4;
    }

    .cancel-btn:hover {
        background-color: #f2f2f2;
        color: #4070f4;
    }

    .update-profile-btn:hover {
        background-color: #333;
    }

    .form-btn input {
        justify-content: center;
        display: flex;
        margin-top: 20px;
        margin-right: -10px;
        margin-left: 35px;

    }

    .form-btn a {
        margin-right: -10px;
        margin-left: 50px;
    }

    .form-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    /* Center-top alert styling */
    .center-top-alert {
        position: fixed;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 300px;
        text-align: center;
        z-index: 1000;
        margin-top: 20px; /* Added margin-top to center the alert */
    }

    .alert-danger {
        position: fixed;
        top: 100px; /* Adjusted margin-top to move the alert down */
        left: 50%;
        transform: translateX(-50%);
        width: 300px;
        text-align: center;
        z-index: 1000;
    }

    .alert-danger.left {
        display: none;
    }


</style>

<body>
    <div class="center">
        <h1>Edit Profile</h1>



        <form action="edit_profile.php" method="post" class="txt_field" >
            <div class="row">
                <div class="col-md-6">
                    <div class="input-box">
                        <span class="details">
                            <label>Student Number:</label>
                            <input type="text" name="student_number" value="<?php echo $user['student_no']; ?>" readonly>
                        </span>
                    </div>

                    <div class="input-box">
                        <span class="details">
                            <label>Email:</label>
                            <input type="email" name="email" value="<?php echo $user['email']; ?>" readonly>
                        </span>
                    </div>

                    <div class="input-box">
                        <span class="details">
                            <label>First Name:</label>
                            <input type="text" class="form-control" name="firstname" value="<?php echo $user['first_name']; ?>" onsubmit="return checkForSpaces();">
                        </span>
                    </div>

                    <div class="input-box">
                        <span class="details">
                            <label>Age:</label>
                            <input type="text" class="form-control" name="age" value="<?php echo $user['age']; ?>" required>
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-box">
                        <span class="details">
                            <label>Username:</label>
                            <input type="text" name="username" value="<?php echo $user['user_name']; ?>" readonly>
                        </span>
                    </div>

                    <div class="input-box">
                        <span class="details">
                            <label>Contact Number:</label>
                            <input type="tel" class="form-control" name="contact_number" value="<?php echo $user['contact_no']; ?>">
                        </span>
                    </div>

                    <div class="input-box">
                        <span class="details">
                            <label>Last Name:</label>
                            <input type="text" class="form-control" name="lastname" value="<?php echo $user['last_name']; ?>" onsubmit="return checkForSpaces();">
                        </span>
                    </div>

                    <div class="input-box">
                        <span class="details">
                            <label>Gender:</label>
                            <select class="form-control" name="gender" style="margin-top: 3px;">
                                <option value="Male" <?php echo ($user['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($user['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Prefer not to say" <?php echo ($user['gender'] === 'Prefer not to say') ? 'selected' : ''; ?>>Prefer not to say</option>
                            </select>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-btn">
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
                <input type="submit" value="Update Profile" name="update_profile" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>

</html>