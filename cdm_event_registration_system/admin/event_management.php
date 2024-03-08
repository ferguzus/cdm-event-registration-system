<!-- event_management.php -->
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

// Initialize $data array
$data = [];

// Fetch all event categories with distinct events
$sql_categories = "SELECT category, GROUP_CONCAT(DISTINCT event) as events FROM event_registrations GROUP BY category";
$result_categories = mysqli_query($conn, $sql_categories);

if (!$result_categories) {
    echo "Error fetching categories: " . mysqli_error($conn);
    exit();
}

$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);



// Handle filter form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter'])) {
    $selectedCategory = $_POST['category'];
    $selectedEvent = $_POST['event'];

    // Build SQL query based on selected filters
    $sql_event_registrations = "
    SELECT CONCAT(user_login_list.first_name, ' ', user_login_list.last_name) AS full_name, user_login_list.gender, event_registrations.user_id, event_registrations.category, event_registrations.event, event_registrations.registration_time, event_registrations.status
    FROM event_registrations
    INNER JOIN user_login_list ON event_registrations.user_id = user_login_list.id
    WHERE (event_registrations.category = ? OR ? = 'All') AND (event_registrations.event = ? OR ? = 'All')
    ";
    $stmt_event_registrations = mysqli_prepare($conn, $sql_event_registrations);

    if ($stmt_event_registrations) {
        mysqli_stmt_bind_param($stmt_event_registrations, "ssss", $selectedCategory, $selectedCategory, $selectedEvent, $selectedEvent);
        mysqli_stmt_execute($stmt_event_registrations);
        mysqli_stmt_bind_result($stmt_event_registrations, $full_name, $gender, $user_id, $category, $event, $registration_time, $status);

        // Fetch the result
        while (mysqli_stmt_fetch($stmt_event_registrations)) {
            $data[] = array($full_name, $gender, $user_id, $category, $event, $registration_time, $status);
        }

        mysqli_stmt_close($stmt_event_registrations);
    } else {
        echo "Error preparing event registration statement.";
        exit();
    }
} else {
    // Fetch all event registration information
    $sql_event_registrations = "
    SELECT CONCAT(user_login_list.first_name, ' ', user_login_list.last_name) AS full_name, user_login_list.gender, event_registrations.user_id, event_registrations.category, event_registrations.event, event_registrations.registration_time, event_registrations.status
    FROM event_registrations
    INNER JOIN user_login_list ON event_registrations.user_id = user_login_list.id
    ";
    $stmt_event_registrations = mysqli_prepare($conn, $sql_event_registrations);

    if ($stmt_event_registrations) {
        mysqli_stmt_execute($stmt_event_registrations);
        mysqli_stmt_bind_result($stmt_event_registrations, $full_name, $gender, $user_id, $category, $event, $registration_time, $status);

        // Fetch the result
        while (mysqli_stmt_fetch($stmt_event_registrations)) {
            $data[] = array($full_name, $gender, $user_id, $category, $event, $registration_time, $status);
        }

        mysqli_stmt_close($stmt_event_registrations);
    } else {
        echo "Error preparing event registration statement.";
        exit();
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="../css/dash-board.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Event Registrations</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            color: white;
        }

        .filter-form {
            margin-bottom: 20px;
        }

        table {
            max-width: 1200px; /* Adjust the value as needed */
            width: 100%;
        }

        .thead-shadow {
            background-color: lightgray;
            box-shadow: 0 4px 6px -6px gray; /* Adjust the values to control the shadow */
            border: 5px solid gray; /* Add a border */
            border-radius: 10px; /* Add border-radius for rounded corners */
        }

        .filter-form {
            margin-top: 20px;
        }

        .category-select,
        .event-select {
            width: 200px;
            margin-bottom: 10px;
            margin-right: 5px;
            padding-right: 5px;
        }

        .text-center-md {
            text-align: center !important;
        }

        /* Center-align options in select elements */
        .category-select,
        .event-select {
            text-align-last: center;
        }

        /* Center-align filter button and all events link */
        .filter-button {
            display: flex;
            margin-top: 22px;
            margin-right: 40px;
        }

        .all-events-button {
            margin-top: 30px;
            text-align: center;
        }

        .back-button {
            margin-left: 20px;
        }

        .logout-form {
          margin-left: 32px; /* Adjust the margin as needed */
          margin-bottom: 10px;


        }


    </style>
    <script>
        // JavaScript to dynamically populate the second selection based on the first selection
        function updateEvents() {
            var category = document.getElementById('category').value;
            var eventSelect = document.getElementById('event');

            // Clear existing options
            eventSelect.innerHTML = '<option value="" disabled selected>Select Event</option>';

            // Always add "All" option for events when "All" or "Sport" category is selected
            if (category.toLowerCase() === 'all' || category.toLowerCase() === 'sport') {
                var allOption = document.createElement('option');
                allOption.value = 'All';
                allOption.text = 'All';
                eventSelect.add(allOption);
            }

            // Populate options based on the selected category
            var categories = <?php echo json_encode($categories); ?>;
            var selectedCategory = categories.find(function (item) {
                return item.category.toLowerCase() === category.toLowerCase();
            });

            if (selectedCategory) {
                // Split events by comma
                var events = selectedCategory.events.split(',');

                // Add each event to the dropdown
                events.forEach(function (event) {
                    var option = document.createElement('option');
                    option.value = event.trim(); // trim to remove leading/trailing whitespaces
                    option.text = event.trim();
                    eventSelect.add(option);
                });
            }

            // Trigger the change event to reload the event registration list
            eventSelect.dispatchEvent(new Event('change'));
        }

        // Initial population of events on page load
        document.addEventListener("DOMContentLoaded", function () {
            updateEvents();
        });
    </script>
</head>

<body>

    <!-- Back Button -->
 


    <!-- Filter Form -->
    <form action="event_management.php" method="post" class="filter-form">
           <a href="admin_dashboard.php" class="btn btn-secondary back-button">Back</a>
        <div class="row">
            <div class="col-md-3 mx-auto text-center-md">
                <label for="category">Category:</label>
                <select name="category" id="category" onchange="updateEvents()"
                    class="form-control category-select mx-auto" required>
                    <option value="" disabled selected>Select Category</option>
                    <option value="All"
                        <?php echo isset($_POST['category']) && $_POST['category'] === 'All' ? 'selected' : ''; ?>>All
                    </option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['category']; ?>"
                        <?php echo isset($_POST['category']) && $_POST['category'] === $cat['category'] ? 'selected' : ''; ?>>
                        <?php echo $cat['category']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 mx-auto text-center-md">
                <label for="event">Events:</label>
                <select name="event" id="event" class="form-control event-select mx-auto" required>
                    <!-- Placeholder option for event selection -->
                    <option value="" disabled selected>Select Event</option>
                </select>
            </div>
            <div class="col-md-3 mx-auto text-center-md">
                <button type="submit" name="filter" class="btn btn-primary filter-button">Filter</button>
            </div>
            <div class="col-md-3 mx-auto text-center-md">
                <a href="event_management.php" class="btn btn-secondary all-events-button" hidden>All Events</a>
            </div>
        </div>
    </form>

    <!-- Event Registrations Table -->
    <table class="table table-dark table-borderless mx-auto" style="border-radius: 15px;">
        <thead class="thead-shadow">
            <tr>
                <th class="text-center">Full Name</th>
                <th class="text-center">Gender</th>
                <th class="text-center">User ID</th>
                <th class="text-center">Category</th>
                <th class="text-center">Event</th>
                <th class="text-center">Registration Time</th>
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
            <tr>
                <td class="text-center"><?php echo $row[0]; ?></td>
                <td class="text-center"><?php echo $row[1]; ?></td>
                <td class="text-center"><?php echo $row[2]; ?></td>
                <td class="text-center"><?php echo $row[3]; ?></td>
                <td class="text-center"><?php echo $row[4]; ?></td>
                <td class="text-center"><?php echo $row[5]; ?></td>
                <td class="text-center"><?php echo $row[6]; ?></td>
                <td>
                    <?php if ($row[6] !== 'Approved'): ?>
                        <form action="approve_registration.php" method="post" style="display: inline-block; margin-right: 5px" onsubmit="return confirmAction('approve');">
      
                        <input type="hidden" name="user_id" value="<?php echo $row[2]; ?>">
                        <input type="hidden" name="category" value="<?php echo $row[3]; ?>">
                        <input type="hidden" name="event" value="<?php echo $row[4]; ?>">
                        <button type="submit" name="approve" class="btn btn-success btn-sm rounded">Approve</button>
                    </form>
                    <?php if ($row[6] !== 'Approved'): ?>
                        <form action="remove_registration.php" method="post" style="display: inline-block;" onsubmit="return confirmAction('remove');">
                        <input type="hidden" name="user_id" value="<?php echo $row[2]; ?>">
                        <input type="hidden" name="category" value="<?php echo $row[3]; ?>">
                        <input type="hidden" name="event" value="<?php echo $row[4]; ?>">
                        <button type="submit" name="remove" class="btn btn-danger btn-sm rounded">Remove</button>
                    </form>
                    <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
    // JavaScript function to confirm action
    function confirmAction(actionType) {
        var confirmationMessage;

        // Set appropriate confirmation message based on action type
        switch (actionType) {
            case 'approve':
                confirmationMessage = "Are you sure you want to approve this registration?";
                break;
            case 'remove':
                confirmationMessage = "Are you sure you want to remove this registration?";
                break;
            default:
                confirmationMessage = "Are you sure you want to cancel your registration?";
        }

        // Show confirmation dialog
        var confirmAction = confirm(confirmationMessage);

        // Return true or false based on user's choice
        return confirmAction;
    }
</script>

</body>

</html>


