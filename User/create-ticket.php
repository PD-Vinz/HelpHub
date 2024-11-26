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
            $Name = $Data['first_name'];
            $LastName = $Data['last_name'];
            $MiddleName = $Data['middle_name'];
            $MiddleInitial = $Data['middle_initial'];
            $ExtensionName = $Data['ext_name'];

// Check if there's a middle name or initial
if (!empty($MiddleInitial)) {
    $Name .= " " . $MiddleInitial . ".";
} elseif (!empty($MiddleName)) {
    $Name .= " " . $MiddleName;
}

// Add last name if it exists
if (!empty($LastName)) {
    $Name .= " " . $LastName;
}

// Add extension name if it exists (e.g., Jr., Sr., III)
if (!empty($ExtensionName)) {
    $Name .= " " . $ExtensionName . ".";
}

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
            $Name = $Data['first_name'];
            $LastName = $Data['last_name'];
            $MiddleName = $Data['middle_name'];
            $MiddleInitial = $Data['middle_initial'];
            $ExtensionName = $Data['ext_name'];

// Check if there's a middle name or initial
if (!empty($MiddleInitial)) {
    $Name .= " " . $MiddleInitial . ".";
} elseif (!empty($MiddleName)) {
    $Name .= " " . $MiddleName;
}

// Add last name if it exists
if (!empty($LastName)) {
    $Name .= " " . $LastName;
}

// Add extension name if it exists (e.g., Jr., Sr., III)
if (!empty($ExtensionName)) {
    $Name .= " " . $ExtensionName . ".";
}

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



            // for displaying system details
            $query = $pdoConnect->prepare("SELECT system_name, short_name, system_logo, system_cover FROM settings WHERE id = :id");
            $query->execute(['id' => 1]);
            $Datas = $query->fetch(PDO::FETCH_ASSOC);
            $sysName = $Datas['system_name'] ?? '';
            $shortName = $Datas['short_name'] ?? '';
             $systemCover = $Datas['system_cover'];
             $S_L = $Datas['system_logo'];
             $S_LBase64 = '';
             if (!empty($S_L)) {
                 $base64Image = base64_encode($S_L);
                 $imageType = 'image/png'; // Default MIME type
                 $S_LBase64 = 'data:' . $imageType . ';base64,' . $base64Image;
             }
         // for displaying system details //end
        
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
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*"> 
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
<?php include 'loading.php'; ?>
<body>
    <div id="wrapper">
    <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                    <div class="col-md-12">
                     <h2> CREATE NEW TICKET</h2>   
                   <hr>   </div>
                  
                </div>


                <div class="container-center">
                    <div class="modal-header">
                        <img src="assets/pic/head.jpg" alt="Technical support for DHVSU students" style="height:auto;">  


        <?php
     
        $user_id = $_SESSION['user_id']; 

        // Get the current date
        $current_date = date('Y-m-d');


        $today_start = date('Y-m-d 00:00:00');
        $today_end = date('Y-m-d 23:59:59');
        $query = "SELECT * FROM tb_tickets WHERE user_number = :user_id AND created_date BETWEEN :today_start AND :today_end";
        $stmt = $pdoConnect->prepare($query);
        $stmt->execute(['user_id' => $user_id, 'today_start' => $today_start, 'today_end' => $today_end]);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);



if ($stmt->rowCount() > 15) {

    echo '
      <div class="container-create">
    <div>
        <p>&nbsp&nbsp&nbsp<i class=" fa fa-exclamation-circle fa-sm">&nbsp&nbsp&nbsp&nbsp&nbsp</i>You can only submit a maximum of five(5) tickets per day. Please try again tomorrow</p>
    </div>
    </div>';
} else {
 
    ?>



<div class="container-create">
                <div id="ticket-message" style="display: none;">
    <p>&nbsp&nbsp&nbsp<i class=" fa fa-exclamation-circle fa-sm">&nbsp&nbsp&nbsp&nbsp&nbsp</i>The MIS is currently not accepting tickets at the moment. Please try again during office hours.</p>
</div>


<form class="issue-form" id="issueForm" action="ticket-submit.php" method="POST" enctype="multipart/form-data" style="display: none;">
 

                        <div class="form-group">
                            <label for="category">Issue</label>
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
                        <div class="upload-area">
                            <div class="upload-icon"><!-- SVG icon --></div>
                                <p>Click to upload or drag and drop</p>
                            <input type="file" id="file-input" name="image" <?php echo ($identity == 'Student') ? 'required' : ''; ?>>
                                <p class="file-message">No Files Selected</p>
                                    <p id="size-error" style="color:red;"></p>
                                    <p id="type-error" style="color:red;"></p>
                            </div>

          

                <div class="modal-body"> 
                    <div class="letter">
                        <main>
                            
                            <p>By completing this form, I allow Don Honorio Ventura State University, 
                            specifically the Management Information Systems Office, to gather, store, and handle the information 
                            I provide regarding my SMS/LMS/@dhvsu Google account concerns.</p>
<br>
                    <label>
                        <input type="checkbox" name="consent" value="yes" required> Yes, I consent
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
                 
                        
             <!-- /. PAGE INNER  -->
      
         <!-- /. PAGE WRAPPER  -->
       
                </div></div>
            </div><?php require_once ('../footer.php')?>
        </div>
    </div>
    <!-- /. WRAPPER -->
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

    <!--if setting is turned off/on-->
    <script>
    var displayForm = <?php echo $displayForm ? 'true' : 'false'; ?>;
    if (!displayForm) {
        document.getElementById('ticket-message').style.display = 'block';
    } else {
        document.getElementById('issueForm').style.display = 'block';
    }
</script>
    <!--preview-->
<script>
    // Form submission listener to check consent before submitting
    document.getElementById('issueForm').addEventListener('submit', function(event) {
        const consentYes = document.querySelector('input[name="consent"][value="yes"]');
        const consentNo = document.querySelector('input[name="consent"][value="no"]');

        if (!consentYes.checked) {
            event.preventDefault(); // Prevent form submission if "Yes" is not checked
            alert('You must consent to submit the ticket.');
        }
    });
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
var identity = "<?php echo $identity; ?>";

// Now you can make a condition based on the identity value
if (identity === "Employee") {
    populateDropdown('../issue-template/employee-issue.txt', 'category');
} else if (identity === "Student") {
    populateDropdown('../issue-template/student-issue.txt', 'category');
}
// Call the function to populate the dropdowns

    </script>

<script>
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

</script>
</body>
</html>

<hidden style="display: none;">
        #imagePreview {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
            overflow: hidden; /* Ensure that the container handles overflow */
            border: 1px solid #ddd;
            max-width: 100%; /* Full width of the container */
            max-height: 720px; /* Set a max height for the preview area */
        }
        #imagePreview img {
            max-width: 100%;
            max-height: 100%;
            width: auto; /* Let the image maintain its aspect ratio */
            height: auto; /* Let the image maintain its aspect ratio */
        }
        .error {
            color: red;
        }

        .modal-body p {
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .modal-body h1 {
            line-height: 1.2;
            margin-bottom: 15px;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            padding: 0px;
            border-top: 1px solid #ccc;
        }

        /* Hide the "No file chosen" text*/ 
input[type="file"]::file-selector-button {
    visibility: hidden;
}

/* Customize the button appearance (optional) */
.custom-file {
    position: relative;
    overflow: hidden;
    display: inline-block;
}

.custom-file input[type="file"] {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
}

.custom-file::before {
    content: 'Choose file';
    display: inline-block;
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    border: 1px solid #007bff;
    border-radius: 5px;
    cursor: pointer;
}

.custom-file:hover::before {
    background-color: #0056b3;
}
</hidden>