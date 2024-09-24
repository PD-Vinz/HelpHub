// Fade in the page when it loads
window.addEventListener('load', () => {
    document.body.classList.add('fade-in');
  });
  
  // Add fade-out effect when navigating to another page
  document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault(); // Prevent the default link action
      const targetUrl = this.href; // Get the target URL
  
      // Add the fade-out class and navigate after the animation
      document.body.classList.add('fade-out');
  
      setTimeout(() => {
        window.location.href = targetUrl; // Navigate to the target URL
      }, 500); // Adjust this duration to match the fade-out time in CSS
    });
  });