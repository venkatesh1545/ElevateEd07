<?php
session_start();
require_once "../WebDevelopmentCourse/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html");
    exit();
}

$userId = $_SESSION['user_id'];

try {
    $pdo->beginTransaction();

    // Update basic user information
    $stmt = $pdo->prepare("
        UPDATE users 
        SET full_name = ?, username = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $_POST['full_name'],
        $_POST['username'],
        $userId
    ]);

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        $fileExtension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $newFileName = $userId . '_' . time() . '.' . $fileExtension;
        $uploadFile = $uploadDir . $newFileName;
    
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
            // Update profile image in database
            $stmt = $pdo->prepare("
                INSERT INTO user_profiles (user_id, profile_image, college_name)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                profile_image = VALUES(profile_image),
                college_name = VALUES(college_name)
            ");
            $stmt->execute([$userId, $uploadFile, $_POST['college_name']]);
        }
    } 
    
    // Update only college name if no image was uploaded
    if (isset($_POST['college_name'])) {
        $college_name = $_POST['college_name'];
    
        $stmt = $pdo->prepare("UPDATE user_profiles SET college_name = ? WHERE user_id = ?");
        $stmt->execute([$college_name, $userId]);
    }
    // update user profile image
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $imagePath = "uploads/" . basename($_FILES['profile_image']['name']);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $imagePath);
    
        // Update in database
        $stmt = $pdo->prepare("UPDATE user_profiles SET profile_image = ? WHERE user_id = ?");
        $stmt->execute([$imagePath, $_SESSION['user_id']]);
    }
    
    // Update skills
    $stmt = $pdo->prepare("DELETE FROM skills WHERE user_id = ?");
    $stmt->execute([$userId]);

    if (isset($_POST['skills']) && is_array($_POST['skills'])) {
        $stmt = $pdo->prepare("INSERT INTO skills (user_id, skill_name) VALUES (?, ?)");
        foreach ($_POST['skills'] as $skill) {
            $stmt->execute([$userId, $skill]);
        }
    }

    // Update social links
    $stmt = $pdo->prepare("DELETE FROM social_links WHERE user_id = ?");
    $stmt->execute([$userId]);

    if (isset($_POST['social_links']) && is_array($_POST['social_links'])) {
        $stmt = $pdo->prepare("INSERT INTO social_links (user_id, platform, url) VALUES (?, ?, ?)");
        foreach ($_POST['social_links'] as $platform => $url) {
            if (!empty($url)) {
                $stmt->execute([$userId, $platform, $url]);
            }
        }
    }

    $pdo->commit();
    $_SESSION['full_name'] = $_POST['full_name'];
    $_SESSION['username'] = $_POST['username'];

    header("Location: user_dashboard.php");
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error updating profile: " . $e->getMessage();
}
?>