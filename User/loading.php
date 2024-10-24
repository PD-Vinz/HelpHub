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

/*--------------------------------------------------------------------------------------*/

/* From Uiverse.io by cosnametv */ 
.loader {
  --color: #800000;
  --size: 70px;
  width: var(--size);
  height: var(--size);
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 5px;
}

.loader span {
  width: 100%;
  height: 100%;
  background-color: var(--color);
  animation: keyframes-blink 0.6s alternate infinite linear;
}

.loader span:nth-child(1) {
  animation-delay: 0ms;
}

.loader span:nth-child(2) {
  animation-delay: 200ms;
}

.loader span:nth-child(3) {
  animation-delay: 300ms;
}

.loader span:nth-child(4) {
  animation-delay: 400ms;
}

.loader span:nth-child(5) {
  animation-delay: 500ms;
}

.loader span:nth-child(6) {
  animation-delay: 600ms;
}

@keyframes keyframes-blink {
  0% {
    opacity: 0.3;
    transform: scale(0.5) rotate(5deg);
  }

  50% {
    opacity: 1;
    transform: scale(1);
  }
}

/*--------------------------------------------------------------------------------------*/

</style>
<div id="loading-screen">
        <!--
        <div class="spinner"></div>
        <p>Loading...</p>
-->

<div class="loader">
  <span></span>
  <span></span>
  <span></span>
  <span></span>
  <span></span>
  <span></span>
</div>

</div>
<!-- Loading Screen -->    
<script>
document.addEventListener('DOMContentLoaded', () => {
    const loadingScreen = document.getElementById('loading-screen');
    const content = document.getElementById('content'); // Assuming you have a content div

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

// Handle back button or cached page load
window.addEventListener('pageshow', function(event) {
    const loadingScreen = document.getElementById('loading-screen');
    const content = document.getElementById('content'); // Assuming you have a content div
    if (event.persisted) {
        // Page is loaded from cache (e.g., using the back button), so remove the loading screen
        loadingScreen.classList.add('fade-out');
        setTimeout(() => {
        loadingScreen.style.display = 'none';
        content.style.display = 'block';
        }, 500); // Matches the fade-out duration
    }
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


<!--
Additional Loading CSS

/* From Uiverse.io by satyamchaudharydev */ 
.spinner {
 --size: 30px;
 --first-block-clr: #005bba;
 --second-block-clr: #fed500;
 --clr: #111;
 width: 100px;
 height: 100px;
 position: relative;
}

.spinner::after,.spinner::before {
 box-sizing: border-box;
 position: absolute;
 content: "";
 width: var(--size);
 height: var(--size);
 top: 50%;
 animation: up 2.4s cubic-bezier(0, 0, 0.24, 1.21) infinite;
 left: 50%;
 background: var(--first-block-clr);
}

.spinner::after {
 background: var(--second-block-clr);
 top: calc(50% - var(--size));
 left: calc(50% - var(--size));
 animation: down 2.4s cubic-bezier(0, 0, 0.24, 1.21) infinite;
}

@keyframes down {
 0%, 100% {
  transform: none;
 }

 25% {
  transform: translateX(100%);
 }

 50% {
  transform: translateX(100%) translateY(100%);
 }

 75% {
  transform: translateY(100%);
 }
}

@keyframes up {
 0%, 100% {
  transform: none;
 }

 25% {
  transform: translateX(-100%);
 }

 50% {
  transform: translateX(-100%) translateY(-100%);
 }

 75% {
  transform: translateY(-100%);
 }
}
-->