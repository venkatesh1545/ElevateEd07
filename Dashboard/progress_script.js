document.addEventListener('DOMContentLoaded', () => {
    // Initialize Lucide icons
    lucide.createIcons();

    // Get DOM elements
    const skillsList = document.getElementById('skillsList');
    const mainContent = document.getElementById('mainContent');
    const videoModal = document.getElementById('videoModal');
    const articleModal = document.getElementById('articleModal');
    const certModal = document.getElementById('certModal');

    // Close modal functionality
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', () => {
            videoModal.style.display = 'none';
            articleModal.style.display = 'none';
            certModal.style.display = 'none';
            const videoFrame = document.getElementById('videoFrame');
            if (videoFrame) {
                videoFrame.src = '';
            }
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === videoModal) {
            videoModal.style.display = 'none';
            document.getElementById('videoFrame').src = '';
        }
        if (event.target === articleModal) {
            articleModal.style.display = 'none';
        }
        if (event.target === certModal) {
            certModal.style.display = 'none';
        }
    });

    // Render skills list
    function renderSkillsList() {
        skillsList.innerHTML = Object.values(skillsData).map(skill => `
            <div class="skill-item" data-skill-id="${skill.id}">
                <span>${skill.name}</span>
            </div>
        `).join('');

        // Add click handlers for skills
        document.querySelectorAll('.skill-item').forEach(item => {
            item.addEventListener('click', () => {
                document.querySelectorAll('.skill-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                renderSkillContent(item.dataset.skillId);
            });
        });
    }

    // Render skill content
    function renderSkillContent(skillId) {
        const skill = skillsData[skillId];
        if (!skill) return;

        const completedVideos = skill.videos.filter(v => v.completed).length;
        const completedArticles = skill.articles.filter(a => a.completed).length;
        const completedCerts = skill.certifications.filter(c => c.completed).length;

        mainContent.innerHTML = `
            <div class="resource-section">
                <div class="resource-header">
                    <div class="header-content">
                        <h2>${skill.name}</h2>
                        <div class="progress-stats">
                            <div class="progress-bar">
                                <div class="progress" style="width: ${skill.progress}%"></div>
                            </div>
                            <span class="progress-text">${skill.progress}% Complete</span>
                        </div>
                    </div>
                    <p>${skill.description}</p>
                </div>

                <!-- Videos Section -->
                <div class="resource-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i data-lucide="video"></i>
                        </div>
                        <div class="category-info">
                            <h3>Video Courses</h3>
                            <span class="completion-status">${completedVideos}/${skill.videos.length} Completed</span>
                        </div>
                    </div>
                    <div class="resource-items">
                        ${skill.videos.map(video => `
                            <div class="resource-item ${video.completed ? 'completed' : ''}" data-video-url="${video.url}" data-video-title="${video.title}">
                                <div class="resource-content">
                                    <i data-lucide="${video.completed ? 'check-circle' : 'play-circle'}"></i>
                                    <div class="resource-details">
                                        <span class="resource-title">${video.title}</span>
                                        <span class="resource-duration">${video.duration}</span>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>

                <!-- Articles Section -->
                <div class="resource-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i data-lucide="book-open"></i>
                        </div>
                        <div class="category-info">
                            <h3>Articles</h3>
                            <span class="completion-status">${completedArticles}/${skill.articles.length} Completed</span>
                        </div>
                    </div>
                    <div class="resource-items">
                        ${skill.articles.map(article => `
                            <div class="resource-item ${article.completed ? 'completed' : ''}" data-article-content="${article.content}" data-article-title="${article.title}">
                                <div class="resource-content">
                                    <i data-lucide="${article.completed ? 'check-circle' : 'book'}"></i>
                                    <div class="resource-details">
                                        <span class="resource-title">${article.title}</span>
                                        <span class="resource-duration">${article.duration}</span>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>

                <!-- Certifications Section -->
                <div class="resource-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i data-lucide="award"></i>
                        </div>
                        <div class="category-info">
                            <h3>Certifications</h3>
                            <span class="completion-status">${completedCerts}/${skill.certifications.length} Completed</span>
                        </div>
                    </div>
                    <div class="resource-items">
                        ${skill.certifications.map(cert => `
                            <div class="resource-item ${cert.completed ? 'completed' : ''}" 
                                 data-cert-title="${cert.title}"
                                 data-cert-provider="${cert.provider}"
                                 data-cert-duration="${cert.duration}"
                                 data-cert-details="${cert.details}">
                                <div class="resource-content">
                                    <i data-lucide="award"></i>
                                    <div class="resource-details">
                                        <span class="resource-title">${cert.title}</span>
                                        <span class="resource-duration">${cert.duration}</span>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;

        // Reinitialize Lucide icons for new content
        lucide.createIcons();

        // Add click handlers for resources
        addResourceHandlers();
    }

    // Add click handlers for resources
    function addResourceHandlers() {
        // Video handlers
        document.querySelectorAll('[data-video-url]').forEach(item => {
            item.addEventListener('click', () => {
                const videoFrame = document.getElementById('videoFrame');
                const videoTitle = document.getElementById('videoTitle');
                videoFrame.src = item.dataset.videoUrl;
                videoTitle.textContent = item.dataset.videoTitle;
                videoModal.style.display = 'block';
            });
        });

        // Article handlers
        document.querySelectorAll('[data-article-content]').forEach(item => {
            item.addEventListener('click', () => {
                const articleTitle = document.getElementById('articleTitle');
                const articleContent = document.getElementById('articleContent');
                articleTitle.textContent = item.dataset.articleTitle;
                articleContent.textContent = item.dataset.articleContent;
                articleModal.style.display = 'block';
            });
        });

        // Certification handlers
        document.querySelectorAll('[data-cert-title]').forEach(item => {
            item.addEventListener('click', () => {
                const certTitle = document.getElementById('certTitle');
                const certContent = document.getElementById('certContent');
                certTitle.textContent = item.dataset.certTitle;
                certContent.innerHTML = `
                    <div class="cert-details">
                        <p><strong>Provider:</strong> ${item.dataset.certProvider}</p>
                        <p><strong>Duration:</strong> ${item.dataset.certDuration}</p>
                        <p><strong>Details:</strong> ${item.dataset.certDetails}</p>
                    </div>
                `;
                certModal.style.display = 'block';
            });
        });
    }

    // Initialize the app
    renderSkillsList();
    // Show first skill by default
    const firstSkill = Object.keys(skillsData)[0];
    document.querySelector(`[data-skill-id="${firstSkill}"]`).classList.add('active');
    renderSkillContent(firstSkill);
});