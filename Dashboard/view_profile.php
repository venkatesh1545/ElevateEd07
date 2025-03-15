<?php
session_start();
require_once "../WebDevelopmentCourse/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user profile data
$stmt = $pdo->prepare("
    SELECT u.*, up.college_name, up.profile_image 
    FROM users u 
    LEFT JOIN user_profiles up ON u.id = up.user_id 
    WHERE u.id = ?
");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch skills
$stmt = $pdo->prepare("SELECT skill_name FROM skills WHERE user_id = ?");
$stmt->execute([$userId]);
$skills = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch social links
$stmt = $pdo->prepare("SELECT platform, url FROM social_links WHERE user_id = ?");
$stmt->execute([$userId]);
$socialLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - DevHub</title>
    <link rel="stylesheet" href="UD_styles.css">
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <header class="top-nav">
        <div class="nav-left">
            <div class="logo">Elevate Ed</div>
            <nav class="main-nav">
                <a href="user_dashboard.php" class="nav-link">Dashboard</a>
                <a href="#" class="nav-link">Learn</a>
                <a href="#" class="nav-link">Compete</a>
                <a href="#" class="nav-link">Support</a>
            </nav>
        </div>
        <div class="nav-right">
            <div class="user-menu">
                <?php
                $defaultImage = "https://via.placeholder.com/50"; // Default image URL
                $profileImage = isset($user['profile_image']) && !empty($user['profile_image'])
                    ? htmlspecialchars($user['profile_image'])
                    : $defaultImage;
                ?>
                <img src="<?php echo $profileImage; ?>" alt="Profile" class="user-avatar">
                <div class="dropdown-menu">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="profile-container">
        <h1>Edit Profile</h1>
        
        <form id="profileForm" action="update_profile.php" method="POST" enctype="multipart/form-data">
            <div class="form-section">
                <h2>Basic Information</h2>
                
                <div class="profile-image-upload">
                    <img src="<?php echo htmlspecialchars($user['profile_image'] ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80'); ?>" 
                         alt="Profile" 
                         id="profilePreview">
                    <input type="file" id="profileImage" name="profile_image" accept="image/*">
                    <label for="profileImage" class="upload-btn">Change Photo</label>
                </div>

                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="full_name" 
                           value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" 
                           value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="college_name">College Name:</label>
                    <input type="text" id="college_name" name="college_name" value="<?php echo htmlspecialchars($user['college_name']); ?>">

                </div>
            </div>

            <div class="form-section">
                <h2>Skills</h2>
                <div class="skills-container">
                    <div class="skills-input">
                        <input type="text" id="skillInput" placeholder="Add a skill">
                        <button type="button" id="addSkill">Add</button>
                    </div>
                    <div class="skills-list" id="skillsList">
                        <?php foreach ($skills as $skill): ?>
                            <span class="skill">
                                <?php echo htmlspecialchars($skill); ?>
                                <button type="button" class="remove-skill">×</button>
                                <input type="hidden" name="skills[]" value="<?php echo htmlspecialchars($skill); ?>">
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2>Social Links</h2>
                <div class="social-links-container">
                    <?php
                    $platforms = ['LinkedIn', 'GitHub', 'LeetCode', 'GeeksforGeeks', 'CodeForces'];
                    foreach ($platforms as $platform) {
                        $url = '';
                        foreach ($socialLinks as $link) {
                            if (strtolower($link['platform']) === strtolower($platform)) {
                                $url = $link['url'];
                                break;
                            }
                        }
                    ?>
                        <div class="form-group">
                            <label for="<?php echo strtolower($platform); ?>"><?php echo $platform; ?> Profile</label>
                            <input type="url" id="<?php echo strtolower($platform); ?>" 
                                   name="social_links[<?php echo strtolower($platform); ?>]" 
                                   value="<?php echo htmlspecialchars($url); ?>"
                                   placeholder="https://">
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">Save Changes</button>
                <a href="user_dashboard.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>

    <script src="profile.js"></script>
</body>
</html>