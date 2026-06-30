<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eventify";

$conn = new mysqli("localhost", "root", "", "eventify", 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        header("Location: signup.php?error=mismatch");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO login (name, email, password) VALUES (?, ?, ?)");
    
    if (!$stmt) {
        // Table mein name column nahi ya query galat
        die("Query Error: " . $conn->error);
    }
    
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: main.php");
        exit();
    } else {
        die("Insert Error: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: signup.html");
    exit();
}
?>