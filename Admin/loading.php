<style>
    /* Styles for the loading screen */
#loading-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.95);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    z-index: 9999;
    opacity: 1;
    transition: opacity 0.5s ease-in-out;
}

    /* Fade-in effect for loading screen */
    #loading-screen.fade-in {
        opacity: 1;
    }

    /* Fade-out effect after the page is fully loaded */
    #loading-screen.fade-out {
        opacity: 0;
        pointer-events: none;
    }

.spinner {
    border: 16px solid #FFD700; /* Light grey */
    border-top: 16px solid #800000; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}

/* Animation for the spinner */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>
<div id="loading-screen">
        <div class="spinner"></div>
        <p>Loading...</p>
</div>
<!-- Loading Screen -->    
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loadingScreen = document.getElementById('loading-screen');
    // Simulate data fetching
    fetchData().then(() => {
        // Fade out the loading screen once the data is ready
        loadingScreen.classList.add('fade-out');
        setTimeout(() => {
            loadingScreen.style.display = 'none'; // Hide the loading screen
            content.style.display = 'block';      // Show the content
        }, 500); // Matches the fade-out duration
    });


    // Show the loading screen with a fade-in effect when the page reloads or navigates away
    window.addEventListener('beforeunload', () => {
        loadingScreen.style.display = 'flex'; // Ensure it's visible
        loadingScreen.classList.remove('fade-out'); // Remove fade-out if present
        loadingScreen.classList.add('fade-in'); // Apply fade-in effect
    });
});

// Handle redirection with fade-in effect
document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default link action
        const targetUrl = this.href; // Get the target URL

        // Add the fade-in effect for redirection
        document.body.classList.add('fade-in');
        setTimeout(() => {
            window.location.href = targetUrl; // Redirect to the new page
        }, 500); // Adjust time to match fade-in animation
    });
});

function fetchData() {
    return new Promise((resolve) => {
        // Simulate a delay for data fetching (e.g., 2 seconds)
        setTimeout(() => {
            resolve();
        }, 500);
    });
}

</script>