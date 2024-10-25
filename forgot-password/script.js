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
