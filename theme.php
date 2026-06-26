<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the selected events from the form
    $events = isset($_POST['events']) ? $_POST['events'] : [];

    // Check if at least one event is selected
    if (!empty($events)) {
        // Database connection variables
        $servername = "localhost";
        $username = "root";  // XAMPP default MySQL username
        $password = "";      // XAMPP default MySQL password
        $dbname = "eventify";

        // Create a connection to the database
        $conn = new mysqli("localhost", "root", "", "eventify", 3307);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Store selected events in the database
        foreach ($events as $event) {
            $event = $conn->real_escape_string($event); // Sanitize input

            // SQL query to insert event into the database
            $sql = "INSERT INTO event_selections (event_name) VALUES ('$event')";

            // Execute the query
            if ($conn->query($sql) === TRUE) {
                // Optionally echo a success message (for debugging)
                // echo "New record created successfully";
            } else {
                // Handle the error
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Close the database connection
        $conn->close();

        // Redirect to pricing.html after successful submission
        header("Location: pricing.html");
        exit();
    } else {
        echo "Please select at least one event.";
    }
}
?>
