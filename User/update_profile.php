<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

//Updates the Balance Sheet
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["user_id"];
    $identity = $_SESSION["user_identity"];

if (isset($_POST['update'])) {

    if ($identity == "Student"){

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        
        // Validate file size (6MB)
        $maxSize = 6 * 1024 * 1024; // 6MB in bytes
        if ($image['size'] > $maxSize) {
            echo "File size exceeds 6MB limit.";
            exit;
        }

        // Validate file type
        $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        $fileType = mime_content_type($image['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            echo "Only PNG, JPG, and JPEG files are allowed.";
            exit;
        }

        $imgContent = file_get_contents($image['tmp_name']);

    try{
    $NewName = $_POST['name'];
    $NewSex = $_POST['sex'];
    $NewBday = $_POST['bday'];
    $NewCampus = $_POST['campus'];
    $NewDept = $_POST['dept'];
    $NewCourse = $_POST['course'];
    $NewYS = $_POST['ys'];

    if ($NewBday) {
        $birthDate = new DateTime($NewBday);
        $currentDate = new DateTime();
        $NewAge = $currentDate->diff($birthDate)->y; // Calculate the age in years
    } else {
        $NewAge = 0; // Set to 0 if no birthday is provided
    }

    $pdoUserQuery = "UPDATE student_user SET name = :name, birthday = :birthday, age = :age, campus = :campus, department = :department, course = :course, year_section = :year_section, profile_picture = :P_P, sex = :sex WHERE user_id = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->bindParam(':name', $NewName);
    $pdoResult->bindParam(':birthday', $NewBday);
    $pdoResult->bindParam(':age', $NewAge);
    $pdoResult->bindParam(':campus', $NewCampus);
    $pdoResult->bindParam(':department', $NewDept);
    $pdoResult->bindParam(':course', $NewCourse);
    $pdoResult->bindParam(':year_section', $NewYS);
    $pdoResult->bindParam(':P_P', $imgContent);
    $pdoResult->bindParam(':sex', $NewSex);
    $pdoResult->execute();

        // Set a session variable to indicate successful update
        $_SESSION['update_success'] = true;

        // Redirect to the same page to prevent form resubmission
        header("location: profile.php");
        exit();


    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
        exit(); // Exit after handling the error
    }

} else {
    try{
        $NewName = $_POST['name'];
        $NewSex = $_POST['sex'];
        $NewBday = $_POST['bday'];
        $NewCampus = $_POST['campus'];
        $NewDept = $_POST['dept'];
        $NewCourse = $_POST['course'];
        $NewYS = $_POST['ys'];

        if ($NewBday) {
            $birthDate = new DateTime($NewBday);
            $currentDate = new DateTime();
            $NewAge = $currentDate->diff($birthDate)->y; // Calculate the age in years
        } else {
            $NewAge = 0; // Set to 0 if no birthday is provided
        }
    
        $pdoUserQuery = "UPDATE student_user SET name = :name, birthday = :birthday, age = :age, campus = :campus, department = :department, course = :course, year_section = :year_section, sex = :sex WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->bindParam(':name', $NewName);
        $pdoResult->bindParam(':birthday', $NewBday);
        $pdoResult->bindParam(':age', $NewAge);
        $pdoResult->bindParam(':campus', $NewCampus);
        $pdoResult->bindParam(':department', $NewDept);
        $pdoResult->bindParam(':course', $NewCourse);
        $pdoResult->bindParam(':year_section', $NewYS);
        $pdoResult->bindParam(':sex', $NewSex);
        $pdoResult->execute();
    
            // Set a session variable to indicate successful update
            $_SESSION['update_success'] = true;
    
            // Redirect to the same page to prevent form resubmission
            header("location: profile.php");
            exit();
    
    
        } catch (PDOException $e) {
            // Handle database errors
            echo "Error: " . $e->getMessage();
            exit(); // Exit after handling the error
        }
}
    } elseif ($identity == "Employee") {
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = $_FILES['image'];
            
            // Validate file size (6MB)
            $maxSize = 6 * 1024 * 1024; // 6MB in bytes
            if ($image['size'] > $maxSize) {
                echo "File size exceeds 6MB limit.";
                exit;
            }
    
            // Validate file type
            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            $fileType = mime_content_type($image['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                echo "Only PNG, JPG, and JPEG files are allowed.";
                exit;
            }
    
            $imgContent = file_get_contents($image['tmp_name']);
    
        try{
        $NewName = $_POST['name'];
        $NewSex = $_POST['sex'];
        $NewBday = $_POST['bday'];
        $NewCampus = $_POST['campus'];
        $NewDept = $_POST['dept'];
        $NewCourse = $_POST['course'];
        $NewYS = $_POST['ys'];

        if ($NewBday) {
            $birthDate = new DateTime($NewBday);
            $currentDate = new DateTime();
            $NewAge = $currentDate->diff($birthDate)->y; // Calculate the age in years
        } else {
            $NewAge = 0; // Set to 0 if no birthday is provided
        }
    
        $pdoUserQuery = "UPDATE employee_user SET name = :name, birthday = :birthday, age = :age, campus = :campus, department = :department, course = :course, year_section = :year_section, profile_picture = :P_P, sex = :sex WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->bindParam(':name', $NewName);
        $pdoResult->bindParam(':birthday', $NewBday);
        $pdoResult->bindParam(':age', $NewAge);
        $pdoResult->bindParam(':campus', $NewCampus);
        $pdoResult->bindParam(':department', $NewDept);
        $pdoResult->bindParam(':course', $NewCourse);
        $pdoResult->bindParam(':year_section', $NewYS);
        $pdoResult->bindParam(':P_P', $imgContent);
        $pdoResult->bindParam(':sex', $NewSex);
        $pdoResult->execute();
    
            // Set a session variable to indicate successful update
            $_SESSION['update_success'] = true;
    
            // Redirect to the same page to prevent form resubmission
            header("location: profile.php");
            exit();
    
    
        } catch (PDOException $e) {
            // Handle database errors
            echo "Error: " . $e->getMessage();
            exit(); // Exit after handling the error
        }
    
    } else {
        try{
            $NewName = $_POST['name'];
            $NewSex = $_POST['sex'];
            $NewBday = $_POST['bday'];
            $NewCampus = $_POST['campus'];
            $NewDept = $_POST['dept'];

            if ($NewBday) {
                $birthDate = new DateTime($NewBday);
                $currentDate = new DateTime();
                $NewAge = $currentDate->diff($birthDate)->y; // Calculate the age in years
            } else {
                $NewAge = 0; // Set to 0 if no birthday is provided
            }
        
            $pdoUserQuery = "UPDATE employee_user SET name = :name, birthday = :birthday, age = :age, campus = :campus, department = :department, sex = :sex WHERE user_id = :number";
            $pdoResult = $pdoConnect->prepare($pdoUserQuery);
            $pdoResult->bindParam(':number', $id);
            $pdoResult->bindParam(':name', $NewName);
            $pdoResult->bindParam(':birthday', $NewBday);
            $pdoResult->bindParam(':age', $NewAge);
            $pdoResult->bindParam(':campus', $NewCampus);
            $pdoResult->bindParam(':department', $NewDept);
            $pdoResult->bindParam(':sex', $NewSex);
            $pdoResult->execute();
        
                // Set a session variable to indicate successful update
                $_SESSION['update_success'] = true;
        
                // Redirect to the same page to prevent form resubmission
                header("location: profile.php");
                exit();
        
        
            } catch (PDOException $e) {
                // Handle database errors
                echo "Error: " . $e->getMessage();
                exit(); // Exit after handling the error
            }
    }
    }

}
}