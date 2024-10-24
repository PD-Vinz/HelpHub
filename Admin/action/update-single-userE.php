<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user_id = $_POST['userid']; // Ensure you have the user ID to fetch the existing image
        
        // Retrieve the current image from the database
        $stmt = $pdoConnect->prepare("SELECT profile_picture FROM student_user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $existingImage = $stmt->fetchColumn();

        // Initialize the image content
        $imgContent = $existingImage ? $existingImage : file_get_contents(__DIR__ . '/No-Profile.png');

        // Check if a file is uploaded and there's no upload error
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
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

            // Get the image content
            $imgContent = file_get_contents($image['tmp_name']);
        }

        $name = $_POST['name'];
        $department = $_POST['department'];
        $email_address = $_POST['email'];
        $campus = $_POST['campus'];    
        $sex = $_POST['sex'];
        $birthday = $_POST['birthday'];

        if ($birthday) {
            $birthDate = new DateTime($birthday);
            $currentDate = new DateTime();
            $age = $currentDate->diff($birthDate)->y; // Calculate the age in years
        } else {
            $age = 0; // Set to 0 if no birthday is provided
        }

        // Start a transaction
        $pdoConnect->beginTransaction();
        
        // Prepare an update statement
        $stmt = $pdoConnect->prepare("UPDATE `employee_user` SET
                                        `name` = :name,
                                        `department` = :department,
                                        `email_address` = :email_address,
                                        `campus` = :campus,
                                        `sex` = :sex,
                                        `age` = :age,
                                        `birthday` = :birthday" . 
                                        (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK ? ", `profile_picture` = :profile_picture" : "") . 
                                        " WHERE `user_id` = :user_id");
        
        // Bind the parameters
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':email_address', $email_address);
        $stmt->bindParam(':campus', $campus);
        $stmt->bindParam(':sex', $sex);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':birthday', $birthday);

        // Bind the profile picture only if a new image is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $stmt->bindParam(':profile_picture', $imgContent, PDO::PARAM_LOB);
        }

        // Execute the statement
        if ($stmt->execute()) {
            // Commit the transaction
            $pdoConnect->commit();
            header("Location:../user-employee-list.php");
            exit();
        } else {
            // Roll back the transaction on failure
            $pdoConnect->rollBack();
            header("Location: ../user-employee-list.php");
            exit();
        }
    }

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    echo "<a href='../add-user-employee.php'>Back</a>";

    // Roll back the transaction on exception
    if ($pdoConnect->inTransaction()) {
        $pdoConnect->rollBack();
    }
}

// Close the connection
$pdoConnect = null;
