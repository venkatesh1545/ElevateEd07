<?php
require "db.php";  
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Fetch user data from the database
    $stmt = $pdo->prepare("SELECT id, full_name, username, password_hash FROM users WHERE email = :email");
    $stmt->execute(["email" => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password_hash"])) {
        // Store user data in session
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["full_name"] = $user["full_name"];

        // Pass data via JavaScript
        echo "<script>
            sessionStorage.setItem('username', '" . $user['username'] . "');
            sessionStorage.setItem('full_name', '" . $user['full_name'] . "');
            window.location.href = 'Dashboard/user_dashboard.php';
        </script>";
        exit();
    } else {
        echo "Invalid email or password.";
    }
}
?>
