<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

//Updates the Balance Sheet
if (!isset($_SESSION["admin_number"])) {
    header("Location: ../../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["admin_number"];

if (isset($_POST['update'])) {
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
    $NewFName = $_POST['fname'];
    $NewLName = $_POST['lname'];
    $NewSex = $_POST['sex'];
    $NewBday = $_POST['bday'];
    $NewEmail = $_POST['emailadd'];

    if ($NewBday) {
        $birthDate = new DateTime($NewBday);
        $currentDate = new DateTime();
        $NewAge = $currentDate->diff($birthDate)->y; // Calculate the age in years
    } else {
        $NewAge = 0; // Set to 0 if no birthday is provided
    }
                                                                                                                                                    
    $pdoUserQuery = "UPDATE mis_employees SET f_name = :fname, l_name = :lname, email_address = :email, birthday = :birthday, age = :age, profile_picture = :P_P, sex = :sex WHERE admin_number = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->bindParam(':fname', $NewFName);
    $pdoResult->bindParam(':lname', $NewLName);
    $pdoResult->bindParam(':email', $NewEmail);
    $pdoResult->bindParam(':birthday', $NewBday);
    $pdoResult->bindParam(':age', $NewAge);
    $pdoResult->bindParam(':P_P', $imgContent);
    $pdoResult->bindParam(':sex', $NewSex);
    $pdoResult->execute();

        // Set a session variable to indicate successful update
        $_SESSION['update_success'] = true;

        // Redirect to the same page to prevent form resubmission
        header("location: ../profile.php");
        exit();


    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
        exit(); // Exit after handling the error
    }

} else {
    try{
        $NewFName = $_POST['fname'];
        $NewLName = $_POST['lname'];
        $NewSex = $_POST['sex'];
        $NewBday = $_POST['bday'];
        $NewEmail = $_POST['emailadd'];

        if ($NewBday) {
            $birthDate = new DateTime($NewBday);
            $currentDate = new DateTime();
            $NewAge = $currentDate->diff($birthDate)->y; // Calculate the age in years
        } else {
            $NewAge = 0; // Set to 0 if no birthday is provided
        }
                                                                                                                                                    
        $pdoUserQuery = "UPDATE mis_employees SET f_name = :fname, l_name = :lname, email_address = :email, birthday = :birthday, age = :age, sex = :sex WHERE admin_number = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->bindParam(':fname', $NewFName);
        $pdoResult->bindParam(':lname', $NewLName);
        $pdoResult->bindParam(':email', $NewEmail);
        $pdoResult->bindParam(':birthday', $NewBday);
        $pdoResult->bindParam(':age', $NewAge);
        $pdoResult->bindParam(':sex', $NewSex);
        $pdoResult->execute();
    
            // Set a session variable to indicate successful update
            $_SESSION['update_success'] = true;
    
            // Redirect to the same page to prevent form resubmission
            header("location: ../profile.php");
            exit();
    
    
        } catch (PDOException $e) {
            // Handle database errors
            echo "Error: " . $e->getMessage();
            exit(); // Exit after handling the error
        }

    
    }
}
}