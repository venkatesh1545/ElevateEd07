document.addEventListener('DOMContentLoaded', function() {
    // Profile image preview
    const profileImage = document.getElementById('profileImage');
    const profilePreview = document.getElementById('profilePreview');

    profileImage.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Skills management
    const skillInput = document.getElementById('skillInput');
    const addSkillBtn = document.getElementById('addSkill');
    const skillsList = document.getElementById('skillsList');

    function addSkill(skillName) {
        const skill = document.createElement('span');
        skill.className = 'skill';
        skill.innerHTML = `
            ${skillName}
            <button type="button" class="remove-skill">×</button>
            <input type="hidden" name="skills[]" value="${skillName}">
        `;
        skillsList.appendChild(skill);

        skill.querySelector('.remove-skill').addEventListener('click', function() {
            skill.remove();
        });
    }

    addSkillBtn.addEventListener('click', function() {
        const skillName = skillInput.value.trim();
        if (skillName) {
            addSkill(skillName);
            skillInput.value = '';
        }
    });

    skillInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const skillName = this.value.trim();
            if (skillName) {
                addSkill(skillName);
                this.value = '';
            }
        }
    });

    // Remove existing skills
    document.querySelectorAll('.remove-skill').forEach(button => {
        button.addEventListener('click', function() {
            this.parentElement.remove();
        });
    });

    // Form validation
    const profileForm = document.getElementById('profileForm');
    profileForm.addEventListener('submit', function(e) {
        const username = document.getElementById('username').value.trim();
        const fullName = document.getElementById('fullName').value.trim();

        if (!username || !fullName) {
            e.preventDefault();
            alert('Username and Full Name are required!');
        }
    });
});