<?php
session_start();
require_once "../WebDevelopmentCourse/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_skill'])) {
    $skill_id = $_POST['delete_skill'];
    
    // Prepare the SQL statement
    $sql = "DELETE FROM selected_skills WHERE id = :skill_id";
    $stmt = $pdo->prepare($sql);
    
    // Bind the parameter
    $stmt->bindParam(':skill_id', $skill_id, PDO::PARAM_INT);
    
    // Execute the query
    $stmt->execute();
    
    // Redirect to progress page
    header("Location: progress.php");
    exit();
}
?>
