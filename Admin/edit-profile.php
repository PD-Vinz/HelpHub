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
        $Email_Add = $Data['email_address'];
        $Alt_Email_Add = $Data['alt_email_address'];

        $FirstName = $Data['f_name'];
        $LastName = $Data['l_name'];
        $MiddleName = $Data['m_name'];
        $MiddleInitial = $Data['m_initial'];
        $ExtensionName = $Data['ext_name'];

$Name = $Data['f_name'];
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
        $P_P = $Data['profile_picture'];
        $Sex = $Data['sex'];
        $Age = $Data['age'];
        $Bday = $Data['birthday'];
        $U_T = $Data['user_type'];

    

        $P_PBase64 = base64_encode($P_P);
        $date = new DateTime($Bday);
        $formattedDate = $date->format('F j, Y'); // This will give "July 22, 1990"
    } else {
        // Handle the case where no results are found
        echo "No Admin found with the given student number.";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*">   
  
    <!-- BOOTSTRAP STYLES -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
  <!-- FONTAWESOME STYLES-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- CUSTOM STYLES -->

    <link href="assets/css/custom.css" rel="stylesheet">
    <!-- GOOGLE FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <!-- TABLE STYLES -->
    <link href="assets/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

 
</head>

<body>
    <div id="wrapper">
    <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE -->
        <div id="page-wrapper">
            <div id="page-inner">
            <div class="row">
            <div class="col-md-12">
                    <div class="col-md-12">
                        <h2>Edit profile</h2>          <hr>
                        <div class="container">
                            <h1 class="text-primary"></h1>
                  
                            <div class="row">
                               
<form class="form-horizontal" role="form" method="post" action="action\update_profile.php" enctype="multipart/form-data" onsubmit="return validateForm();">
                                <!-- left column -->

                                
                                <div class="avatar" id="avatar" style="align-content:center;">
                                    <div id="preview">
                                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" id="avatar-image" class="avatar_img" id="" alt="No Image">
                                    </div>
                                    <div class="avatar_upload">
                                        <label class="upload_label">Choose
                                            <input type="file" id="upload" name="image" accept="image/*">
                                        </label>
                                    </div>
                                  </div>
<script>    // Validate file type
        $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        $fileType = mime_content_type($image['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            <?php echo "Only PNG, JPG, and JPEG files are allowed."; ?>
            exit;
        }

        $imgContent = file_get_contents($image['tmp_name']);
</script>

                                  <div class="row">
                                    <span id="name" tabindex="4" data-key="1" contenteditable="true" onkeyup="changeAvatarName(event, this.dataset.key, this.textContent)" onblur="changeAvatarName('blur', this.dataset.key, this.textContent)" hidden></span>
                                  </div>
                                <!-- edit form column -->
                                <div class="col-md-12 personal-info ">
                               <br>

<div class="form-group">
    <label class="col-lg-3 control-label">FIRST NAME</label>
    <div class="col-lg-7">
        <input class="form-control" name="first_name" id="firstNameInput" type="text" value="<?php echo $FirstName; ?>" required autocomplete="off" maxlength="100" oninput="capitalizeFirstLetter(this)">
        <span id="firstNameError" style="color: red; font-size:smaller;"></span>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label">LAST NAME</label>
    <div class="col-lg-7">
        <input class="form-control" name="last_name" id="lastNameInput" type="text" value="<?php echo $LastName; ?>" required autocomplete="off" maxlength="50" oninput="capitalizeFirstLetter(this)">
        <span id="lastNameError" style="color: red; font-size:smaller;"></span>
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label">MIDDLE NAME</label>
    <div class="col-lg-7">
        <input class="form-control" name="middle_name" id="middleNameInput" type="text" value="<?php echo $MiddleName; ?>" autocomplete="off" maxlength="50" oninput="capitalizeFirstLetter(this)">
        <span id="middleNameError" style="color: red; font-size:smaller;"></span>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label">MIDDLE INITIAL</label>
    <div class="col-lg-7">
        <input class="form-control" name="middle_initial" id="middleInitialInput" type="text" value="<?php echo $MiddleInitial; ?>" autocomplete="off" maxlength="1" oninput="this.value = this.value.toUpperCase(); this.value = this.value.replace(/[^A-Z]/g, '');">
        <span id="middleInitialError" style="color: red; font-size:smaller;"></span>
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label">EXT. NAME</label>
    <div class="col-lg-7">
        <input class="form-control" name="ext_name" id="extNameInput" type="text" value="<?php echo $ExtensionName; ?>" autocomplete="off" maxlength="2" oninput="capitalizeFirstLetter(this)">
        <span id="extNameError" style="color: red; font-size:smaller;"></span>
    </div>
</div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">EMAIL ADDRESS</label>
                                            <div class="col-lg-7">
                                                <input class="form-control" name="emailadd" id="emailadd" type="text" value="<?php echo $Email_Add?>" required placeholder="DHVSU Email" oninput="validateEmail()">
                                                <span id="emailError" style="color: red; font-size:smaller;"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">ALT. EMAIL ADDRESS</label>
                                            <div class="col-lg-7">
                                                <input class="form-control" name="altemailadd" type="text" value="<?php echo $Alt_Email_Add?>" placeholder="Personal Email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="category" class="col-lg-3 control-label">SEX ASSIGNED AT BIRTH</label>
                                            <div class="col-lg-7">
                                                <select id="category" name="sex" class="form-control dropdown" required>
                                                    <option value="Male" <?php echo ($Sex == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                    <option value="Female" <?php echo ($Sex == 'Female') ? 'selected' : ''; ?>>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">BIRTHDAY</label>
                                            <div class="col-lg-7">
                                                <input class="form-control" name="bday" type="date" value="<?php echo $Bday?>" required id="bdayInput">
                                            </div>
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
                                        <div class="modal-footer">	 
                                            <button type="button" class="btn btn-primary" onclick="history.back()">BACK</button>
                                            <input type="submit" class="btn btn-primary" name="update" value="UPDATE PROFILE"  >
                                           
                                        </div>
                                        



                                        
                                                </div>
                                            </div>
                                        </div>
                                    </form>
<script>
    function capitalizeFirstLetter(input) {
    // Split the input value into words
    const words = input.value.split(' ');
    // Capitalize the first letter of each word
    for (let i = 0; i < words.length; i++) {
        if (words[i]) {
            words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1).toLowerCase();
        }
    }
    // Join the words back into a string and update the input value
    input.value = words.join(' ');
}

const inputs = {
    firstName: {
        input: document.getElementById('firstNameInput'),
        error: document.getElementById('firstNameError'),
        required: true
    },
    lastName: {
        input: document.getElementById('lastNameInput'),
        error: document.getElementById('lastNameError'),
        required: true
    },
    middleName: {
        input: document.getElementById('middleNameInput'),
        error: document.getElementById('middleNameError'),
        required: false
    },
    middleInitial: {
        input: document.getElementById('middleInitialInput'),
        error: document.getElementById('middleInitialError'),
        required: false
    },
    extName: {
        input: document.getElementById('extNameInput'),
        error: document.getElementById('extNameError'),
        required: false
    },
    email: {
        input: document.getElementById('emailadd'), // Assuming email input has ID 'emailadd'
        error: document.getElementById('emailError'), // Assuming error span has ID 'emailError'
        required: true,
        domain: "@dhvsu.edu.ph"
    }
};

const nameRegex = /^[a-zA-ZÀ-ÿ\s'.-]+$/;
const maxLength = 100;

// Function to validate a single field
function validateField(field) {
    const value = field.input.value.trim();
    
    // Required field check
    if (field.required && value === "") {
        field.error.textContent = 'This field is required.';
        return false;
    }

    // Email-specific validation
    if (field.input === inputs.email.input) {
        if (!value.endsWith(inputs.email.domain)) {
            field.error.textContent = `Please enter a valid DHVSU email (example${inputs.email.domain}).`;
            return false;
        }
    } else {
        // Name validation
        if (value && !nameRegex.test(value)) {
            field.error.textContent = 'Please enter a valid value (letters, spaces, hyphens, apostrophes, and periods only).';
            return false;
        } else if (value.length > maxLength) {
            field.error.textContent = `This field must be ${maxLength} characters or fewer.`;
            return false;
        }
    }

    field.error.textContent = ''; // Clear error if valid
    return true;
}

// Main function to validate all fields before form submission
function validateForm() {
    let isValid = true;

    for (const key in inputs) {
        if (!validateField(inputs[key])) {
            isValid = false;
        }
    }

    return isValid ? confirmSubmit() : false; // Show confirmation dialog if all fields are valid
}

// Function to confirm submission
function confirmSubmit() {
    return confirm("Please make sure that the data you are submitting is true. Are you sure you want to proceed?");
}

// Attach live validation feedback to each input
for (const key in inputs) {
    inputs[key].input.addEventListener('input', () => validateField(inputs[key]));
}

</script>                              
                                </div>
                           
                        </div></div></div>
                    
                        <?php include '../footer.php' ?> 
                    </div>
                </div>
            </div>
        </div>
        
                        
                        
                        <!-- /. ROW -->
                    </div>
                </div>
                <!-- /. ROW -->
            </div>
            <!-- /. PAGE INNER -->
        </div>
        <!-- /. PAGE WRAPPER -->
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
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });
    </script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    <script src="../user/assets/js/custom.js"></script>
    <script type="text/javascript" src="post.js"></script>
</body>
</html>
