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
            $Name = $Data['name'];
            $Campus = $Data['campus'];
            $Department = $Data['department'];
            $Course = $Data['course'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
            $Sex = $Data['sex'];
            $Age = $Data['age'];
            $Bday = $Data['birthday'];
            $UserType = $Data['user_type'];
    
            $nameParts = explode(' ', $Name);
            $firstName = $nameParts[0];
    
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
            $Name = $Data['name'];
            $Campus = $Data['campus'];
            $Department = $Data['department'];
            $Course = $Data['course'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
            $Sex = $Data['sex'];
            $Age = $Data['age'];
            $Bday = $Data['birthday'];
            $UserType = $Data['user_type'];
    
            $nameParts = explode(' ', $Name);
            $firstName = $nameParts[0];
    
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
        // Get the submitted password values

        // Your PHP logic goes here, for example, save the password to the database
        if ($User == "Student"){
            try{
                $NewName = $_POST['name'];
                $NewAltEmail = $_POST['altemail'];
                $NewSex = $_POST['sex'];
                $NewBday = $_POST['bday'];
        
                if ($NewBday) {
                    $birthDate = new DateTime($NewBday);
                    $currentDate = new DateTime();
                    $NewAge = $currentDate->diff($birthDate)->y; // Calculate the age in years
                } else {
                    $NewAge = 0; // Set to 0 if no birthday is provided
                }
            
                $pdoUserQuery = "UPDATE student_user SET name = :name, birthday = :birthday, alt_email_address = :altemail, age = :age, sex = :sex WHERE user_id = :number";
                $pdoResult = $pdoConnect->prepare($pdoUserQuery);
                $pdoResult->bindParam(':number', $id);
                $pdoResult->bindParam(':name', $NewName);
                $pdoResult->bindParam(':altemail', $NewAltEmail);
                $pdoResult->bindParam(':birthday', $NewBday);
                $pdoResult->bindParam(':age', $NewAge);
                $pdoResult->bindParam(':sex', $NewSex);
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
        }
            } elseif ($User == "Employee") {
                
                try{
                    $NewName = $_POST['name'];
                    $NewAltEmail = $_POST['altemail'];
                    $NewSex = $_POST['sex'];
                    $NewBday = $_POST['bday'];
        
                    if ($NewBday) {
                        $birthDate = new DateTime($NewBday);
                        $currentDate = new DateTime();
                        $NewAge = $currentDate->diff($birthDate)->y; // Calculate the age in years
                    } else {
                        $NewAge = 0; // Set to 0 if no birthday is provided
                    }
                
                    $pdoUserQuery = "UPDATE employee_user SET name = :name, birthday = :birthday, alt_email_address = :altemail, age = :age, sex = :sex WHERE user_id = :number";
                    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
                    $pdoResult->bindParam(':number', $id);
                    $pdoResult->bindParam(':name', $NewName);
                    $pdoResult->bindParam(':altemail', $NewAltEmail);
                    $pdoResult->bindParam(':birthday', $NewBday);
                    $pdoResult->bindParam(':age', $NewAge);
                    $pdoResult->bindParam(':sex', $NewSex);
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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HelpHub</title>
    <link  rel="stylesheet" href="fill-up-info.css">
    <link rel="icon" href="../img/logo.png" type="image/png">
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
            <label class="label">User ID</label>
            <input class="form-control" name="" type="text" value="<?php echo $id?>" readonly>
        </div>
    
        <div class="form-group">
            <label class="label">NAME</label>
            <input class="form-control" name="name" type="text" value="<?php echo $Name?>" required>
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
            <label class="label">GENDER</label>
            <select class="form-control" name="sex" id="genderDropdown" required>
                <option value="Male" <?php echo ($Sex == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($Sex == 'Female') ? 'selected' : ''; ?>>Female</option>
            </select>
        </div>

            <!--
            <select class="form-control" name="id" type="text" value="<?php //echo $Sex?>" required>
                <option value="Male" <?php //echo ($Sex == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php //echo ($Sex == 'Female') ? 'selected' : ''; ?>>Female</option>
            -->

        <div class="form-group">
            <label class="label">BIRTHDAY</label>
            <input class="form-control" name="bday" type="date" value="<?php echo $Bday?>" required>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="label">CAMPUS</label>
            <input class="form-control" name="" type="text" value="<?php echo $Campus?>" readonly>
        </div>
    
        <div class="form-group">
            <label class="label">DEPARTMENT</label>
            <input class="form-control" name="" type="text" value="<?php echo $Department?>" readonly>
        </div>
    </div>
    
    <?php if ( $User === 'Student'): ?>
    <div class="form-row">
        <div class="form-group">
            <label class="label">COURSE</label>
            <input class="form-control" name="" type="text" value="<?php echo $Course?>" readonly>
        </div>

        <div class="form-group">
            <label class="label">YEAR AND SECTION</label>
            <input class="form-control" name="" type="text" value="<?php echo $Y_S?>" readonly>
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

</body>
</html>