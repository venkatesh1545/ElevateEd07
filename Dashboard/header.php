<<?php
// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}

// Include database connection file
require_once '../WebDevelopmentCourse/db.php';

// Fetch user data
$stmt = $pdo->prepare("
    SELECT u.*, up.profile_image, up.college_name 
    FROM users u 
    LEFT JOIN user_profiles up ON u.id = up.user_id 
    WHERE u.id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<header class="top-nav">
    <div class="nav-left">
        <div class="logo">DevHub</div>
        <nav class="main-nav">
            <a href="user_dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">Learn</a>
            <a href="compete.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'compete.php' ? 'active' : ''; ?>">Compete</a>
            <a href="support.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'support.php' ? 'active' : ''; ?>">Support</a>
        </nav>
    </div>
    <div class="nav-right">
        <div class="user-menu">
            <img src="<?php echo htmlspecialchars($user['default_avatar.jpg'] ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80'); ?>" 
                alt="Profile" 
                class="user-avatar"
                id="userMenuTrigger">
            <div class="dropdown-menu" id="userDropdown">
                <div class="user-info">
                    <strong><?php echo htmlspecialchars($user['full_name']); ?></strong>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="dropdown-divider"></div>
                <a href="profile.php">View Profile</a>
                <a href="settings.php">Settings</a>
                <div class="dropdown-divider"></div>
                <a href="../logout.php">Sign Out</a>
            </div>
        </div>
    </div>
</header>