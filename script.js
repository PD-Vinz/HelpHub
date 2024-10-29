
// Fade in the page when it loads
window.addEventListener('load', () => {
    document.body.classList.add('fade-in');
  });

// Show the loading screen with a fade-in effect when the page reloads or navigates away
window.addEventListener('beforeunload', () => {
    document.body.classList.add('fade-out')
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

window.onload = function() {
  document.getElementById('user-login').reset(); // Clear the form fields
};

document.getElementById('login').addEventListener('click', function(event) {
  // Define the required fields to check
  var requiredFields = ['#userid', '#myInput'];

  // Flag to track if all fields are valid
  var allValid = true;

  // Loop through each required field and check if they are filled
  requiredFields.forEach(function(selector) {
      var field = document.querySelector(selector);

      // If any field is empty, set the flag to false and highlight the empty field
      if (!field.value.trim()) {
          allValid = false;
          field.style.borderColor = 'red';  // Highlight the empty field with red border
      } else {
          field.style.borderColor = '';  // Reset the border if filled
      }
  });

  // If all fields are valid, show the modal
  if (allValid) {
    document.getElementById('user-login').submit();
  }
});

['#userid', '#myInput'].forEach(function(selector) {
  var field = document.querySelector(selector);
  field.addEventListener('input', function() {
      if (field.value.trim()) {
          field.style.borderColor = '';  // Remove the red border on valid input
      }
  });
});

// Wait for the document to fully load
document.addEventListener("DOMContentLoaded", function() {
  // Select the error message element
  var errorMessage = document.getElementById('error-message');

  // Check if the error message is present
  if (errorMessage) {
      // Set a timeout to add the fade-out class after 25 seconds
      setTimeout(function() {
          errorMessage.classList.add('fade-out');

          // Remove the element from the DOM after the fade-out transition
          setTimeout(function() {
              errorMessage.style.display = 'none'; // Hide the element
          }, 1000); // Match this duration to the CSS transition time
      }, 25000); // 25 seconds
  }
});