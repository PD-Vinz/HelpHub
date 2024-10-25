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
        FROM employee_user t1
        LEFT JOIN employee_user t2 ON t1.user_id + 1 = t2.user_id
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

if (isset($_GET['id'])){
    $id = $_GET['id'];

    try {
        $pdoUserQuery = "SELECT * FROM employee_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->execute();
    
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $FetchUserID = $Data['user_id'];
            $FetchEmail = $Data['email_address'];
            //$FetchAltEmail = $Data['alt_email_address'];
            $FetchName = $Data['name'];
            $FetchCampus = $Data['campus'];
            $FetchDepartment = $Data['department']; 
            $FetchSex = $Data['sex'];
            $FetchBirthday = $Data['birthday'];
            $FetchAge = $Data['age'];
            $Avatar = $Data['profile_picture'];

            $AvatarBase64 = base64_encode($Avatar);
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DHVSU MIS - HelpHub</title>
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
                     <h2>Create New Employee Account</h2>   

                    </div>
                </div>
                 <!-- /. ROW  -->
                 <hr />
                 <div class="panel panel-default">
	<div class="panel-body">
		<div class="container-fluid col-md-12">
			<div id="msg"></div>
			<form method="post" action="action/update-single-userE.php" id="manage-user" enctype="multipart/form-data">
            <div class="container-fluid col-md-6">
            <div class="form-group col-6">
					<label for="name">User ID</label>
                    <input type="text" name="userid" class="form-control" value="<?php echo $FetchUserID?>" readonly>
				</div>
                <div class="form-group col-6">
					<label for="email">Email Address</label>
					<input type="email" name="email" id="email" class="form-control" value="<?php echo $FetchEmail?>">
				</div>
				<div class="form-group col-6">
					<label for="name">Name</label>
					<input type="text" name="name" id="name" class="form-control" value="<?php echo $FetchName?>" required>
				</div>
                <div class="form-group col-6">
					<label for="name">Birthday</label>
					<input type="date" name="birthday" id="birthday" class="form-control" value="<?php echo $FetchBirthday?>" required>
				</div>
                <div class="form-group col-6">
					<label for="name">Age</label>
					<input type="text" name="age" id="age" class="form-control" value="<?php echo $FetchAge?>" readonly >
				</div>
                <div class="form-group col-6">
					<label for="sex">Sex</label>
					<select name="sex" id="sex" class="form-control" required>
						<option value="Male" <?php echo ($FetchSex == 'Male') ? 'selected' : ''; ?>>Male</option>
						<option value="Female" <?php echo ($FetchSex == 'Female') ? 'selected' : ''; ?>>Female</option>
					</select>
				</div>
                <div class="form-group col-6">
					<label for="campus">Campus</label>
					<select type="text" name="campus" id="campusDropdown" class="form-control" required>

                    </select>
				</div>
                <div class="form-group col-6">
					<label for="department">Department</label>
					<select name="department" id="categoryDropdown" class="form-control" required>

                    </select>
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
					<img src="data:image/jpeg;base64,<?php echo $AvatarBase64?>" alt="" id="cimg" class="img-fluid img-thumbnail">
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
			<div class="col-md-12">
				<div class="row">
					<button class="btn btn-sm btn-primary mr-2" form="manage-user">Update Account</button>
					<a class="btn btn-sm btn-secondary" href="user-employee-list.php">Cancel</a>
				</div>
			</div>
		</div>
    </form>
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
function populateCampus(fileName, dropdownId, selectedCampus) {
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

            // Select the option based on selectedValue
            if (selectedCampus) {
                dropdown.value = selectedCampus; // Set the selected option
            }
        })
        .catch(error => console.error(`Error fetching the text file (${fileName}):`, error));
}

// Assuming you have a PHP variable $FetchCampus, output it to JavaScript
const fetchCampus = "<?php echo $FetchCampus; ?>"; // Replace with your method to get the PHP variable

// Call the function to populate the category dropdown
populateCampus('txt/campus.txt', 'campusDropdown', fetchCampus);
</script>
<script>
    // Function to populate a dropdown from a specified text file
    function populateDropdown(fileName, dropdownId, selectedDepartment) {
        const url = fileName + '?v=' + new Date().getTime();
        // Fetch the text file
        fetch(url)
            .then(response => response.text())
            .then(data => {
                const options = data.split('\n');
                const dropdown = document.getElementById(dropdownId);

                options.forEach(option => {
                    if (option.trim() !== '') {  // Ignore empty lines
                        const opt = document.createElement('option');
                        opt.value = option.trim();
                        opt.textContent = option.trim();
                        dropdown.appendChild(opt);
                    }
                });

                // Select the option based on selectedDepartment
                if (selectedDepartment) {
                    dropdown.value = selectedDepartment; // Set the selected option
                }

                // After populating, call handleCategoryChange to initialize the second dropdown
                handleCategoryChange();
            })
            .catch(error => console.error(`Error fetching the text file (${fileName}):`, error));
    }

    // Assuming you have a PHP variable $FetchDepartment, output it to JavaScript
    const fetchDepartment = "<?php echo $FetchDepartment; ?>"; // Replace with your method to get the PHP variable

    // Call the function to populate the category dropdown
    populateDropdown('txt/department.txt', 'categoryDropdown', fetchDepartment);

    // Event listener for category dropdown change
    document.getElementById('categoryDropdown').addEventListener('change', handleCategoryChange);

    // Initial population based on the default selection
    document.addEventListener('DOMContentLoaded', handleCategoryChange);
</script>
</body>
</html>
