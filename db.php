<?php
$host = "localhost";
$dbname = "elevate_ed";
$username = "root";  // Change if necessary
$password = "";      // Set your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
// Helper function to get user data
function getUserData($userId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT u.*, up.profile_image, up.college_name,
               (SELECT COUNT(*) FROM user_skills WHERE user_id = u.id) as skill_count
        FROM users u
        LEFT JOIN user_profiles up ON u.id = up.user_id
        WHERE u.id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

// Helper function to get user skills
function getUserSkills($userId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT s.*, us.progress
        FROM skills s
        JOIN user_skills us ON s.id = us.skill_id
        WHERE us.user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}
?>