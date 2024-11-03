// Fade in the page when it loads
window.addEventListener('load', () => {
    document.body.classList.add('fade-in');
  });
  
  // Add fade-out effect when navigating to another page
  // For both <a> tags and form submissions
document.querySelectorAll('a').forEach(element => {
  element.addEventListener('click', function (e) {
    e.preventDefault(); // Prevent default action (link navigation or form submission)

    let targetUrl;

    // Check if it's a form, handle form submission
    if (this.tagName.toLowerCase() === 'form') {
      targetUrl = this.action; // Use form's action URL
    } 
    // Handle link clicks (for <a> tags)
    else if (this.tagName.toLowerCase() === 'a') {
      targetUrl = this.href; // Use anchor's href
    }

    // Optional: Further customization for input or button elements
    else if (this.tagName.toLowerCase() === 'input' || this.tagName.toLowerCase() === 'button') {
      const form = this.closest('form');
      targetUrl = form ? form.action : '#'; // Use form action if found, otherwise do nothing
    }

    // Add fade-out animation and navigate after a delay
    document.body.classList.add('fade-out');

    setTimeout(() => {
      window.location.href = targetUrl; // Navigate to the target URL
    }, 500); // Adjust this duration to match the fade-out time in CSS
  });
});


const inputs = {
    firstName: {
        input: document.getElementById('firstNameInput'),
        error: document.getElementById('firstNameError'),
        required: true
    },
    lastName: {
        input: document.getElementById('lastNameInput'),
        error: document.getElementById('lastNameError'),
        required: true
    },
    middleName: {
        input: document.getElementById('middleNameInput'),
        error: document.getElementById('middleNameError'),
        required: false
    },
    middleInitial: {
        input: document.getElementById('middleInitialInput'),
        error: document.getElementById('middleInitialError'),
        required: false
    },
    extName: {
        input: document.getElementById('extNameInput'),
        error: document.getElementById('extNameError'),
        required: false
    }
};

const nameRegex = /^[a-zA-ZÀ-ÿ\s'.-]+$/;
const maxLength = 100;

// Function to validate a single field
function validateField(field) {
    const value = field.input.value.trim();
    
    // Only check for required fields
    if (field.required && value === "") {
        field.error.textContent = 'This field is required.';
        return false;
    }

    if (value && !nameRegex.test(value)) {
        field.error.textContent = 'Please enter a valid value (letters, spaces, hyphens, apostrophes, and periods only).';
        return false;
    } else if (value.length > maxLength) {
        field.error.textContent = `This field must be ${maxLength} characters or fewer.`;
        return false;
    } else {
        field.error.textContent = ''; // Clear error if valid
        return true;
    }
}

// Main function to validate all fields before form submission
function validateForm() {
    let isValid = true;

    for (const key in inputs) {
        if (!validateField(inputs[key])) {
            isValid = false;
        }
    }

    return isValid ? confirmSubmit() : false; // Show confirmation dialog if all fields are valid
}

// Function to confirm submission
function confirmSubmit() {
    return confirm("Please make sure that the data you are submitting is true. Are you sure you want to proceed?");
}

// Attach live validation feedback to each input
for (const key in inputs) {
    inputs[key].input.addEventListener('input', () => validateField(inputs[key]));
}
