<?php
require "db.php";  // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $gender = trim($_POST["gender"]);
    $password = $_POST["password"];

    if (empty($fullName) || empty($email) || empty($username) || empty($password)) {
        die("All fields are required.");
    }

    // Check if the email or username is already registered
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
    $stmt->execute(["email" => $email, "username" => $username]);
    
    if ($stmt->fetch()) {
        die("Email or Username already exists. Try a different one.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, username, gender, password_hash) 
                           VALUES (:full_name, :email, :username, :gender, :password_hash)");
    
    $stmt->execute([
        "full_name" => $fullName,
        "email" => $email,
        "username" => $username,
        "gender" => $gender,
        "password_hash" => $hashedPassword
    ]);

    header("Location: signin.html");
    exit();
}
?>
