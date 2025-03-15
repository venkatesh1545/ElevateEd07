<?php
session_start();
require_once "../WebDevelopmentCourse/db.php";
global $pdo;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html");
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize form inputs
    $title = $_POST['project_title'] ?? 'Untitled Project';
    $description = $_POST['project_description'] ?? 'No description provided.';
    $category = $_POST['project_category'] ?? 'Uncategorized';
    $status = $_POST['project_status'] ?? 'Ongoing';
    $deployed_link = $_POST['deployed_link'] ?? null;
    $github_link = $_POST['github_link'] ?? null;
    
    // Handle technology stack (multiple select)
    $technology_stack = isset($_POST['technology_stack']) ? implode(', ', $_POST['technology_stack']) : '';

    $image_path = '';
    $upload_success = true;
    $error_message = '';

    if (isset($_FILES['project_image']) && $_FILES['project_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/projects/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES['project_image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('project_') . '.' . $file_extension;
        $target_file = $upload_dir . $filename;

        // Check file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mime_type = mime_content_type($_FILES['project_image']['tmp_name']);
        if (!in_array($mime_type, $allowed_types)) {
            $upload_success = false;
            $error_message = "Invalid file type detected.";
        }

        if ($_FILES['project_image']['size'] > 5 * 1024 * 1024) {
            $upload_success = false;
            $error_message = "File is too large. Maximum size is 5MB.";
        }

        if ($upload_success && move_uploaded_file($_FILES['project_image']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        } else {
            $upload_success = false;
            $error_message = "Failed to upload file.";
        }
    }

    if (!$upload_success) {
        header("Location: user_dashboard.php?error=" . urlencode($error_message));
        exit();
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO projects (
                user_id, title, description, technology_stack, 
                deployed_link, github_link, category, status, 
                image_path, created_at
            ) VALUES (
                ?, ?, ?, ?, 
                ?, ?, ?, ?, 
                ?, NOW()
            )
        ");

        $stmt->execute([
            $userId,
            $title,
            $description,
            $technology_stack,
            $deployed_link,
            $github_link,
            $category,
            $status,
            $image_path
        ]);

        $project_id = $pdo->lastInsertId();

        // Insert collaborators
        if (isset($_POST['collaborator_name']) && is_array($_POST['collaborator_name'])) {
            $collaborator_names = $_POST['collaborator_name'];
            $collaborator_emails = $_POST['collaborator_email'] ?? [];

            $stmt = $pdo->prepare("
                INSERT INTO project_collaborators (
                    project_id, name, email, added_at
                ) VALUES (
                    ?, ?, ?, NOW()
                )
            ");

            for ($i = 0; $i < count($collaborator_names); $i++) {
                $name = trim($collaborator_names[$i]);
                $email = isset($collaborator_emails[$i]) ? trim($collaborator_emails[$i]) : '';

                if (!empty($name) || !empty($email)) {
                    $stmt->execute([$project_id, $name, $email]);
                }
            }
        }

        $pdo->commit();

        header("Location: user_dashboard.php?success=1");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("DB Insert Error: " . $e->getMessage());
        header("Location: user_dashboard.php?error=" . urlencode("Database error. Please try again."));
        exit();
    }
} else {
    header("Location: user_dashboard.php");
    exit();
}
?>