<?php
$conn = new mysqli("localhost", "root", "", "eventify", 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loginMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // sirf email se user dhundo
        $sql = "SELECT * FROM login WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // bcrypt se password verify karo
            if (password_verify($password, $user['password'])) {
                header("Location: main.html");
                exit();
            } else {
                header("Location: login.html?error=1");
                exit();
            }
        } else {
            header("Location: login.html?error=1");
            exit();
        }
        $stmt->close();
    } else {
        header("Location: login.html?error=2");
        exit();
    }
}
$conn->close();
?>