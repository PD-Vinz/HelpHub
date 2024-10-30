<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["admin_number"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["admin_number"];

    $pdoUserQuery = "SELECT * FROM mis_employees WHERE admin_number = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();

    $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

    if ($Data) {
        $Name = $Data['f_name'];
        $Position = $Data['position'];
        $U_T = $Data['user_type'];

        $nameParts = explode(' ', $Name);
        $firstName = $nameParts[0];
    } else {
        // Handle the case where no results are found
        echo "No student found with the given student number.";
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

try {
    $next_id = "";
    // SQL to find the smallest unused 10-digit ID
    $sql = "
        SELECT MIN(t1.user_id + 1) AS next_id
        FROM student_user t1
        LEFT JOIN student_user t2 ON t1.user_id + 1 = t2.user_id
        WHERE t2.user_id IS NULL 
        AND LENGTH(t1.user_id) = 10
        AND LENGTH(t1.user_id + 1) = 10;
    ";

    $stmt = $pdoConnect->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['next_id']) {
        // Check if the next_id has 10 digits
        $next_id = str_pad($result['next_id'], 10, '0', STR_PAD_LEFT);
    } else {
        echo "No unused IDs found or the table is empty.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

   <style>
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
    /*background-color: #007bff;
    color: white;*/
    padding: 5px 10px;
    border: 1px solid #C70039 ;
    border-radius: 5px;
    cursor: pointer;
}

.custom-file:hover::before {
    background-color: #800000;
    color: white;
}
   </style>
</head>
<body>
    <div id="wrapper">
        <!-- NAV SIDE  -->
         <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>Create New Student Account</h2>   

                    </div>
                </div>
                 <!-- /. ROW  -->
                 <hr />
                 <div class="panel panel-default">
	<div class="panel-body">
		<div class="container-fluid col-md-12">
			<div id="msg"></div>
<form method="post" action="action/add-single-userS-upload.php" id="manage-user" enctype="multipart/form-data" onsubmit='return validateForm();'>
            <div class="container-fluid col-md-6">
    <style>
        .availability-message {
            color: green;
            display: none; /* Hidden by default */
        }
        .unavailable {
            color: red;
        }
    </style>
                <div class="form-group col-6">
					<label for="name">User ID</label>
                    <input type="number" name="userid" id="userid" class="form-control" value="" required autocomplete="off" oninput="checkUserId()">
                    <div id="availabilityMessage" class="availability-message"></div>
                </div>	
<script>
    function checkUserId() {
        const useridInput = document.getElementById('userid');
        const availabilityMessage = document.getElementById('availabilityMessage');

        const userId = useridInput.value.trim();
        const userType = "Student"; // Set this to the actual user type you want to send

        if (userId.length === 0) {
            availabilityMessage.style.display = 'none'; // Hide message if input is empty
            return;
        } else if (userId.length >= 15) {
            availabilityMessage.textContent = 'Max ID length is only 15';
            availabilityMessage.classList.add('unavailable');
            availabilityMessage.style.display = 'block';
            return; // Add return to stop execution if max length is reached
        } else {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch/does-id-already-exist.php?userid=" + encodeURIComponent(userId) + "&usertype=" + encodeURIComponent(userType), true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.available) {
                        availabilityMessage.textContent = 'User ID is available!';
                        availabilityMessage.classList.remove('unavailable');
                        availabilityMessage.style.display = 'block';
                    } else {
                        availabilityMessage.textContent = 'User ID is already taken.';
                        availabilityMessage.classList.add('unavailable');
                        availabilityMessage.style.display = 'block';
                    }
                }
            };
            xhr.send(); // Move this inside the else block
        }
    }
</script>
                
				<div class="form-group col-6">
					<label for="name">Name</label>
					<input type="text" name="name" id="nameInput" class="form-control" required autocomplete="off">
                    <span id="nameError" style="color: red;"></span>
                </div>
                <div class="form-group col-6">
					<label for="name">Birthday</label>
					<input type="date" name="birthday" id="bdayInput" class="form-control" required autocomplete="off">
				</div>
<script>
    const bdayInput = document.getElementById("bdayInput");

    // Calculate minimum date (100 years ago from today)
    const minDate = new Date();
    minDate.setFullYear(minDate.getFullYear() - 100);
    bdayInput.min = minDate.toISOString().split("T")[0];

    // Calculate maximum date (18 years ago from today)
    const maxDate = new Date();
    maxDate.setFullYear(maxDate.getFullYear() - 10);
    bdayInput.max = maxDate.toISOString().split("T")[0];
</script> 
                <div class="form-group col-6">
					<label for="sex">Sex</label>
					<select name="sex" id="sex" class="form-control" required>
						<option value="Male">Male</option>
						<option value="Female">Female</option>
					</select>
				</div>
                <div class="form-group col-6">
					<label for="campus">Campus</label>
					<select name="campus" id="campusDropdown" class="form-control" required>

                    </select>
				</div>
                <div class="form-group col-6">
					<label for="department">Department</label>
					<select name="department" id="categoryDropdown" class="form-control" required>

                    </select>
				</div>
                <div class="form-group col-6">
					<label for="course">Course</label>
					<select name="course" id="itemDropdown" class="form-control" required>
                        
                    </select>
				</div>
                <div class="form-group col-6">
					<label for="year_section">Year and Section</label>
					<input type="text" name="year_section" id="year_section" class="form-control" required autocomplete="off">
				</div>
                <div class="form-group col-6">
					<label for="email">Email Address</label>
					<input type="email" name="email" id="email" class="form-control" value="" required  autocomplete="off">
				</div>
                <div class="form-group col-6">
					<label for="altemail">Alternative Email Address</label>
					<input type="email" name="altemail" id="altemail" class="form-control" value=""  autocomplete="off">
				</div>
			
               
                </div>
                <div class="col-md-6">
				<div class="form-group col-6">
					<label for="" class="control-label">Avatar</label>
                    <br>
					<div class="custom-file">
                        <input type="file" class="form-control" id="customFile" name="image" onchange="displayImg(this)">
		             
		            </div>
				</div>
				<div class="form-group col-6 d-flex justify-content-center">
					<img src="assets\img\No-Profile.png" alt="" id="cimg" class="img-fluid img-thumbnail">
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
			<div class="col-md-12">
				<div class="row">
					<button class="btn btn-sm btn-primary mr-2" form="manage-user">Add Account</button>
					<a class="btn btn-sm btn-secondary" href="user-student-list.php">Cancel</a>
				</div>
			</div>
		</div>
    </form>
<script>
    const nameInput = document.getElementById('nameInput');
    const nameError = document.getElementById('nameError');
    const nameRegex = /^[a-zA-ZÀ-ÿ\s'.-]+$/;
    const maxLength = 100; // Set maximum length

    // Function to validate the name
    function validateName() {
        const name = nameInput.value.trim();

        if (name === "") {
            nameError.textContent = 'Name is required.';
            return false;
        } else if (!nameRegex.test(name)) {
            nameError.textContent = 'Please enter a valid name (letters, spaces, hyphens, apostrophes, and periods only).';
            return false;
        } else if (name.length >= maxLength) {
            nameError.textContent = `Name must be ${maxLength} characters or fewer.`;
            return false;
        } else {
            nameError.textContent = ''; // Clear error if valid
            return true;
        }
    }

    // Function to confirm submission
    function confirmSubmit() {
        return confirm("Please make sure that the data you are submitting is true. Are you sure you want to proceed?");
    }

    // Main function to validate form before submission
    function validateForm() {
        if (!validateName()) {
            return false; // Prevent form submission if name validation fails
        }
        return confirmSubmit(); // Show confirmation dialog if name is valid
    }

    // Live validation feedback
    nameInput.addEventListener('input', validateName);
</script>
</div>
<style>
    .img-thumbnail {
    padding: 0.25rem;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.075);
    max-width: 100%;
    height: auto;
}
	.img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
    
.rounded-circle {
    border-radius: 50% !important;
}

.custom-file-input {
    position: relative;
    z-index: 2;
    width: 100%;
    height: calc(2.25rem + 2px);
    margin: 0;
    opacity: 0;
}
.button, input {
    overflow: visible;
}
.input, button, select, optgroup, textarea {
    margin: 0;
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
}
*, *::before, *::after {
    box-sizing: border-box;
}

.input[type="file" i] {
    appearance: none;
    background-color: initial;
    cursor: default;
    align-items: baseline;
    color: inherit;
    text-overflow: ellipsis;
    text-align: start !important;
    padding: initial;
    border: initial;
    white-space: pre;
    overflow: hidden !important;
}

.input {
    
    text-rendering: auto;
    color: fieldtext;
    letter-spacing: normal;
    word-spacing: normal;
    line-height: normal;
    text-transform: none;
    text-indent: 0px;
    text-shadow: none;
    display: inline-block;
    text-align: start;
    appearance: auto;
    -webkit-rtl-ordering: logical;
    cursor: text;
    background-color: field;
    margin: 0em;
    padding: 1px 0px;
    border-width: 2px;
    border-style: inset;
    border-color: light-dark(rgb(118, 118, 118), rgb(133, 133, 133));
    border-image: initial;
    padding-block: 1px;
    padding-inline: 2px;
}
.custom-select {
  display: inline-block;
  width: 100%;
  /*height: calc(2.25rem + 2px);*/
  padding: 0.375rem 1.75rem 0.375rem 0.75rem;
  /*font-size: 1rem;*/
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  vertical-align: middle;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

.custom-control-label::before, .custom-file-label, .custom-select {
  transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.custom-file-label {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    z-index: 1;
    height: calc(2.25rem + 2px);
    padding: 0.375rem 0.75rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    box-shadow: none;
}

.card {
  position: relative;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-direction: column;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 0 solid rgba(0, 0, 0, 0.125);
  border-radius: 0.25rem;
}
</style>
<script>
function displayImg(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#cimg').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
    </div>
    <?php require_once('../footer.php') ?> 
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
    <script>
function populateCampus(fileName, dropdownId) {
    // Add a random query parameter to the file name to prevent caching
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
            dropdown.innerHTML = '';

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

// Call the function to populate the category dropdown
populateCampus('txt/campus.txt', 'campusDropdown');
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

                // Iterate over each line and create an option element
                options.forEach(option => {
                    if (option.trim() !== '') {  // Ignore empty lines
                        const opt = document.createElement('option');
                        opt.value = option.trim();
                        opt.textContent = option.trim();
                        dropdown.appendChild(opt);
                    }
                });

                // After populating, call handleCategoryChange to initialize the second dropdown
                handleCategoryChange();
            })
            .catch(error => console.error(`Error fetching the text file (${fileName}):`, error));
    }

    // Function to populate a dropdown from a specific section of a text file
    function populateDropdownFromSection(fileName, dropdownId, sectionMarker) {
        const url = fileName + '?v=' + new Date().getTime();
        // Fetch the text file
        fetch(url)
            .then(response => response.text())
            .then(data => {
                // Split the text data by lines
                const lines = data.split('\n');

                // Variables to hold the section data
                let isInSection = false;
                const options = [];

                // Iterate over each line to find the desired section
                lines.forEach(line => {
                    const trimmedLine = line.trim();

                    // Check if the line is the section marker
                    if (trimmedLine === sectionMarker) {
                        isInSection = true; // Start capturing lines
                    } else if (trimmedLine.startsWith('#')) {
                        isInSection = false; // End capturing lines
                    } else if (isInSection && trimmedLine !== '') {
                        options.push(trimmedLine); // Capture lines within the section
                    }
                });

                // Get the dropdown element
                const dropdown = document.getElementById(dropdownId);

                // Clear existing options in the dropdown
                dropdown.innerHTML = '';

                // Populate dropdown with options from the section
                options.forEach(option => {
                    const opt = document.createElement('option');
                    opt.value = option;
                    opt.textContent = option;
                    dropdown.appendChild(opt);
                });
            })
            .catch(error => console.error(`Error fetching the text file (${fileName}):`, error));
    }

    // Function to handle category change
    function handleCategoryChange() {
        const categoryDropdown = document.getElementById('categoryDropdown');
        const selectedCategory = categoryDropdown.value;
        const sectionMarker = `# ${selectedCategory}`;

        // Populate the second dropdown based on the selected category
        populateDropdownFromSection('txt/course.txt', 'itemDropdown', sectionMarker);
    }

    // Call the function to populate the category dropdown
    populateDropdown('txt/department.txt', 'categoryDropdown');

     // Event listener for category dropdown change
     document.getElementById('categoryDropdown').addEventListener('change', handleCategoryChange);

// Initial population based on the default selection
handleCategoryChange();
</script>

</body>
</html>
