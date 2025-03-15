<?php
session_start();
require_once "../WebDevelopmentCourse/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html");
    exit();
}

// Fetch user data including profile image and college
$stmt = $pdo->prepare("
    SELECT u.*, up.college_name, up.profile_image 
    FROM users u 
    LEFT JOIN user_profiles up ON u.id = up.user_id 
    WHERE u.id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch user's skills
$stmt = $pdo->prepare("SELECT skill_name FROM skills WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$skills = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch social links
$stmt = $pdo->prepare("SELECT platform, url FROM social_links WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$socialLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create an associative array for easy access to social links
$socialUrls = [];
foreach ($socialLinks as $link) {
    $socialUrls[strtolower($link['platform'])] = $link['url'];
}

// Fetch user's projects
$stmt = $pdo->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Hub</title>
    <link rel="stylesheet" href="UD_styles.css">
    <link rel="stylesheet" href="modal_styles.css">
    <link rel="stylesheet" href="project_styles.css">
</head>
<body>
    <header class="top-nav">
        <div class="nav-left">
            <div class="logo">Elevate Ed</div>
            <nav class="main-nav">
                <a href="#" class="nav-link active">Learn</a>
                <a href="#" class="nav-link">Compete</a>
                <a href="#" class="nav-link">Support</a>
            </nav>
        </div>
        <div class="nav-right">
            <div class="user-menu">
            <img src="default_avatar.jpg" alt="Profile" class="user-avatar" width="40px" height="40px">
                <div class="dropdown-menu">
                    <a href="view_profile.php">Edit Profile</a>
                    <a href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <nav class="side-nav">
                <a href="#" class="nav-item active">
                    <span class="nav-icon">🏠</span>
                    <span>Dashboard</span>
                </a>
                <div class="nav-item-wrapper">
                    <a href="#" class="nav-item">
                        <span class="nav-icon">📚</span>
                        <span>Learning Path</span>
                        <span class="submenu-arrow">▾</span>
                    </a>
                    <div class="submenu">
                        <a href="http://128.24.121.207:6550/" class="submenu-item">
                            <span class="nav-icon">📝</span>
                            <span>Summarizer</span>
                        </a>
                        <a href="landing_page.php" class="submenu-item" id="progress-work">
                            <span class="nav-icon">📊</span>
                            <span>Progress Work</span>
                        </a>
                    </div>
                </div>
                <a href="#" class="nav-item">
                    <span class="nav-icon">📝</span>
                    <span>Resume Builder</span>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="header-section">
                <h1 class="page-title">Dashboard</h1>
                <div class="header-actions">
                    <button class="action-button" id="openProjectModal">
                        <span class="button-icon">+</span>
                        New Project
                    </button>
                </div>
            </div>

            <div class="content-grid">
                <div class="profile-card glass-card">
                    <div class="profile-header">
                        <!-- <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80" alt="Profile Picture" class="profile-image">
                         -->
                        <img src="default_avatar.jpg" alt="Profile" class="user-avatar" width="60px" height="60px">
                        <div class="profile-info">
                            <!-- <h2 id="full-name"><?php echo htmlspecialchars($fullName); ?></h2> -->
                            <h2 id="full-name"><?php echo htmlspecialchars($user['full_name']); ?></h2>
                            <p class="username" id="user-name">@<?php echo htmlspecialchars($user['username']); ?></p>
                            <p class="institution"><?php echo htmlspecialchars($user['college_name'] ?? ''); ?></p>
                        </div>
                    </div>
                    

                    <div class="skills">
                        <?php foreach ($skills as $skill): ?>
                            <span class="skill <?php echo strtolower($skill); ?>"><?php echo htmlspecialchars($skill); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <button class="view-profile" onclick="window.location.href='view_profile.php'">View Full Profile</button>

                    <div class="social-links">
                        <?php if (!empty($socialUrls['linkedin'])): ?>
                        <a href="<?php echo htmlspecialchars($socialUrls['linkedin']); ?>" class="social-link" target="_blank">
                            <span class="social-icon">in</span>
                            LinkedIn Profile
                            <span class="arrow">→</span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($socialUrls['github'])): ?>
                        <a href="<?php echo htmlspecialchars($socialUrls['github']); ?>" class="social-link" target="_blank">
                            <span class="social-icon">gh</span>
                            GitHub Profile
                            <span class="arrow">→</span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($socialUrls['leetcode'])): ?>
                        <a href="<?php echo htmlspecialchars($socialUrls['leetcode']); ?>" class="social-link" target="_blank">
                            <span class="social-icon">lc</span>
                            LeetCode Profile
                            <span class="arrow">→</span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($socialUrls['geeksforgeeks'])): ?>
                        <a href="<?php echo htmlspecialchars($socialUrls['geeksforgeeks']); ?>" class="social-link" target="_blank">
                            <span class="social-icon">gfg</span>
                            GeeksforGeeks Profile
                            <span class="arrow">→</span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($socialUrls['codeforces'])): ?>
                        <a href="<?php echo htmlspecialchars($socialUrls['codeforces']); ?>" class="social-link" target="_blank">
                            <span class="social-icon">cf</span>
                            CodeForces Profile
                            <span class="arrow">→</span>
                        </a>
                        <?php endif; ?>
                    </div>

                    <div class="contribution-dots">
                        <div class="dot" data-level="4"></div>
                        <div class="dot" data-level="2"></div>
                        <div class="dot" data-level="5"></div>
                        <div class="dot" data-level="3"></div>
                        <div class="dot" data-level="1"></div>
                        <div class="dot" data-level="4"></div>
                        <div class="dot" data-level="5"></div>
                    </div>
                </div>

                <div class="goals-card glass-card">
                    <div class="card-header">
                        <span class="card-icon">🎯</span>
                        <h3>Current Goals</h3>
                    </div>
                    <div class="progress-container">
                        <div class="progress-ring">
                            <svg class="progress-ring__circle" width="120" height="120">
                                <circle class="progress-ring__circle-bg" cx="60" cy="60" r="54"/>
                                <circle class="progress-ring__circle-progress" cx="60" cy="60" r="54"/>
                            </svg>
                            <span class="progress-text">70%</span>
                        </div>
                    </div>
                    <p class="goal-text">Complete React Advanced Course</p>
                </div>

                <div class="activity-card glass-card">
                    <h3>Recent Activity</h3>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-status completed">
                                <span class="status-icon">✓</span>
                            </div>
                            <div class="activity-content">
                                <p>Completed JavaScript Basics</p>
                                <span class="activity-time">2 hours ago</span>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-status started">
                                <span class="status-icon">→</span>
                            </div>
                            <div class="activity-content">
                                <p>Started React Course</p>
                                <span class="activity-time">1 day ago</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="contributions-section glass-card">
                <h3>Yearly Contributions</h3>
                <div class="contribution-graph">
                    <div class="graph-bar" style="--height: 30%"></div>
                    <div class="graph-bar" style="--height: 45%"></div>
                    <div class="graph-bar" style="--height: 25%"></div>
                    <div class="graph-bar" style="--height: 60%"></div>
                    <div class="graph-bar" style="--height: 35%"></div>
                    <div class="graph-bar" style="--height: 70%"></div>
                    <div class="graph-bar" style="--height: 40%"></div>
                    <div class="graph-bar" style="--height: 55%"></div>
                    <div class="graph-bar" style="--height: 30%"></div>
                    <div class="graph-bar" style="--height: 65%"></div>
                    <div class="graph-bar" style="--height: 45%"></div>
                    <div class="graph-bar" style="--height: 80%"></div>
                </div>
            </section>

            <!-- Projects Section -->
            <section class="projects-section glass-card">
                <h2 class="section-title">My Projects</h2>
                
                <?php if (empty($projects)): ?>
                <div class="no-projects">
                    <p>You haven't added any projects yet. Click the "New Project" button to get started!</p>
                </div>
                <?php else: ?>
                <div class="projects-grid">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card">
                            <div class="project-image">
                                <?php if (!empty($project['image_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($project['image_path']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                                <?php else: ?>
                                    <div class="no-image">No Image</div>
                                <?php endif; ?>
                            </div>
                            <div class="project-content">
                                <h3 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h3>
                                <div class="project-status <?php echo strtolower(str_replace(' ', '-', $project['status'])); ?>">
                                    <?php echo htmlspecialchars($project['status']); ?>
                                </div>
                                <p class="project-description">
                                    <?php echo htmlspecialchars(substr($project['description'], 0, 100)) . (strlen($project['description']) > 100 ? '...' : ''); ?>
                                </p>
                                <div class="project-tech">
                                    <?php 
                                    $techs = explode(',', $project['technology_stack']);
                                    foreach ($techs as $tech): 
                                        $tech = trim($tech);
                                        if (!empty($tech)):
                                    ?>
                                    <span class="tech-tag"><?php echo htmlspecialchars($tech); ?></span>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>
                                <div class="project-links">
                                    <?php if (!empty($project['github_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" class="project-link">
                                            <span class="link-icon">gh</span> GitHub
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($project['deployed_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($project['deployed_link']); ?>" target="_blank" class="project-link">
                                            <span class="link-icon">🌐</span> Live Demo
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <!-- Delete button -->
                                <form action="delete_project.php" method="post" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                    <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                                    <button type="submit" class="delete-button">Delete</button>
                                </form>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <!-- Project Modal -->
    <div id="projectModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close-modal">&times;</span>
                <h2>Add New Project</h2>
            </div>
            <form id="projectForm" action="add_project.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="project_title">Project Title *</label>
                    <input type="text" id="project_title" name="project_title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="project_description">Project Description *</label>
                    <textarea id="project_description" name="project_description" class="form-control" rows="4" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="technology_stack">Technology Stack *</label>
                    <select id="technology_stack" name="technology_stack[]" class="form-control multi-select" multiple required>
                        <option value="HTML">HTML</option>
                        <option value="CSS">CSS</option>
                        <option value="JavaScript">JavaScript</option>
                        <option value="TypeScript">TypeScript</option>
                        <option value="React">React</option>
                        <option value="Angular">Angular</option>
                        <option value="Vue">Vue</option>
                        <option value="Node.js">Node.js</option>
                        <option value="Express">Express</option>
                        <option value="MongoDB">MongoDB</option>
                        <option value="MySQL">MySQL</option>
                        <option value="PostgreSQL">PostgreSQL</option>
                        <option value="PHP">PHP</option>
                        <option value="Laravel">Laravel</option>
                        <option value="Python">Python</option>
                        <option value="Django">Django</option>
                        <option value="Flask">Flask</option>
                        <option value="Java">Java</option>
                        <option value="Spring">Spring</option>
                        <option value="C#">C#</option>
                        <option value=".NET">.NET</option>
                        <option value="Ruby">Ruby</option>
                        <option value="Ruby on Rails">Ruby on Rails</option>
                        <option value="AWS">AWS</option>
                        <option value="Azure">Azure</option>
                        <option value="Google Cloud">Google Cloud</option>
                        <option value="Docker">Docker</option>
                        <option value="Kubernetes">Kubernetes</option>
                        <option value="GraphQL">GraphQL</option>
                        <option value="REST API">REST API</option>
                    </select>
                    <small>Hold Ctrl/Cmd to select multiple technologies</small>
                </div>
                
                <div class="form-group">
                    <label for="deployed_link">Deployed Link</label>
                    <input type="url" id="deployed_link" name="deployed_link" class="form-control" placeholder="https://your-project.com">
                </div>
                
                <div class="form-group">
                    <label for="github_link">GitHub Repository Link</label>
                    <input type="url" id="github_link" name="github_link" class="form-control" placeholder="https://github.com/username/repo">
                </div>
                
                <div class="form-group">
                    <label for="project_category">Project Category *</label>
                    <select id="project_category" name="project_category" class="form-control" required>
                        <option value="">Select a category</option>
                        <option value="Web Development">Web Development</option>
                        <option value="Mobile App">Mobile App</option>
                        <option value="Desktop Application">Desktop Application</option>
                        <option value="Machine Learning">Machine Learning</option>
                        <option value="Data Science">Data Science</option>
                        <option value="Game Development">Game Development</option>
                        <option value="IoT">IoT</option>
                        <option value="Blockchain">Blockchain</option>
                        <option value="DevOps">DevOps</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Project Status *</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="status_in_progress" name="project_status" value="In Progress" required>
                            <label for="status_in_progress">In Progress</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="status_completed" name="project_status" value="Completed">
                            <label for="status_completed">Completed</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="project_image">Project Image</label>
                    <div class="image-upload">
                        <label for="project_image" class="upload-btn">Choose File</label>
                        <input type="file" id="project_image" name="project_image" accept="image/*" style="display: none;">
                        <div id="imagePreview" class="image-preview"></div>
                    </div>
                </div>
                
                <div class="collaborator-section">
                    <h3>Collaborators</h3>
                    <div id="collaboratorsContainer">
                        <!-- Collaborator fields will be added here -->
                    </div>
                    <button type="button" id="addCollaborator" class="btn btn-secondary">Add Collaborator</button>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Project</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Fallback JavaScript to ensure user data is displayed
    window.onload = function() {
        // Check if we have data in sessionStorage (from signin.php)
        const storedUsername = sessionStorage.getItem('username');
        const storedFullName = sessionStorage.getItem('full_name');
        
        if (storedUsername && storedFullName) {
            document.getElementById('user-name').textContent = '@' + storedUsername;
            document.getElementById('full-name').textContent = storedFullName;
        }
        
        // Check for success message
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
            alert('Project added successfully!');
            // Remove the success parameter from the URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }
    </script>
    <script src="project_modal.js"></script>
</body>
</html>