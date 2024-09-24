
<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["user_id"];
    $identity = $_SESSION["user_identity"];

    if ($identity == "Student"){
        $pdoUserQuery = "SELECT * FROM student_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->execute();
    
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $Name = $Data['name'];
            $Department = $Data['department'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
    
            $nameParts = explode(' ', $Name);
            $firstName = $nameParts[0];
    
            $P_PBase64 = base64_encode($P_P);
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }
    } elseif ($identity == "Employee") {
        $pdoUserQuery = "SELECT * FROM employee_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->execute();
    
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $Name = $Data['name'];
            $Department = $Data['department'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
    
            $nameParts = explode(' ', $Name);
            $firstName = $nameParts[0];
    
            $P_PBase64 = base64_encode($P_P);
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }
    }
}




if(isset($_GET['error']) && $_GET['error'] == 1) {
    // Set your error message here
    $errorMessage = "Cannot proceed with your request. Please check your submission carefully.";
    echo "<script type='text/javascript'>
        window.onload = function() {
            alert('$errorMessage');
            window.location.href = 'create-ticket.php';
        };
    </script>";
}

$pdoUserQuery = "SELECT * from settings WHERE id = 1";
$pdoResult = $pdoConnect->prepare($pdoUserQuery);
$pdoResult->execute();

$settingsData = $pdoResult->fetch(PDO::FETCH_ASSOC);
$acceptTickets = $settingsData['accept_tickets'];

if ($acceptTickets == "off") {
    $displayForm = false;
} else {
    $displayForm = true;
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>USER</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
  <!-- FONTAWESOME STYLES-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
     <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />

        <!-- UPLOAD STYLES-->
    <link href="assets/css/upload.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

   <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans&display=swap" rel="stylesheet">

   <style>
    
   </style>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="dashboard.php">USER</a>
            </div>
            <div style="color: white;
            padding: 15px 50px 5px 50px;
            float: right;
            font-size: 16px;"> Last access : <?php echo date('d F Y')?> &nbsp; 
            <div class="btn-group nav-link">
              <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="ml-3"><?php echo $Name?></span>
            <span class="fa fa-caret-down">
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="profile.php"><span class="fa fa-user"></span> MY ACCOUNT</a>
            <hr style="margin-top: 5px; margin-bottom: 5px;">
            <a class="dropdown-item" href="settings.php"><span class="fa fa-gear"></span> SETTINGS</a>
            <hr style="margin-top: 5px; margin-bottom: 5px;">
            <a class="dropdown-item" href="logout.php"><span class="fa fa-sign-out"></span> LOG OUT </a>
          </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="user-image img-responsive" />
                        <h3 style="color:white;"><?php echo $Name?></h3>
                    </li>
                    <li>
                        <a href="dashboard.php"><i class="fa fa-dashboard fa-xl" style="font-size:24px;color:rgb(255, 255, 255)"></i>  DASHBOARD </a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="fa fa-user fa-xl" style="font-size:24px;color:rgb(255, 255, 255)"></i> PROFILE </a>
                        </li>

                        <li>
                            <a class="active-menu" href="create-ticket.php"><i class="fa fa-plus fa-xl" style="font-size: 24px; color: rgb(255, 255, 255)"></i> CREATE TICKET </a>
                            </li>
                            <li>
                        <a href="all-ticket.php"><i class="fa fa-ticket fa-xl" style="font-size:24px"></i> ALL TICKET </a>
                    </li>
                </ul>
               
            </div>
            
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">


            <div>
                <div class="row">
                    <div class="col-md-12"> <div class="col-md-12">
                    <div class="col-md-12">
                     <h2> CREATE TICKET</h2>   
                    </div>
                </div>

            

                <div class="container-center">
                    <div class="modal-header">
                        <img src="assets/pic/head.png" alt="Technical support for DHVSU students">  




                        <?php

// Set the user's ID (assuming you have it from session or login)
$user_id = $_SESSION['user_id']; // Make sure user_id is properly set in the session

// Get the current date
$current_date = date('Y-m-d');

// Query to check if the user has already submitted a ticket today
$query = "SELECT * FROM tb_tickets WHERE user_number = :user_id and DATE(created_date) = :current_date";
$stmt = $pdoConnect->prepare($query); // Assuming you're using PDO
$stmt->execute(['user_id'=> $user_id, 'current_date' => $current_date]);

// Check if a row was found
if ($stmt->rowCount() > 0) {
    // User has already submitted a ticket today, display the message
    echo '
      <div class="container-create">
    <div>
        <p>&nbsp&nbsp&nbsp<i class=" fa fa-exclamation-circle fa-sm">&nbsp&nbsp&nbsp&nbsp&nbsp</i>You can only submit a ticket once per day. Please try again tomorrow</p>
    </div>
    </div>';
} else {
    // No ticket submitted today, display the form
    ?>

                <div class="container-create">
                <div id="ticket-message" style="display: none;">
    <p>&nbsp&nbsp&nbsp<i class=" fa fa-exclamation-circle fa-sm">&nbsp&nbsp&nbsp&nbsp&nbsp</i>The MIS is currently not accepting tickets at the moment. Please try again during office hours.</p>
</div>


                <form class="issue-form" id="issueForm" action="ticket-submit.php" method="POST" enctype="multipart/form-data" style="display: none;">
 
                        <div class="form-group">
                            <label for="category">ISSUE</label>
                            <select id="category" name="category" class="form-control dropdown" required>

                                <option value="">SELECT PROBLEM</option>
                                <!--
                                <option value="DHVSU EMAIL">DHVSU EMAIL</option>
                                <option value="DHVSU PORTAL">DHVSU PORTAL</option>
                                <option value="DHVSU SMS">DHVSU SMS</option>
                                -->

                            </select>
                        </div>

                        <div class="form-group">
                                    <label for="issue-description">Issue Description</label>
                                    <textarea id="issue-description" name="issue-description" class="form-control" maxlength="255" oninput="updateRemainingCharacters()" required></textarea>
                                    <small id="remaining-characters" class="form-text text-muted">255 characters remaining</small>
                                </div>

<!--

                        <div class="form-group">
                            <label for="" class="control-label">Upload Screenshot</label>
                        <div class="form-group" id="imagePreview">
                            <img src="assets/pic/pics.jpg" alt="" id="cimg2" class="img-thumbnail">
                        </div>
                        
                        <div class="custom-file">
                            <input type="file" id="imageInput" name="image" accept="image/*" required>       
                            <p id="sizeError" class="error"></p>
                            <p id="typeError" class="error"></p>
                        </div>
                        </div>
-->    

    <label for="" class="control-label">Upload and attach files</label>
    <div class="dropzone-area">
        <div class="file-upload-icon"><!-- svg icon --></div> <p>Click to upload or drag and drop</p>
            <input type="file" required id="upload-file" name="image"> <p class="message">No Files Selected</p>
            <p id="sizeError" style="color:red;"></p>
            <p id="typeError" style="color:red;"></p>
        </div>
          

                <div class="modal-body"> 
                    <div class="letter">
                        <main>
                            
                            <p>By completing this form, I allow Don Honorio Ventura State University, 
                            specifically the Management Information Systems Office, to gather, store, and handle the information 
                            I provide regarding my SMS/LMS/@dhvsu Google account concerns.</p>

                    <label>
                        <input type="radio" name="consent" value="yes" required> Yes, I consent
                    </label>
                    <label>
                        <input type="radio" name="consent" value="no" required> No, I do not consent
                    </label>
                        </main>
                    </div>
                </div>
                
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">SUBMIT</Input>
            </div>

</form>

<?php
}
?>
                </div>
</div>
                 <!-- /. ROW  -->
                </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
    <!-- /. WRAPPER -->
    <?php require_once ('../footer.php')?>
            </div>
    <!-- SCRIPTS - AT THE BOTTOM TO REDUCE THE LOAD TIME -->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- DATA TABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    <!--preview-->

<script>
    // Modal confirm button to handle form submission
    document.getElementById('confirmBtn').addEventListener('click', function() {
        const consentYes = document.querySelector('input[name="consent"][value="yes"]');
        const consentNo = document.querySelector('input[name="consent"][value="no"]');

        if (consentYes.checked) {
            // If consent is given, submit the form
            document.querySelector('.issue-form').submit();
        } else if (consentNo.checked) {
            // If 'No' is selected, prevent submission and alert
            alert('You must consent to submit the ticket.');
        } else {
            alert('Please select an option before proceeding.');
        }
    });

    var displayForm = <?php echo $displayForm ? 'true' : 'false'; ?>;
    if (!displayForm) {
        document.getElementById('ticket-message').style.display = 'block';
    } else {
        document.getElementById('issueForm').style.display = 'block';
    }

</script>
    <script>
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

    </script>
    <script>
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
// Pass the PHP variable to JavaScript
// Function to populate the dropdown
function populateDropdown(url, dropdownId) {
    var dropdown = document.getElementById(dropdownId);
    // Clear existing options
    dropdown.innerHTML = '';

    // Add the "NONE" option as the first option
    var noneOption = document.createElement('option');
    noneOption.value = 'Select an issue';
    noneOption.text = 'Select an issue';
    dropdown.add(noneOption);

    // Fetch options from the provided URL
    fetch(url)
        .then(response => response.text())
        .then(data => {
            var options = data.split('\n');
            options.forEach(option => {
                if (option.trim()) {
                    var opt = document.createElement('option');
                    opt.value = option.trim();
                    opt.text = option.trim();
                    dropdown.add(opt);
                }
            });
        })
        .catch(error => console.error('Error populating dropdown:', error));
}

// Function to validate the form
function validateForm(event) {
    var categorySelect = document.getElementById('category');
    var selectedValue = categorySelect.value;
    
    if (selectedValue === "Select an issue") {
        event.preventDefault(); // Prevent form submission
        alert("Please select the issue you want to report.");
    }
}

// Populate the dropdown based on identity
var identity = "<?php echo $identity; ?>";
if (identity === "Employee") {
    populateDropdown('../issue-template/employee-issue.txt', 'category');
} else if (identity === "Student") {
    populateDropdown('../issue-template/student-issue.txt', 'category');
}


    </script>
<script>
// Get elements
const cropzoneBox = document.getElementsByClassName("dropzone-box")[0];
const inputFiles = document.querySelector(".dropzone-area input[type='file']");
const dropZoneElement = inputFiles.closest(".dropzone-area");
const maxSize = 6 * 1024 * 1024; // 6MB size limit
const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg']; // Allowed file types

// Update dropzone file list
const updateDropzoneFileList = (dropzoneElement, file) => {
    let dropzoneFileMessage = dropzoneElement.querySelector(".message");
    dropzoneFileMessage.innerHTML = `${file.name}, ${file.size} bytes`;

    // Create and display image preview
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
            let img = document.createElement('img');
            img.src = e.target.result;

            // Apply the CSS class to the image
            img.classList.add('dropzone-image');

            // Remove existing preview if any
            const existingImg = dropzoneElement.querySelector('img');
            if (existingImg) {
                dropzoneElement.removeChild(existingImg);
            }

            dropZoneElement.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
};

// Validate file size and type
const validateFile = (file) => {
    const sizeError = document.getElementById('sizeError');
    const typeError = document.getElementById('typeError');

    if (file.size > maxSize) {
        sizeError.textContent = 'File size exceeds 6MB limit.';
        return false;
    } else {
        sizeError.textContent = '';
    }

    if (!allowedTypes.includes(file.type)) {
        typeError.textContent = 'Only PNG, JPG, and JPEG files are allowed.';
        return false;
    } else {
        typeError.textContent = '';
    }

    return true;
};

// Handle file selection
inputFiles.addEventListener("change", (e) => {
    const file = inputFiles.files[0];
    if (file && validateFile(file)) {
        updateDropzoneFileList(dropZoneElement, file);
    } else {
        // Reset file input if validation fails
        inputFiles.value = '';
    }
});

// Handle drag events
["dragover", "dragleave", "dragend"].forEach((type) => {
    dropZoneElement.addEventListener(type, (e) => {
        if (type === "dragover") {
            e.preventDefault();
            dropZoneElement.classList.add("dropzone--over");
        } else {
            dropZoneElement.classList.remove("dropzone--over");
        }
    });
});

// Handle drop event
dropZoneElement.addEventListener("drop", (e) => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file && validateFile(file)) {
        inputFiles.files = e.dataTransfer.files; // Update the input file list
        updateDropzoneFileList(dropZoneElement, file);
    } else {
        // Reset file input if validation fails
        inputFiles.value = '';
    }
    dropZoneElement.classList.remove("dropzone--over");
});

// Reset event
cropzoneBox.addEventListener("reset", () => {
    let dropzoneFileMessage = dropZoneElement.querySelector(".message");
    dropzoneFileMessage.innerHTML = "No Files Selected";

    // Remove image preview
    const existingImg = dropZoneElement.querySelector('img');
    if (existingImg) {
        dropZoneElement.removeChild(existingImg);
    }
});

// Submit event
cropzoneBox.addEventListener("submit", (e) => {
    e.preventDefault();
    const myFile = document.getElementById("upload-file");
    console.log(myFile.files);
});

// Add form validation
document.getElementById('issueForm').addEventListener('submit', validateForm);
   </script>
</body>
</html>




