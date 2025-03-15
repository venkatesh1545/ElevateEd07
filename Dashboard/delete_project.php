<?php
session_start();
require_once "../WebDevelopmentCourse/db.php";

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["project_id"])) {
    $project_id = $_POST["project_id"];
    $user_id = $_SESSION["user_id"];

    // Check if the project belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
    $stmt->execute([$project_id, $user_id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($project) {
        // Delete the project
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$project_id]);

        // Redirect back to dashboard with success message
        header("Location: user_dashboard.php?message=Project deleted successfully");
        exit();
    } else {
        echo "Project not found or unauthorized access!";
    }
} else {
    echo "Invalid request!";
}
?>
