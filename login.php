<?php
session_start(); // sabse upar hona chahiye

$conn = new mysqli("localhost", "root", "", "eventify", 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $sql  = "SELECT * FROM login WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {

                // ✅ SESSION SAVE KARO
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_email'] = $user['email'];

                header("Location: main.php"); // main.php pe bhejo
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