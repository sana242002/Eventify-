<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "";     // Replace with your database password
$dbname = "eventify"; // Replace with your database name

// Establish database connection
$conn = new mysqli("localhost", "root", "", "eventify", 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventType = $_POST['event_type'];
    $decorType = $_POST['decor_type'];
    $totalPrice = $_POST['total_price'];

    // Prepare SQL query to insert data
    $sql = "INSERT INTO price ( total_price) VALUES ('$totalPrice')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data saved successfully!');</script>";
        header("Location: main.html"); // Redirect to pricing page after successful submission
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
