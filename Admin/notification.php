<?php
try {
    // Check if any result is found
    if (isset($_SESSION['update_success'])) {
      $notification = "Profile Information Updated Successfully"; // Fetch the notification message
      unset($_SESSION['update_success']);
    } elseif (isset($_SESSION['Employee_Add_Success'])) {
      $notification = "Employee Account Added Successfully"; // Fetch the notification message
      unset($_SESSION['Employee_Add_Success']);
    } else {
      $notification = ''; // No notification found
  }
    
    
    
} catch (PDOException $e) {
    // Handle connection errors or query issues
    echo "Error: " . $e->getMessage();
}

?>

<!-- Pass PHP variable to JavaScript using JSON -->
<script type="text/javascript">
    var notificationMessage = <?php echo json_encode($notification); ?>;
</script>


<style>
.notification {
  display: none; /* Initially hidden */
  position: fixed;
  top: -100px; /* Start off the screen */
  left: 50%;
  transform: translateX(-50%);
  background-color: #942121; /* A modern blue color */
  color: white;
  padding: 15px 20px; /* More padding for a cleaner look */
  border-radius: 8px; /* Slightly rounded corners */
  z-index: 9999;
  max-width: 80%; /* Allow it to take up to 80% of the viewport width */
  width: auto; /* Let it adjust based on content */
  text-align: center;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Add a subtle shadow for depth */
  border: 2px solid #FFD700; /* Gold outline */
  transition: top 0.5s ease-in-out; /* Smooth slide down/up */
}

.notification.show {
  top: 20px; /* Slide into view when shown */
}

.close-btn {
  position: absolute;
  top: 5px;
  right: 10px;
  font-size: 20px;
  cursor: pointer;
  color: white; /* Ensure the close button is visible */
  transition: color 0.2s; /* Smooth transition for hover effect */
}

.close-btn:hover {
  color: #FFC107; /* Change color on hover for better visibility */
}

</style>

<div id="notification" class="notification">
  <span id="close-btn" class="close-btn">&times;</span>
  <p id="notification-message"><?php echo $notification ?></p>
</div>


<script>
window.onload = function() {
  // Check if there is a notification message from PHP
  if (notificationMessage && notificationMessage.trim() !== '') {
    document.getElementById('notification-message').innerText = notificationMessage; // Add the message to the notification
    showNotification(); // Show the notification
  }
};

function showNotification() {
  var notification = document.getElementById('notification');
  
  // Show the notification
  notification.style.display = 'block'; // Make it visible
  setTimeout(function() {
    notification.classList.add('show'); // Slide down
  }, 10); // Small delay to allow the transition to work
  
  // Calculate the display duration based on the message length
  var messageLength = notificationMessage.length;
  var duration = messageLength * 100; // Display for 100ms per character
  
  // Set a minimum and maximum duration
  var minDuration = 3000;  // 3 seconds minimum
  var maxDuration = 15000; // 15 seconds maximum
  duration = Math.max(minDuration, Math.min(duration, maxDuration));
  
  // Automatically hide after the calculated duration
  setTimeout(function() {
    hideNotification();
  }, duration);
  
  // Close button functionality
  document.getElementById('close-btn').onclick = function() {
    hideNotification();
  };
}

function hideNotification() {
  var notification = document.getElementById('notification');
  
  // Slide up by removing the 'show' class
  notification.classList.remove('show');
  
  // After the slide-up animation ends (500ms), hide the notification
  setTimeout(function() {
    notification.style.display = 'none';
  }, 500); // Match this to the CSS transition duration (0.5s)
}



</script>