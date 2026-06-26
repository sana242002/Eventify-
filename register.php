<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eventify";

$conn = new mysqli("localhost", "root", "", "eventify", 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $conn->real_escape_string($_POST['fullName']);
    $email = $conn->real_escape_string($_POST['email']);
    $eventName = $conn->real_escape_string($_POST['eventName']);
    $eventStartDate = date('Y-m-d', strtotime($_POST['eventStartDate']));
    $eventStartTime = $conn->real_escape_string($_POST['eventStartTime']);
    $eventEndDate = date('Y-m-d', strtotime($_POST['eventEndDate']));
    $eventEndTime = $conn->real_escape_string($_POST['eventEndTime']);
    $city = $conn->real_escape_string($_POST['city']);
    

    // Construct query
    $sql = "INSERT INTO registrations (`full_name`, `email`, `event_name`, `event_start_date`, `event_start_time`, `event_end_date`, `event_end_time`, `city`)
            VALUES ('$fullName', '$email', '$eventName', '$eventStartDate', '$eventStartTime', '$eventEndDate', '$eventEndTime', '$city')";

    // Debugging: Print query
    echo $sql;

    // Execute query
    if ($conn->query($sql) === TRUE) {
        header("Location: theme.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
