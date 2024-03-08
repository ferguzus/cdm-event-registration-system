<!-- sign_up.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/profile.css">
    <!-- Include Bootstrap CSS -->
    
    <title>Registration Form</title>

    <style>
        /* Add this style to make the dropdown scrollable */
        .dropdown {
            height: 40px; /* Adjust the max height as needed */
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div class="center">
        <?php
        session_start(); // Start the session to access user data
        require_once "../connection/connection.php";

        if (isset($_POST["submit"])) {
            $conn = connection(); // Call your connection function
            $studentnumber = $_POST["student_number"];
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $email = $_POST["email"];
            $contactnumber = $_POST["contact_number"];
            $username = $_POST["username"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];
            $gender = $_POST["gender"];
            $age = $_POST["age"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();

            // Validation logic for student number
            if (!preg_match('/^22-\d{5}$/', $studentnumber)) {
                array_push($errors, "Student number must be in the format '22-XXXXX'");
            }

            // Validation logic for first name
            if (!preg_match('/^[a-zA-Z ]{1,25}$/', $firstname)) {
                array_push($errors, "First name must contain only alphabets and be up to 25 characters long");
            }

            // Validation logic for last name
            if (!preg_match('/^[a-zA-Z ]{1,20}$/', $lastname)) {
                array_push($errors, "Last name must contain only alphabets and be up to 20 characters long");
            }

            // Validation logic for email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }

            // Validation logic for contact number
            if (substr($contactnumber, 0, 2) !== "09" || !preg_match('/^\d{11}$/', $contactnumber)) {
                array_push($errors, "Contact number must start with '09' and be exactly 11 digits");
            }

            // // Validation logic for username
            // if (!preg_match('/^[a-z0-9]{8,20}$/i', $username)) {
            //     array_push($errors, "Username must be 8 to 20 characters long and contain only alphanumeric characters");
            // }

            // Validation logic for password
            if (strlen($password) < 10) {
                array_push($errors, "Password must be at least 10 characters long");
            }

            // Validation logic for matching passwords
            if ($password !== $passwordRepeat) {
                array_push($errors, "Passwords do not match");
            }

            // Check if student number already exists
            $sqlStudentNumber = "SELECT * FROM user_login_list WHERE student_no = '$studentnumber'";
            $resultStudentNumber = mysqli_query($conn, $sqlStudentNumber);
            $rowCountStudentNumber = mysqli_num_rows($resultStudentNumber);
            if ($rowCountStudentNumber > 0) {
                array_push($errors, "Student number already registered!");
            }

            // Check if email already exists
            $sqlEmail = "SELECT * FROM user_login_list WHERE email = '$email'";
            $resultEmail = mysqli_query($conn, $sqlEmail);
            $rowCountEmail = mysqli_num_rows($resultEmail);
            if ($rowCountEmail > 0) {
                array_push($errors, "Email already exists!");
            }

            // Check if contact number already exists
            $sqlContactNumber = "SELECT * FROM user_login_list WHERE contact_no = '$contactnumber'";
            $resultContactNumber = mysqli_query($conn, $sqlContactNumber);
            $rowCountContactNumber = mysqli_num_rows($resultContactNumber);
            if ($rowCountContactNumber > 0) {
                array_push($errors, "Contact number already exists!");
            }

            // Check if username already exists
            $sqlUsername = "SELECT * FROM user_login_list WHERE user_name = '$username'";
            $resultUsername = mysqli_query($conn, $sqlUsername);
            $rowCountUsername = mysqli_num_rows($resultUsername);
            if ($rowCountUsername > 0) {
                array_push($errors, "Username already exists!");
            }

            if (count($errors) > 0) {
                echo "<div class='alert alert-danger'>";
                foreach ($errors as $error) {
                    echo "<p>$error</p>";
                }
                echo "</div>";
            } else {
                // Insert the data into the database
                $sql = "INSERT INTO `user_login_list`(`student_no`, `first_name`, `last_name`, `gender`, `age`, `email`, `contact_no`, `user_name`, `pass_word`, `access`) VALUES (?,?,?,?,?,?,?,?,?, 'user')";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sql);

                if ($prepareStmt) {
                    mysqli_stmt_bind_param($stmt, "sssssssss", $studentnumber, $firstname, $lastname, $gender, $age, $email, $contactnumber, $username, $passwordHash);

                    mysqli_stmt_execute($stmt);

                    // Set session variables
                    $_SESSION['user_id'] = mysqli_insert_id($conn);
                    $_SESSION['username'] = $username;

                    // Registration successful alert message
                    echo "<div class='alert alert-success'>You are registered successfully.
                    <a href='user_sidebar.php'>Continue</a>
                    <a href='user_login.php?logout=true'>Back to Login</a></div>";              


                } else {
                    die("Something went wrong");
                }
            }
        }
        ?>
                <div class="back-btn">
            <a href="user_login.php" class="btn btn-secondary">Back to Login</a>
        </div>
        <h1>Registration Form</h1>
        <form action="sign_up.php" method="post">
            <div class="txt_field">
                <!-- Student No. -->
                <div class="input-box">
                    <span class="details">
                        <label>Student No.</label><input type="text" name="student_number" maxlength="8" pattern="^22-\d{5}$" title="Student number must be in the format '22-XXXXX'" oninput="truncateInput(this)" placeholder="Student Number:" required>
                    </span>
                </div>

                <!-- First Name -->
                <div class="input-box">
                    <span>
                        <label>First Name</label><input type="text" name="firstname" placeholder="First Name:" onkeypress="return isAlphaWithSingleSpace(event)" maxlength="25" required>
                    </span>
                </div>

                <!-- Last Name -->
                <div class="input-box">
                    <span>
                        <label>Last Name</label><input type="text" name="lastname" placeholder="Last Name:" onkeypress="return isAlphaWithSingleSpace(event)" maxlength="20" required>
                    </span>
                </div>

                <!-- Email -->
                <div class="input-box">
                    <span>
                        <label>Email</label><input type="text" placeholder="@gmail.com" name="email" onkeypress="return disableSpaceKey(event)" maxlength="40" required>
                    </span>
                </div>

                <!-- Contact No. -->
                <div class="input-box">
                    <span>
                        <label>Contact No.</label><input type="tel" name="contact_number" maxlength="11" pattern="^09\d{9}$" title="Contact number must start with '09' Please enter exactly 11 digits" placeholder="[+63] 9181234567" placeholder="Contact Number:" onkeypress="return isNumberKey(event, true)" required>
                    </span>
                </div>

                <!-- User Name -->
                <div class="input-box">
                    <span>
                        <label>UserName</label><input type="text" name="username" placeholder="Username:" onkeypress="return disableSpaceKey(event)" required>
                    </span>
                </div>

                <!-- Password -->
                <div class="input-box">
                    <span>
                        <label>Password</label><input type="password" name="password" placeholder="Password:" onkeypress="return disableSpaceKey(event)" required>
                    </span>
                </div>

                <!-- Confirm Password -->
                <div class="input-box">
                    <span>
                        <label>Confirm Password</label><input type="password" name="repeat_password" placeholder="Repeat Password:" onkeypress="return disableSpaceKey(event)" required>
                    </span>
                </div>

                <!-- Age Dropdown -->
                <div class="input-box">
                    <span>
                        <label>Age</label>
                        <br>
                        <select class="dropdown" name="age" required>
                            <?php
                            // Loop to generate age options from 18 to 40
                            for ($i = 18; $i <= 40; $i++) {
                                echo "<option value=\"$i\">$i</option>";
                            }
                            ?>
                        </select>
                    </span>
                </div>

                <!-- Gender Dropdown -->
                <div class="input-box">
                    <span>
                        <label>Please Select your Gender</label>
                        <select class="dropdown text-center" name="gender" required>
                            <option value="" disabled selected>>--select--</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Prefer not to say</option>
                        </select>
                    </span>
                </div>
            </div>

            <!-- Login Button -->
            <div class="login">
                <input type="submit" value="Register Now!" name="submit">
            </div>
        </form>


    </div>

    <!-- Your existing scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        // para maging format ay ganito 22-00406
        function truncateInput(input) {
            // Get the current value of the input
            let inputValue = input.value;

            // Remove any non-digit characters
            inputValue = inputValue.replace(/\D/g, '');

            // Limit the input to 7 digits
            inputValue = inputValue.substring(0, 8);

            // Add hyphen at the specified position
            if (inputValue.length > 2) {
                inputValue = inputValue.substring(0, 2) + '-' + inputValue.substring(2);
            }

            // Update the input value
            input.value = inputValue;
        }

        // isang beses lang pwede mag space sa pagitan ng dalawang letters
        function isAlphaWithSingleSpace(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;

            // Allow alphabets and a single space between words
            if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122)) {
                return true;
            } else if (charCode === 32) {
                // Check if the last character is not a space
                var inputValue = evt.target.value;
                var lastChar = inputValue.slice(-1);

                // Check if there are already characters entered
                var hasCharacters = inputValue.trim().length > 0;

                if (hasCharacters && lastChar !== ' ') {
                    return true;
                }
            }
            return false;
        }

        // restrict ang space
        function disableSpaceKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;

            // Disable space key (charCode 32) for email, username, password, and confirm password
            if (charCode === 32) {
                return false;
            }
            return true;
        }

        function validateAge(input) {
            // Get the selected age value
            var age = input.value;

            // Check if the age is within the specified range (18 - 40)
            if (age < 18 || age > 40) {
                input.setCustomValidity("Please select an age between 18 and 40.");
            } else {
                input.setCustomValidity(""); // Clear any previous validation message
            }
        }
    </script>
</body>

</html>