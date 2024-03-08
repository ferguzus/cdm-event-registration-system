<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}


require_once "../connection/connection.php";
$conn = connection();

?>

<!-- select_category.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/event.css"> <!-- Include your profile styles -->

    <title>Event Registration</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>

body {
    background: #4070f4;
    font-size: 80%; /* Reduce the base font size by 20% */
}

    h1 {
        text-align: center;
    }

    .container h1 {
    position: relative;
    color: #333;
    text-align: center;
    padding: 0 0 2px 0;
    margin: 5px;
    font-size: 40px;
}

    .container h1::before {
	content: '';
	position: absolute;
	bottom: 0;
	height: 3px;
	width: 30px;
	background: #4070f4;
	align-items: center;
}
    .container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 50px;
        box-shadow: rgba(19, 18, 18, 0.5) 0px 7px 29px 0px;
        background: #FFF;
        border: 2px solid rgb(50, 50, 50); /* Dark gray border around the image */
        border-radius: 8px; /* Optional: Add border radius for rounded corners */
    }

        /* Center content inside the modal */
        #musicModal .modal-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

        /* Style for the back button */
    #musicModal .btn-secondary {
        width: 49%; /* Make the back button the same width as the register button */

    }

        /* Center content inside the modal */
    #academicModal .modal-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

        /* Style for the back button */
    #academicModal .btn-secondary {
        width: 49%; /* Make the back button the same width as the register button */

    }

    

    .box-categories {
        display: flex;
        justify-content: space-between; /* Adjust the space between categories */
        margin: 20px 0; /* Add margin for better spacing */
    }

    .category-button {

        color: white;
        padding: 20px; /* Increase the padding to make buttons larger */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center; /* Center the icon vertically */
        transition: box-shadow 0.3s;
        text-align: center;
        border: 2px solid #4070f4;
    }

    .category-button:hover {
        /* transform: scale(1.03); */
        box-shadow: 0px 0px 10px rgba(238, 237, 237, 0.4);
        background-color: rgb(243, 248, 255); /* Light gray background color */
        transition: box-shadow 0.3s, background-color 0.3s; /* Add transition to both properties */


        transition: box-shadow 0.3s;
    }

    .category-icon {
        width: 200px; /* Adjust the size of the icon as needed */
        margin-bottom: 10px; /* Add margin for better spacing between icon and button */
        
    }

    

    .event-icon {
        width: 200px; /* Adjust the size of the icon as needed */
        margin-bottom: 10px; /* Add margin for better spacing between icon and button */
    }

    

    .image-box {
    border: 2px solid rgba(20, 245, 0, 0.4); /* Add a border around the image */
    border-radius: 8px; /* Optional: Add border radius for rounded corners */
    overflow: hidden; /* Hide any overflow content (e.g., if the image is larger than the box) */
    border: 2px solid #4070f4;
    transition: box-shadow 0.3s;
    
}

.image-box:hover {
    box-shadow: 0px 0px 10px rgba(0, 0, 255, 0.4);
    
    transition: box-shadow 0.3s;
}

.image-box img {
    width: 80%; /* Make the image fill the container */
    display: block; /* Remove extra space below the image */
}

.categoryname-text {
    font-size: 30px; /* Adjust the font size for the smaller text */    
    padding: 10px; /* Adjust the padding for spacing around the text */
    border-radius: 0 0 8px 8px; /* Add border radius to the bottom corners for a rounded appearance */
    text-align: center; /* Center the text horizontally */
    font-family: 'Poppins', sans-serif; /* Use your preferred font */
    margin-top: -65px; /* Adjust the top margin */    
    color: white; /* Adjust the color of the smaller text */
    text-shadow: 2px 2px 5px #4070f4;
    transition: text-shadow 0.3s;
}

.categoryname-text:hover {
    text-shadow: -2px -2px 5px #4070f4;
    transition: text-shadow 0.3s;
}

.eventname-text {
    background-color: rgba(255, 255, 255, 0.8); /* Background color for the text box */
    padding: 10px; /* Adjust the padding for spacing around the text */
    border-radius: 0 0 8px 8px; /* Add border radius to the bottom corners for a rounded appearance */
    text-align: center; /* Center the text horizontally */
    font-family: 'Poppins', sans-serif; /* Use your preferred font */
    margin-top: -100px; /* Adjust the top margin */    
}

.eventdate-text {
    font-size: 12px; /* Adjust the font size for the smaller text */
    color: #555;
    text-align: center; /* Center the text horizontally */
    font-family: 'Poppins', sans-serif; /* Use your preferred font */
    margin-top: -10px; /* Adjust the top margin to bring it closer to the box text */
    margin-bottom: 10px; /* Add a bottom margin to the smaller text */
}

.event-description {
    font-size: 9px; /* Adjust the font size for the additional text */
    color: #999; /* Darker gray color for the additional text */
    text-align: center; /* Center the text horizontally */
    font-family: 'Poppins', sans-serif; /* Use your preferred font */
    margin-top: -5px; /* Adjust the top margin */
    margin-bottom: 10px; /* Add a bottom margin */
}

.second-event-description {
    font-size: 9px; /* Adjust the font size for the additional text */
    color: #999; /* Darker gray color for the additional text */
/*    color: #ccc; /* Adjust the color of the additional text */
    text-align: center; /* Center the text horizontally */
    font-family: 'Poppins', sans-serif; /* Use your preferred font */
    margin-top: -10px; /* Adjust the top margin */
    margin-bottom: 10px; /* Add a bottom margin */
}

.register-button {
    background-color: #4070f4;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    margin-top: 10px;
    width: 100%;
    transition: box-shadow 0.3s;
}

.register-button:hover {
    box-shadow: 0px 0px 10px rgba(0, 0, 255, 0.4);
    transition: box-shadow 0.3s;
}

    /* Add this style for the back button */
    .back-to-sidebar-button {
        background-color: #4070f4; /* Use the same color as your other buttons */
        color: white; /* Text color */
        padding: 10px 20px; /* Adjust padding as needed */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        margin-top: 10px; /* Adjust margin as needed */
        width: 15%; /* Make the button full width */
        transition: box-shadow 0.3s;
    }

    .back-to-sidebar-button:hover {
        box-shadow: 0px 0px 10px rgba(0, 0, 255, 0.4);
        transition: box-shadow 0.3s;
    }

    h1 {
    text-align: center;
    font-size: 32px; /* Adjust the font size for h1 */
}
    
</style>


</head>
<body>
<br>
<br>

    <div class="container">
    <button class="back-to-sidebar-button" onclick="goBack()">Back to Sidebar</button>
    <h1>SELECT CATEGORY</h1>
    <section class="box-categories">
        <!-- ACADEMIC -->
        <div class="box-category-box">
            <button type="button" class="category-button" data-bs-toggle="modal" data-bs-target="#academicModal">
                <img src="../img/academic.png" alt="Sport" class="category-icon">
                <div class="categoryname-text">ACADEMIC</div>
            </button>
            <!-- academic_modal.php -->
            <div class="modal fade" id="academicModal" tabindex="-1" aria-labelledby="academicModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <h1>ACADEMIC EVENT</h1>
                        <div class="box-categories">
                            <div class="box-category-box">
                                <div class="image-box">
                                    <img src="../img/quizbee.png" alt="Quizbee" class="event-icon" style="width: 220px;">
                                    <div class="eventname-text">Quiz Bee</div>
                                    <div class="eventdate-text">December 19, 2023</div>
                                    <div class="event-description">Academic competition testing knowledge</div>
                                    <div class="second-event-description">across various subjects in a quiz format.</div>
                                </div>
                                <form id="quizBeeRegistrationForm" action="process_event_registration.php" method="post">
                                    <input type="hidden" name="category" value="Academic">
                                    <input type="hidden" name="event" value="Quiz Bee">
                                    <input type="submit" value="Register" class="buy-button register-button">
                                </form>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MUSIC EVENT -->
        <div class="box-category-box">
            <button type="button" class="category-button" data-bs-toggle="modal" data-bs-target="#musicModal">
                <img src="../img/music.png" alt="Sport" class="category-icon">
                <div class="categoryname-text">MUSIC</div>
            </button>
            <!-- music_modal.php -->
            <div class="modal fade" id="musicModal" tabindex="-1" aria-labelledby="musicModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <h1>MUSIC EVENT</h1>
                        <div class="box-categories">
                            <div class="box-category-box">
                                <div class="image-box">
                                    <img src="../img/christmaschoir.png" alt="Christmaschoir" class="event-icon" style="width: 220px;">
                                    <div class="eventname-text">Christmas choir</div>
                                    <div class="eventdate-text">December 17, 2023</div>
                                    <div class="event-description">Musical event celebrating the festive season</div>
                                    <div class="second-event-description">with choral performances of Christmas carols.</div>
                                </div>
                                <form id="christmaschoirRegistrationForm" action="process_event_registration.php" method="post">
                                    <input type="hidden" name="category" value="Music">
                                    <input type="hidden" name="event" value="Christmas Choir">
                                    <input type="submit" value="Register" class="buy-button register-button">
                                </form>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- SPORT EVENTS -->
        <div class="box-category-box">
            <button type="button" class="category-button" data-bs-toggle="modal" data-bs-target="#sportModal">
                <img src="../img/sport.png" alt="Sport" class="category-icon">
                <div class="categoryname-text">SPORT</div>
            </button>
            <!-- sport_modal.php -->
            <div class="modal fade" id="sportModal" tabindex="-1" aria-labelledby="sportModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 39%;">
                    <div class="modal-content">
                        <h1>SPORT EVENTS</h1>
                        <div class="box-categories">
                            <div class="box-category-box">
                                <div class="image-box">
                                    <img src="../img/basketball.png" alt="Basketball" class="event-icon" style="width: 220px;">
                                    <div class="eventname-text">Basketball</div>
                                    <div class="eventdate-text">December 14, 2023</div>
                                    <div class="event-description">Fast-paced team sport, five players per team,</div>
                                    <div class="second-event-description">score by shooting a ball through a hoop.</div>
                                </div>
                                <form id="basketballRegistrationForm" action="process_event_registration.php" method="post">
                                    <input type="hidden" name="category" value="Sport">
                                    <input type="hidden" name="event" value="Basketball">
                                    <input type="submit" value="Register" class="buy-button register-button">
                                </form>
                            </div>
                            <div class="box-category-box">
                                <div class="image-box">
                                    <img src="../img/volleyball.png" alt="Volleyball" class="event-icon" style="width: 220px;">
                                    <div class="eventname-text">Volleyball</div>
                                    <div class="eventdate-text">December 15, 2023</div>
                                    <div class="event-description">Dynamic team sport, six players per team,</div>
                                    <div class="second-event-description">score points by sending the ball over a net.</div>
                                </div>
                                <form id="volleyballRegistrationForm" action="process_event_registration.php" method="post">
                                    <input type="hidden" name="category" value="Sport">
                                    <input type="hidden" name="event" value="Volleyball">
                                    <input type="submit" value="Register" class="buy-button register-button">
                                </form>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
    // Remove the continue button element
    const continueButton = document.getElementById("continueButton");
    if (continueButton) {
      continueButton.parentNode.removeChild(continueButton);
    }


// JavaScript function to go back to the user sidebar page
function goBack() {
    window.location.href = 'user_sidebar.php'; // Replace 'path-to-your-sidebar-page' with the actual path
}
  </script>
  
</body>

<script>


</script>


</html>