<?php
session_start();
require_once "../WebDevelopmentCourse/db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html");
    exit();
}

// Handle deleting a skill
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_skill'])) {
    $skill_id = $_POST['delete_skill'];
    $user_id = $_SESSION['user_id'];

    // Delete the skill from the database
    $sql = "DELETE FROM selected_skills WHERE id = :skill_id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':skill_id', $skill_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect to refresh the page
    header("Location: progress.php");
    exit();
}

// Fetch selected skills from database
// Fetch skills for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, skill_name FROM selected_skills WHERE user_id = :user_id ORDER BY selected_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Tracker</title>
    <link rel="stylesheet" href="progress_styles.css">
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <h2>My Progress List</h2>
            <div id="skillsList" class="skills-list">

                <?php
                if (!empty($skills)) {
                    foreach ($skills as $skill) {
                        echo '<div class="skill-item">
                                <span>' . htmlspecialchars($skill["skill_name"]) . '</span>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="delete_skill" value="' . $skill["id"] . '">
                                    <button type="submit" class="delete-btn" title="Delete">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </div>';
                    }
                } else {
                    echo '<p>No skills selected yet.</p>';
                }
                ?>
            </div>
        </div>
        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Content will be dynamically loaded here -->
        </div>
    </div>
    
    <!-- Modal for Videos -->
    <div id="videoModal" class="modal">
        <div class="modal-content video-modal">
            <span class="close">&times;</span>
            <h2 id="videoTitle"></h2>
            <div class="video-container">
                <iframe id="videoFrame" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- Modal for Articles -->
    <div id="articleModal" class="modal">
        <div class="modal-content article-modal">
            <span class="close">&times;</span>
            <h2 id="articleTitle"></h2>
            <div class="article-content" id="articleContent"></div>
        </div>
    </div>

    <!-- Modal for Certifications -->
    <div id="certModal" class="modal">
        <div class="modal-content cert-modal">
            <span class="close">&times;</span>
            <h2 id="certTitle"></h2>
            <div class="cert-content" id="certContent"></div>
        </div>
    </div>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="progress_data.js"></script>
    <script src="progress_script.js"></script>
    <!-- <script>
    lucide.createIcons();
    
    // Add click handlers for skill items
    document.querySelectorAll('.skill-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.skill-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this skill?')) {
                e.preventDefault();
            }
        });
    });
</script> -->
</body>
</html>