<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

if (!isset($_SESSION["address"]) && !isset($_SESSION["user"]) && !isset($_SESSION["first-time"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["first-time"];
    $Address = $_SESSION["address"];
    $User = $_SESSION["user"];

    if ($User == "Student"){
        $pdoUserQuery = "SELECT * FROM student_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->execute();
    
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $Email_Add = $Data['email_address'];

            $FirstName = $Data['first_name'];
            $LastName = $Data['last_name'];
            $MiddleName = $Data['middle_name'];
            $MiddleInitial = $Data['middle_initial'];
            $ExtensionName = $Data['ext_name'];

            $Campus = $Data['campus'];
            $Department = $Data['department'];
            $Course = $Data['course'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
            $Sex = $Data['sex'];
            $Age = $Data['age'];
            $Bday = $Data['birthday'];
            $UserType = $Data['user_type'];
    
            $P_PBase64 = base64_encode($P_P);
            $date = new DateTime($Bday);
            $formattedDate = $date->format('F j, Y'); // This will give "July 22, 1990"
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }
    } elseif ($User == "Employee") {
        $pdoUserQuery = "SELECT * FROM employee_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->execute();
    
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $Email_Add = $Data['email_address'];

            $FirstName = $Data['first_name'];
            $LastName = $Data['last_name'];
            $MiddleName = $Data['middle_name'];
            $MiddleInitial = $Data['middle_initial'];
            $ExtensionName = $Data['ext_name'];

            $Campus = $Data['campus'];
            $Department = $Data['department'];
            //$Course = $Data['course'];
            //$Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
            $Sex = $Data['sex'];
            $Age = $Data['age'];
            $Bday = $Data['birthday'];
            $UserType = $Data['user_type'];
    
            $P_PBase64 = base64_encode($P_P);
            $date = new DateTime($Bday);
            $formattedDate = $date->format('F j, Y'); // This will give "July 22, 1990"
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['information'])) {
        // Get the submitted values
                $NewFirstName = $_POST['FName'];
                $NewLastName = $_POST['LName'];
                $NewMiddleName = $_POST['MName'];
                $NewMiddleInitial = $_POST['MInitial'];
                $NewEXTName = $_POST['EXTName'];
                $NewAltEmail = $_POST['altemail'];
                $NewSex = $_POST['sex'];
                $NewBday = $_POST['bday'];
                $AccStat = 'Enabled';
        
                if ($NewBday) {
                    $birthDate = new DateTime($NewBday);
                    $currentDate = new DateTime();
                    $NewAge = $currentDate->diff($birthDate)->y; // Calculate the age in years
                } else {
                    $NewAge = 0; // Set to 0 if no birthday is provided
                }

        // Your PHP logic goes here, for example, save the password to the database
        if ($User == "Student"){
            try{
                $NewCampus = $_POST['campus'];
                $NewDepartment = $_POST['department'];
                $NewCourse = $_POST['course'];
                $NewYS = $_POST['yearsection'];

                $pdoUserQuery = "UPDATE student_user 
                                SET first_name = :fname,
                                    last_name = :lname,
                                    middle_name = :mname,
                                    middle_initial = :mi,
                                    ext_name = :extname,
                                    birthday = :birthday,
                                    alt_email_address = :altemail,
                                    age = :age,
                                    sex = :sex,
                                    campus = :campus,
                                    department = :department,
                                    course = :course,
                                    year_section = :yearsection,
                                    account_status = :AccStat 
                                WHERE user_id = :number";

                $pdoResult = $pdoConnect->prepare($pdoUserQuery);
                $pdoResult->bindParam(':number', $id);
                $pdoResult->bindParam(':fname', $NewFirstName);
                $pdoResult->bindParam(':lname', $NewLastName);
                $pdoResult->bindParam(':mname', $NewMiddleName);
                $pdoResult->bindParam(':mi', $NewMiddleInitial);
                $pdoResult->bindParam(':extname', $NewEXTName);
                $pdoResult->bindParam(':altemail', $NewAltEmail);               
                $pdoResult->bindParam(':birthday', $NewBday);
                $pdoResult->bindParam(':age', $NewAge);
                $pdoResult->bindParam(':sex', $NewSex);

                $pdoResult->bindParam(':campus', $NewCampus);
                $pdoResult->bindParam(':department', $NewDepartment);
                $pdoResult->bindParam(':course', $NewCourse);
                $pdoResult->bindParam(':yearsection', $NewYS);

                $pdoResult->bindParam(':AccStat', $AccStat);
                $pdoResult->execute();
            
                    // Set a session variable to indicate successful update
                    $_SESSION['update_success'] = true;
            
                    // Redirect to the same page to prevent form resubmission
                    $_SESSION["user_id"] = $id;
                    $_SESSION["user_identity"] = "Student";
                    header("Location: ../User/dashboard.php");
                    unset($_SESSION['first-time']); // Invalidate the OTP
                    unset($_SESSION['address']);
                    unset($_SESSION['user']);
                    exit(); // Prevent further execution after redirection
            
                } catch (PDOException $e) {
                    // Handle database errors
                    echo "Error: " . $e->getMessage();
                    exit(); // Exit after handling the error
                }
        
            } elseif ($User == "Employee") {
                
                try{

                    $NewCampus = $_POST['campus'];
                    $NewDepartment = $_POST['department'];
                    
                    $pdoUserQuery = "UPDATE employee_user 
                                    SET first_name = :fname, 
                                        last_name = :lname, 
                                        middle_name = :mname, 
                                        middle_initial = :mi, 
                                        ext_name = :extname, 
                                        birthday = :birthday, 
                                        alt_email_address = :altemail, 
                                        age = :age, 
                                        sex = :sex, 
                                        campus = :campus,
                                        department = :department,
                                        account_status = :AccStat 
                                    WHERE user_id = :number";

                    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
                    $pdoResult->bindParam(':number', $id);
                    $pdoResult->bindParam(':fname', $NewFirstName);
                    $pdoResult->bindParam(':lname', $NewLastName);
                    $pdoResult->bindParam(':mname', $NewMiddleName);
                    $pdoResult->bindParam(':mi', $NewMiddleInitial);
                    $pdoResult->bindParam(':extname', $NewEXTName);
                    $pdoResult->bindParam(':altemail', $NewAltEmail);
                    $pdoResult->bindParam(':birthday', $NewBday);
                    $pdoResult->bindParam(':age', $NewAge);
                    $pdoResult->bindParam(':sex', $NewSex);

                    $pdoResult->bindParam(':department', $NewDepartment);
                    $pdoResult->bindParam(':course', $NewCourse);

                    $pdoResult->bindParam(':AccStat', $AccStat);
                    $pdoResult->execute();
                
                        // Set a session variable to indicate successful update
                        $_SESSION['update_success'] = true;
                
                        // Redirect to the same page to prevent form resubmission
                        $_SESSION["user_id"] = $id;
                        $_SESSION["user_identity"] = "Employee";
                        header("Location: ../User/dashboard.php");
                        unset($_SESSION['first-time']); // Invalidate the OTP
                        unset($_SESSION['address']);
                        unset($_SESSION['user']);
                        exit(); // Prevent further execution after redirection
                
                
                    } catch (PDOException $e) {
                        // Handle database errors
                        echo "Error: " . $e->getMessage();
                        exit(); // Exit after handling the error
                    }
            }
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HelpHub</title>
    <link  rel="stylesheet" href="fill-up-info.css">
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*"> 
    <style>
        body {
            background-image: url(../img/background.png);
        }
    </style>
</head>
<body>
    <!--<img class="logo" src="../img/MIS logo.png" alt="Image">-->

    <div class="login">
<form method="post">
        <h3>Personal Information</h3>
        <p>Check your information if they are correct and accurate. Update it if needed.</p>

    <div class="form-row">
        <div class="form-group">
            <label class="label">USER ID</label>
            <input class="form-control" name="" type="text" value="<?php echo $id?>" readonly>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="label">FIRST NAME</label>
            <input class="form-control" name="FName" id="firstNameInput" type="text" value="<?php echo $FirstName?>" required autocomplete="off" maxlength="100">
            <span id="firstNameError" style="color: red; font-size:smaller;"></span>
        </div>
    
        <div class="form-group">
            <label class="label">LAST NAME</label>
            <input class="form-control" name="LName" id="lastNameInput" type="text" value="<?php echo $LastName?>" required autocomplete="off" maxlength="50">
            <span id="lastNameError" style="color: red; font-size:smaller;"></span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="label">MIDDLE NAME</label>
            <input class="form-control" name="MName" id="middleNameInput" type="text" value="<?php echo $MiddleName?>" autocomplete="off" maxlength="50">
            <span id="middleNameError" style="color: red; font-size:smaller;"></span>
        </div>
    
        <div class="form-group">
            <label class="label">MIDDLE INITIAL</label>
            <input class="form-control" name="MInitial" id="middleInitialInput" type="text" value="<?php echo $MiddleInitial?>" autocomplete="off" maxlength="1">
            <span id="middleInitialError" style="color: red; font-size:smaller;"></span>
        </div>

        <div class="form-group">
            <label class="label">EXT. NAME</label>
            <input class="form-control" name="EXTName" id="extNameInput" type="text" value="<?php echo $ExtensionName?>" autocomplete="off" maxlength="2">
            <span id="extNameError" style="color: red; font-size:smaller;"></span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="label">EMAIL ADDRESS</label>
            <input class="form-control" name="" type="email" value="<?php echo $Email_Add?>" readonly>
        </div>

        <div class="form-group">
            <label class="label">ALTERNATIVE EMAIL ADDRESS</label>
            <input class="form-control" name="altemail" type="email" placeholder="Personal Email">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="label">SEX ASSIGNED AT BIRTH</label>
            <select class="form-control" name="sex" id="genderDropdown" required>
                <option value="Male" <?php echo ($Sex == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($Sex == 'Female') ? 'selected' : ''; ?>>Female</option>
            </select>
        </div>

        <div class="form-group">
            <label class="label">BIRTHDAY</label>
            <input class="form-control" name="bday" id="bdayInput" type="date" value="<?php echo $Bday?>" required>
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
    
    <div class="form-row">
        <div class="form-group">
            <label class="label">CAMPUS</label>
            <?php if (!empty($Campus)): ?>
                <input class="form-control" name="campus" type="text" value="<?php echo $Campus ?>" readonly>
            <?php else: ?>
                <select type="text" name="campus" id="campusDropdown" class="form-control" required>

                </select>
            <?php endif; ?>
        </div>
    
        <div class="form-group">
            <label class="label">DEPARTMENT</label>
            <?php if (!empty($Campus)): ?>
                <input class="form-control" name="department" type="text" value="<?php echo $Department ?>" readonly>
                <select type="text" name="" id="categoryDropdown" class="form-control" required hidden>

                </select>
            <?php else: ?>

                <select type="text" name="department" id="categoryDropdown" class="form-control" required>

                </select>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ( $User === 'Student'): ?>
    <div class="form-row">
        <div class="form-group">
            <label class="label">COURSE</label>
            <?php if (!empty($Course)): ?>
                <input class="form-control" name="course" type="text" value="<?php echo $Course?>" readonly>
            <?php else: ?>
                <select type="text" name="course" id="itemDropdown" class="form-control" required>

                </select>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="label">YEAR AND SECTION</label>
            <?php if (!empty($Course)): ?>
                <input class="form-control" name="" type="text" value="<?php echo $Y_S?>" readonly>
            <?php else: ?>
                <input class="form-control" name="yearsection" type="text" placeholder="ex. 1A" required>
            <?php endif; ?>
        </div>  
    </div>
    <?php endif; ?>

        <div class="form-group">
            <input type="checkbox" id="TC" name="TC" required>
            <label for="TC" class="label">I accept the <a>Terms & Conditions</a> and understand that my acceptance is required to proceed.</label>

        </div>
        <input type="submit" name="information" value="Confirm"  ><br>
        
</form>
    </div>
<script src="script.js"></script>


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
const fetchCampus = "<?php echo $Campus; ?>"; // Replace with your method to get the PHP variable

// Call the function to populate the category dropdown
populateCampus('../Admin/txt/campus.txt', 'campusDropdown', fetchCampus);



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

    // Function to populate a dropdown from a specific section of a text file
    function populateDropdownFromSection(fileName, dropdownId, sectionMarker, selectedCourse) {
        const url = fileName + '?v=' + new Date().getTime();
        // Fetch the text file
        fetch(url)
            .then(response => response.text())
            .then(data => {
                const lines = data.split('\n');
                let isInSection = false;
                const options = [];

                // Iterate over each line to find the desired section
                lines.forEach(line => {
                    const trimmedLine = line.trim();

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
                if (dropdown) {
                    // Clear existing options in the dropdown
                    dropdown.innerHTML = '';

                    // Populate dropdown with options from the section
                    options.forEach(option => {
                        const opt = document.createElement('option');
                        opt.value = option;
                        opt.textContent = option;
                        dropdown.appendChild(opt);
                    });

                    // Select the option based on selectedCourse
                    if (selectedCourse) {
                        dropdown.value = selectedCourse; // Set the selected option
                    }
                } else {
                    console.warn(`Dropdown with ID ${dropdownId} not found.`);
                }
            })
            .catch(error => console.error(`Error fetching the text file (${fileName}):`, error));
    }

    // Function to handle category change
    function handleCategoryChange() {
        const categoryDropdown = document.getElementById('categoryDropdown');
        const selectedCategory = categoryDropdown.value;
        const sectionMarker = `# ${selectedCategory}`;

        // Assuming you have a PHP variable $FetchCourse, output it to JavaScript
        const fetchCourse = "<?php echo $Course; ?>"; // Replace with your method to get the PHP variable

        // Populate the second dropdown based on the selected category
        console.log(`Populating itemDropdown for category: ${selectedCategory} with sectionMarker: ${sectionMarker}`);
        populateDropdownFromSection('../Admin/txt/course.txt', 'itemDropdown', sectionMarker, fetchCourse);
    }

    // Assuming you have a PHP variable $FetchDepartment, output it to JavaScript
    const fetchDepartment = "<?php echo $Department; ?>"; // Replace with your method to get the PHP variable

    // Call the function to populate the category dropdown
    populateDropdown('../Admin/txt/department.txt', 'categoryDropdown', fetchDepartment);

    // Event listener for category dropdown change
    document.getElementById('categoryDropdown').addEventListener('change', handleCategoryChange);

    // Initial population based on the default selection
    document.addEventListener('DOMContentLoaded', handleCategoryChange);

</script>
</body>
</html>