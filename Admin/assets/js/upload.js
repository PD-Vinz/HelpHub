    //-----------User Search-------------//
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const suggestions = document.getElementById('suggestions');
    
        function showSuggestions(query) {
            if (query.length >= 4) {
                fetch(`fetch/suggest.php?query=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.text();
                    })
                    .then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Invalid JSON:', text);
                            throw new Error('Invalid JSON response');
                        }
                    })
                    .then(data => {
                        suggestions.innerHTML = '';
                        if (data.success && Array.isArray(data.data) && data.data.length > 0) {
                            suggestions.style.display = 'block';
                            data.data.forEach(item => {
                                const li = document.createElement('li');
                                li.innerHTML = `
                                    <img src="${item.profile_picture || '/placeholder.svg?height=30&width=30'}" alt="Avatar" onerror="this.src='/placeholder.svg?height=30&width=30'">
                                    <div>
                                        <div>${item.first_name}</div>
                                        <div>${item.email_address}</div>
                                    </div>
                                `;
                                li.addEventListener('click', () => {
                                    nameInput.value = item.first_name;
                                    emailInput.value = item.email_address;
                                    hideSuggestions();
                                });
                                suggestions.appendChild(li);
                            });
                        } else if (data.error) {
                            suggestions.style.display = 'block';
                            suggestions.innerHTML = `<li>Error: ${data.error}</li>`;
                        } else {
                            suggestions.style.display = 'block';
                            suggestions.innerHTML = '<li>No results found</li>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        suggestions.style.display = 'block';
                        suggestions.innerHTML = `<li>Error: ${error.message}</li>`;
                    });
            } else {
                hideSuggestions();
            }
        }
    
        function hideSuggestions() {
            suggestions.style.display = 'none';
            suggestions.innerHTML = '';
        }
    
        function handleInput(event) {
            const query = event.target.value.trim();
            if (query.length > 0) {
                showSuggestions(query);
            } else {
                hideSuggestions();
            }
        }
    
        //nameInput.addEventListener('input', handleInput);
        emailInput.addEventListener('input', handleInput);
    
        // Hide suggestions when clicking outside
        document.addEventListener('click', function(event) {
            if (!suggestions.contains(event.target) && event.target !== nameInput && event.target !== emailInput) {
                hideSuggestions();
            }
        });
    });
    
    


    
    // Form submission listener to check consent before submitting
    document.getElementById('issueForm').addEventListener('submit', function(event) {
        const consentYes = document.querySelector('input[name="consent"][value="yes"]');
        const consentNo = document.querySelector('input[name="consent"][value="no"]');

        if (!consentYes.checked) {
            event.preventDefault(); // Prevent form submission if "Yes" is not checked
            alert('You must consent to submit the ticket.');
        }
    });

    function adjustHeight() {
        const textarea = document.getElementById('issue-description');
        textarea.style.height = 'auto'; // Reset height to auto to shrink if needed
        textarea.style.height = textarea.scrollHeight + 'px'; // Adjust height to fit the content
    }

    function updateRemainingCharacters() {
        const textarea = document.getElementById('issue-description');
        const remainingChars = 255 - textarea.value.length;
        document.getElementById('remaining-characters').textContent = `${remainingChars} characters remaining`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('issue-description');
        textarea.addEventListener('input', function() {
            adjustHeight();
            updateRemainingCharacters();
        });

        // Initial adjustment in case there's already content
        adjustHeight();
        updateRemainingCharacters();
    });

    // Function to populate a dropdown from a specified text file
function populateDropdown(fileName, dropdownId) {
    const url = fileName + '?v=' + new Date().getTime();
    // Fetch the text file
    fetch(url)
        .then(response => response.text())
        .then(data => {
            // Split the text data by lines
            const options = data.split('\n');

            // Get the dropdown element
            const dropdown = document.getElementById(dropdownId);

            // Clear existing options in the dropdown
            //dropdown.innerHTML = '';

            // Iterate over each line and create an option element
            options.forEach(option => {
                if (option.trim() !== '') {  // Ignore empty lines
                    const opt = document.createElement('option');
                    opt.value = option.trim();
                    opt.textContent = option.trim();
                    dropdown.appendChild(opt);
                }
            });
        })
        .catch(error => console.error(`Error fetching the text file (${fileName}):`, error));
}

// Now you can make a condition based on the identity value
if (identity === "Employee") {
    populateDropdown('../issue-template/employee-issue.txt', 'category');
} else if (identity === "Student") {
    populateDropdown('../issue-template/student-issue.txt', 'category');
}
// Call the function to populate the dropdowns

// Get elements
const uploadForm = document.getElementsByClassName("upload-area")[0];
const fileInput = document.querySelector(".upload-area input[type='file']");
const uploadArea = fileInput.closest(".upload-area");
const maxFileSize = 6 * 1024 * 1024; // 6MB size limit
const allowedFileTypes = ['image/png', 'image/jpeg', 'image/jpg']; // Allowed file types

// Update file list with file info and preview
const updateFileList = (uploadArea, file) => {
    // Update file message with file name and size
    const fileMessage = uploadArea.querySelector(".file-message");
    fileMessage.innerHTML = `${file.name}, ${file.size} bytes`;

    // Create and display image preview
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const imgPreview = document.createElement('img');
            imgPreview.src = e.target.result;
            imgPreview.classList.add('upload-image');

            // Remove existing preview if any
            const existingImg = uploadArea.querySelector('img');
            if (existingImg) {
                uploadArea.removeChild(existingImg);
            }

            // Append new image preview
            uploadArea.appendChild(imgPreview);
        };
        reader.readAsDataURL(file);
    }
};

// Validate file size and type
const validateFileInput = (file) => {
    const sizeError = document.getElementById('size-error');
    const typeError = document.getElementById('type-error');

    let isValid = true;

    if (file.size > maxFileSize) {
        sizeError.textContent = 'File size exceeds 6MB limit.';
        isValid = false;
    } else {
        sizeError.textContent = '';
    }

    if (!allowedFileTypes.includes(file.type)) {
        typeError.textContent = 'Only PNG, JPG, and JPEG files are allowed.';
        isValid = false;
    } else {
        typeError.textContent = '';
    }

    return isValid;
};

// Handle file selection from input
fileInput.addEventListener("change", (e) => {
    const file = fileInput.files[0];
    if (file && validateFileInput(file)) {
        updateFileList(uploadArea, file);
    } else {
        // Reset file input if validation fails
        fileInput.value = '';
    }
});

// Handle drag events
["dragover", "dragleave", "dragend"].forEach((eventType) => {
    uploadArea.addEventListener(eventType, (e) => {
        e.preventDefault();
        uploadArea.classList.toggle("upload-area--over", eventType === "dragover");
    });
});

// Handle file drop event
uploadArea.addEventListener("drop", (e) => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file && validateFileInput(file)) {
        fileInput.files = e.dataTransfer.files; // Update the input file list
        updateFileList(uploadArea, file);
    } else {
        fileInput.value = ''; // Reset file input if validation fails
    }
    uploadArea.classList.remove("upload-area--over");
});

// Reset event handler
uploadForm.addEventListener("reset", () => {
    const fileMessage = uploadArea.querySelector(".file-message");
    fileMessage.innerHTML = "No Files Selected";

    // Remove image preview
    const existingImg = uploadArea.querySelector('img');
    if (existingImg) {
        uploadArea.removeChild(existingImg);
    }

    // Clear error messages
    document.getElementById('size-error').textContent = '';
    document.getElementById('type-error').textContent = '';
});

// Submit event handler (for demonstration, logs the file)
uploadForm.addEventListener("submit", (e) => {
    e.preventDefault();
    console.log(fileInput.files); // Handle the submitted file(s) here
});

