// auth.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.auth-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get all form inputs
            const inputs = form.querySelectorAll('input, select');
            let isValid = true;
            let formData = {};
            
            // Validate each input
            inputs.forEach(input => {
                if (input.value.trim() === '') {
                    isValid = false;
                    showError(input, 'This field is required');
                } else {
                    clearError(input);
                    formData[input.name] = input.value;
                }
                
                // Email validation
                if (input.type === 'email' && !validateEmail(input.value)) {
                    isValid = false;
                    showError(input, 'Please enter a valid email address');
                }
                
                // Password validation
                if (input.type === 'password' && input.value.length < 6) {
                    isValid = false;
                    showError(input, 'Password must be at least 6 characters');
                }
            });
            
            if (isValid) {
                // Here you would typically send the data to your server
                console.log('Form submitted:', formData);
                
                // Show success message
                showMessage('Success! Redirecting...', 'success');
                
                // Simulate redirect
                setTimeout(() => {
                    window.location.href = '/dashboard.html';
                }, 2000);
            }
        });
    }
});

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function showError(input, message) {
    const formGroup = input.closest('.form-group');
    let errorDiv = formGroup.querySelector('.error-message');
    
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        formGroup.appendChild(errorDiv);
    }
    
    errorDiv.textContent = message;
    input.classList.add('error');
}

function clearError(input) {
    const formGroup = input.closest('.form-group');
    const errorDiv = formGroup.querySelector('.error-message');
    
    if (errorDiv) {
        errorDiv.remove();
    }
    input.classList.remove('error');
}

function showMessage(message, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;
    
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

// Add these styles to your CSS
const styles = `
.error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

input.error {
    border-color: #dc2626;
}

.message {
    position: fixed;
    top: 1rem;
    right: 1rem;
    padding: 1rem 2rem;
    border-radius: 0.5rem;
    color: white;
    animation: slideIn 0.3s ease;
}

.message.success {
    background: #10b981;
}

.message.error {
    background: #dc2626;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
`;

// Add the styles to the document
const styleSheet = document.createElement('style');
styleSheet.textContent = styles;
document.head.appendChild(styleSheet);
