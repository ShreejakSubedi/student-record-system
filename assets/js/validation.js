/**
 * Form Validation Functions
 * Client-side validation for form inputs
 */

/**
 * Validate email format
 */
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Validate phone format
 */
function validatePhone(phone) {
    const phoneRegex = /^[0-9\-\+\s]{10,}$/;
    return phoneRegex.test(phone);
}

/**
 * Validate roll number (alphanumeric)
 */
function validateRollNumber(rollNumber) {
    const rollRegex = /^[A-Z0-9]+$/i;
    return rollRegex.test(rollNumber) && rollNumber.length >= 3;
}

/**
 * Validate marks
 */
function validateMarks(marks, totalMarks = 100) {
    const num = parseFloat(marks);
    return !isNaN(num) && num >= 0 && num <= totalMarks;
}

/**
 * Validate date format YYYY-MM-DD
 */
function validateDate(date) {
    const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
    if (!dateRegex.test(date)) return false;
    const d = new Date(date);
    return d instanceof Date && !isNaN(d);
}

/**
 * Add real-time validation to form input
 */
function addRealTimeValidation(inputId, validationFunction, errorElementId) {
    const input = document.getElementById(inputId);
    const errorElement = document.getElementById(errorElementId);
    
    if (!input || !errorElement) return;
    
    input.addEventListener('blur', function() {
        if (this.value) {
            if (!validationFunction(this.value)) {
                this.classList.add('error');
                errorElement.style.display = 'block';
            } else {
                this.classList.remove('error');
                errorElement.style.display = 'none';
            }
        }
    });
    
    input.addEventListener('focus', function() {
        if (this.classList.contains('error')) {
            errorElement.style.display = 'block';
        }
    });
}

/**
 * Validate entire form
 */
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const formData = new FormData(form);
    const errors = [];
    
    // Check required fields
    for (let [key, value] of formData.entries()) {
        if (!value && form.elements[key].required) {
            errors.push(`${key} is required`);
        }
    }
    
    if (errors.length > 0) {
        const errorContainer = form.querySelector('.error-container');
        if (errorContainer) {
            errorContainer.innerHTML = errors.map(e => `<p>${e}</p>`).join('');
            errorContainer.style.display = 'block';
        }
        return false;
    }
    
    return true;
}

/**
 * Initialize form validations
 */
function initializeFormValidation() {
    // Add email validation
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !validateEmail(this.value)) {
                this.classList.add('error');
                const errorEl = document.getElementById(this.id + '_error');
                if (errorEl) errorEl.textContent = 'Invalid email format';
            }
        });
    });
    
    // Add phone validation
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !validatePhone(this.value)) {
                this.classList.add('error');
                const errorEl = document.getElementById(this.id + '_error');
                if (errorEl) errorEl.textContent = 'Invalid phone format';
            }
        });
    });
    
    // Add date validation
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value && !validateDate(this.value)) {
                this.classList.add('error');
                const errorEl = document.getElementById(this.id + '_error');
                if (errorEl) errorEl.textContent = 'Invalid date format';
            }
        });
    });
}

/**
 * Real-time percentage calculation
 */
function calculatePercentageRealTime(marksId, totalMarksId, percentageId) {
    const marksInput = document.getElementById(marksId);
    const totalInput = document.getElementById(totalMarksId);
    const percentageInput = document.getElementById(percentageId);
    
    if (!marksInput || !totalInput || !percentageInput) return;
    
    const calculate = () => {
        const marks = parseFloat(marksInput.value) || 0;
        const total = parseFloat(totalInput.value) || 100;
        if (total > 0) {
            const percentage = (marks / total) * 100;
            percentageInput.value = percentage.toFixed(2);
        }
    };
    
    marksInput.addEventListener('input', calculate);
    totalInput.addEventListener('input', calculate);
}

/**
 * Initialize on document ready
 */
document.addEventListener('DOMContentLoaded', function() {
    initializeFormValidation();
});
