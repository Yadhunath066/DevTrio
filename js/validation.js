// ============================================
// FORM VALIDATION - Student Course Hub
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    
    // Validate Interest Form
    const interestForm = document.querySelector('.interest-form form');
    
    if (interestForm) {
        interestForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Get form fields
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            
            // Validate Name
            if (!name || !name.value.trim()) {
                showError(name, 'Please enter your full name');
                isValid = false;
            } else if (name.value.trim().length < 2) {
                showError(name, 'Name must be at least 2 characters');
                isValid = false;
            } else {
                clearError(name);
            }
            
            // Validate Email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !email.value.trim()) {
                showError(email, 'Please enter your email address');
                isValid = false;
            } else if (!emailPattern.test(email.value.trim())) {
                showError(email, 'Please enter a valid email address (e.g., name@example.com)');
                isValid = false;
            } else {
                clearError(email);
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = document.querySelector('.error-message');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
    
    // Helper function to show error
    function showError(input, message) {
        if (!input) return;
        
        const formGroup = input.closest('.form-group');
        if (!formGroup) return;
        
        // Remove existing error
        let error = formGroup.querySelector('.error-message');
        if (!error) {
            error = document.createElement('span');
            error.className = 'error-message';
            formGroup.appendChild(error);
        }
        
        error.textContent = message;
        input.style.borderColor = '#e53e3e';
        input.classList.add('error');
    }
    
    // Helper function to clear error
    function clearError(input) {
        if (!input) return;
        
        const formGroup = input.closest('.form-group');
        if (!formGroup) return;
        
        const error = formGroup.querySelector('.error-message');
        if (error) {
            error.remove();
        }
        
        input.style.borderColor = '#e2e8f0';
        input.classList.remove('error');
    }
    
    // Real-time validation as user types
    const nameInput = document.getElementById('name');
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            if (this.value.trim().length >= 2) {
                clearError(this);
            }
        });
    }
    
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailPattern.test(this.value.trim())) {
                clearError(this);
            }
        });
    }
    
    console.log('Validation script loaded successfully');
});