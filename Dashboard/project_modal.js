document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const modal = document.getElementById('projectModal');
    const openModalBtn = document.getElementById('openProjectModal');
    const closeModalBtns = document.querySelectorAll('.close-modal');
    const form = document.getElementById('projectForm');
    const imageInput = document.getElementById('project_image');
    const imagePreview = document.getElementById('imagePreview');
    const addCollaboratorBtn = document.getElementById('addCollaborator');
    const collaboratorsContainer = document.getElementById('collaboratorsContainer');
    
    // Open modal
    openModalBtn.addEventListener('click', function() {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling behind modal
    });
    
    // Close modal
    closeModalBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        });
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
    // Image preview
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Project Preview">`;
                imagePreview.classList.remove('empty');
            };
            
            reader.readAsDataURL(file);
        } else {
            imagePreview.innerHTML = '';
            imagePreview.classList.add('empty');
        }
    });
    
    // Initialize image preview class
    if (!imagePreview.innerHTML.trim()) {
        imagePreview.classList.add('empty');
    }
    
    // Add collaborator
    addCollaboratorBtn.addEventListener('click', function() {
        addCollaboratorRow();
    });
    
    // Function to add collaborator row
    function addCollaboratorRow() {
        const row = document.createElement('div');
        row.className = 'collaborator-row';
        row.innerHTML = `
            <input type="text" name="collaborator_name[]" class="form-control" placeholder="Collaborator Name">
            <input type="email" name="collaborator_email[]" class="form-control" placeholder="Collaborator Email">
            <button type="button" class="remove-collaborator">&times;</button>
        `;
        
        // Add remove event listener
        const removeBtn = row.querySelector('.remove-collaborator');
        removeBtn.addEventListener('click', function() {
            row.remove();
        });
        
        collaboratorsContainer.appendChild(row);
    }
    
    // Add initial collaborator row if container is empty
    if (collaboratorsContainer.children.length === 0) {
        addCollaboratorRow();
    }
    
    // Form validation
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // Check required fields
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
            } else {
                field.classList.remove('error');
            }
        });
        
        // Check technology stack (multi-select)
        const techStack = document.getElementById('technology_stack');
        if (techStack.selectedOptions.length === 0) {
            isValid = false;
            techStack.classList.add('error');
        } else {
            techStack.classList.remove('error');
        }
        
        // Validate collaborator emails
        const collaboratorEmails = form.querySelectorAll('input[name="collaborator_email[]"]');
        collaboratorEmails.forEach(email => {
            if (email.value.trim() !== '' && !isValidEmail(email.value)) {
                isValid = false;
                email.classList.add('error');
            } else {
                email.classList.remove('error');
            }
        });
        
        if (!isValid) {
            event.preventDefault();
            alert('Please fill in all required fields correctly.');
        } else {
            // Save form data to localStorage before submitting
            saveFormDataToLocalStorage();
        }
    });
    
    // Email validation helper
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // Function to save form data to localStorage
    function saveFormDataToLocalStorage() {
        const formData = {
            project_title: document.getElementById('project_title').value,
            project_description: document.getElementById('project_description').value,
            technology_stack: Array.from(document.getElementById('technology_stack').selectedOptions).map(option => option.value),
            deployed_link: document.getElementById('deployed_link').value,
            github_link: document.getElementById('github_link').value,
            project_category: document.getElementById('project_category').value,
            project_status: document.querySelector('input[name="project_status"]:checked')?.value || '',
            collaborators: Array.from(document.querySelectorAll('.collaborator-row')).map(row => {
                return {
                    name: row.querySelector('input[name="collaborator_name[]"]').value,
                    email: row.querySelector('input[name="collaborator_email[]"]').value
                };
            })
        };
        
        localStorage.setItem('projectFormData', JSON.stringify(formData));
    }
    
    // Function to load form data from localStorage
    function loadFormDataFromLocalStorage() {
        const savedData = localStorage.getItem('projectFormData');
        
        if (savedData) {
            try {
                const formData = JSON.parse(savedData);
                
                // Populate form fields
                document.getElementById('project_title').value = formData.project_title || '';
                document.getElementById('project_description').value = formData.project_description || '';
                
                // Set technology stack
                const techStackSelect = document.getElementById('technology_stack');
                if (formData.technology_stack && Array.isArray(formData.technology_stack)) {
                    Array.from(techStackSelect.options).forEach(option => {
                        option.selected = formData.technology_stack.includes(option.value);
                    });
                }
                
                document.getElementById('deployed_link').value = formData.deployed_link || '';
                document.getElementById('github_link').value = formData.github_link || '';
                
                // Set project category
                const categorySelect = document.getElementById('project_category');
                if (formData.project_category) {
                    Array.from(categorySelect.options).forEach(option => {
                        option.selected = option.value === formData.project_category;
                    });
                }
                
                // Set project status
                if (formData.project_status) {
                    const statusRadio = document.querySelector(`input[name="project_status"][value="${formData.project_status}"]`);
                    if (statusRadio) {
                        statusRadio.checked = true;
                    }
                }
                
                // Set collaborators
                if (formData.collaborators && Array.isArray(formData.collaborators)) {
                    // Clear existing collaborator rows
                    collaboratorsContainer.innerHTML = '';
                    
                    // Add saved collaborator rows
                    formData.collaborators.forEach(collaborator => {
                        const row = document.createElement('div');
                        row.className = 'collaborator-row';
                        row.innerHTML = `
                            <input type="text" name="collaborator_name[]" class="form-control" placeholder="Collaborator Name" value="${collaborator.name || ''}">
                            <input type="email" name="collaborator_email[]" class="form-control" placeholder="Collaborator Email" value="${collaborator.email || ''}">
                            <button type="button" class="remove-collaborator">&times;</button>
                        `;
                        
                        // Add remove event listener
                        const removeBtn = row.querySelector('.remove-collaborator');
                        removeBtn.addEventListener('click', function() {
                            row.remove();
                        });
                        
                        collaboratorsContainer.appendChild(row);
                    });
                }
                
                // If no collaborator rows were added, add an empty one
                if (collaboratorsContainer.children.length === 0) {
                    addCollaboratorRow();
                }
                
                console.log('Form data loaded from localStorage');
            } catch (error) {
                console.error('Error loading form data:', error);
                // If there's an error, ensure at least one collaborator row exists
                if (collaboratorsContainer.children.length === 0) {
                    addCollaboratorRow();
                }
            }
        } else {
            // If no saved data, ensure at least one collaborator row exists
            if (collaboratorsContainer.children.length === 0) {
                addCollaboratorRow();
            }
        }
    }
    
    // Load saved form data when opening the modal
    openModalBtn.addEventListener('click', function() {
        loadFormDataFromLocalStorage();
    });
    
    // Add CSS for validation
    const style = document.createElement('style');
    style.textContent = `
        .form-control.error {
            border-color: #ff4d4f;
            background-color: #fff2f0;
        }
        
        .form-control.error:focus {
            box-shadow: 0 0 0 3px rgba(255, 77, 79, 0.1);
        }
    `;
    document.head.appendChild(style);
    
    // Check for error message in URL
    const urlParams = new URLSearchParams(window.location.search);
    const errorMessage = urlParams.get('error');
    
    if (errorMessage) {
        alert('Error: ' + errorMessage);
        // Remove the error parameter from the URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});