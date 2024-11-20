<style>
/* Notification overlay to darken background */
.notification-overlay {
  display: none; /* Hidden by default */
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
  z-index: 10000;
  opacity: 0; /* Start with 0 opacity for fade-in effect */
  transition: opacity 0.5s ease; /* Transition for fade-in effect */
}

.confirm-notification {
  display: none; /* Hidden by default */
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #942121;
  color: white;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
  max-width: 80%;
  width: 400px;
  z-index: 10001;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  border: 2px solid #FFD700;
  opacity: 0; /* Start with 0 opacity */
  transition: opacity 0.5s ease; /* Transition for fade-in effect */
}

.notification-overlay.fade-in,
.confirm-notification.fade-in {
  opacity: 1; /* Set to fully visible */
}

.confirm-notification p {
  margin-bottom: 20px;
}

.confirm-btn, .cancel-btn {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  margin: 5px;
  transition: background-color 0.2s;
}

.confirm-btn {
  background-color: #28a745; /* Green for confirm */
  color: white;
}

.cancel-btn {
  background-color: #dc3545; /* Red for cancel */
  color: white;
}

.confirm-btn:hover {
  background-color: #218838;
}

.cancel-btn:hover {
  background-color: #c82333;
}
</style>

<!-- HTML structure for confirmation notification -->
<div id="notification-overlay" class="notification-overlay"></div>

<div id="confirm-notification" class="confirm-notification">
  <p>Are you sure you want to submit the form?</p>
  <button class="confirm-btn" onclick="confirmSubmission(true)">Yes</button>
  <button class="cancel-btn" onclick="confirmSubmission(false)">No</button>
</div>

<script>
// Function to trigger the custom confirmation notification
function showConfirmNotification() {
  var overlay = document.getElementById('notification-overlay');
  var notification = document.getElementById('confirm-notification');

  // Display overlay and notification
  overlay.style.display = 'block';
  notification.style.display = 'block';

  // Add fade-in effect by applying the class
  setTimeout(function() {
    overlay.classList.add('fade-in');
    notification.classList.add('fade-in');
  }, 10); // Slight delay to allow the transition to take effect
}

function hideConfirmNotification() {
  var overlay = document.getElementById('notification-overlay');
  var notification = document.getElementById('confirm-notification');

  // Remove fade-in class for fade-out effect
  overlay.classList.remove('fade-in');
  notification.classList.remove('fade-in');

  // Hide the notification and overlay after the fade-out transition ends
  setTimeout(function() {
    overlay.style.display = 'none';
    notification.style.display = 'none';
  }, 500); // Match this delay to the CSS transition duration (0.5s)
}


// Confirmation handler for the form submission
function confirmSubmission(userConfirmed) {
  if (userConfirmed) {
    // If user clicked 'Yes', proceed with form submission
    document.getElementById("passnew").submit(); // Replace 'yourFormId' with your actual form ID
  }
  // Hide the notification regardless of the user's response
  hideConfirmNotification();
  return userConfirmed;
}

</script>
