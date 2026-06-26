<?php
// Database connection configuration
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "eventify";

// Create a connection
$conn = new mysqli("localhost", "root", "", "eventify", 3307);
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    if (!empty($fullname) && !empty($email) && !empty($subject) && !empty($message)) {
        // Insert data into the database
        $sql = "INSERT INTO contact (fullname, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fullname, $email, $subject, $message);

        if ($stmt->execute()) {
            $successMessage = "Your message has been sent successfully!";
        } else {
            $errorMessage = "Error: Unable to send your message. Please try again.";
        }

        $stmt->close();
    } else {
        $errorMessage = "Please fill in all fields.";
    }
}

$conn->close();

// Display message back on the contact page
if ($successMessage || $errorMessage) {
    echo "<script>
        alert('" . ($successMessage ?: $errorMessage) . "');
        window.location.href = 'contact.html';
    </script>";
    exit();
}
?>
