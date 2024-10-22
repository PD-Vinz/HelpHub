<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        } else {
            // If no new image, retain the current one
            // You may want to retrieve the current image from the database here
            $user_id = $_POST['userid']; // Ensure you have the user ID to fetch the existing image
            $stmt = $pdoConnect->prepare("SELECT profile_picture FROM student_user WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $existingImage = $stmt->fetchColumn();

            // Use the existing image content if no new image was uploaded
            $imgContent = $existingImage ? $existingImage : file_get_contents(__DIR__ . '/No-Profile.png');
        }

        $user_id = $_POST['userid'];
        $name = $_POST['name'];
        $department = $_POST['department'];
        $year_section = $_POST['year_section'];
        $email_address = $_POST['email'];
        $campus = $_POST['campus'];
        $course = $_POST['course'];
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
        $stmt = $pdoConnect->prepare("UPDATE `student_user` SET 
                                        `name` = :name,
                                        `department` = :department,
                                        `year_section` = :year_section,
                                        `email_address` = :email_address,
                                        `campus` = :campus,
                                        `course` = :course,
                                        `sex` = :sex,
                                        `age` = :age,
                                        `birthday` = :birthday,
                                        `profile_picture` = :profile_picture
                                        WHERE `user_id` = :user_id");
        
        // Bind the parameters
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':department', $department, PDO::PARAM_STR);
        $stmt->bindParam(':year_section', $year_section, PDO::PARAM_STR);
        $stmt->bindParam(':email_address', $email_address, PDO::PARAM_STR);
        $stmt->bindParam(':campus', $campus, PDO::PARAM_STR);
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->bindParam(':sex', $sex, PDO::PARAM_STR);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':birthday', $birthday, PDO::PARAM_STR);
        $stmt->bindParam(':profile_picture', $imgContent, PDO::PARAM_LOB);

        // Execute the statement
        if ($stmt->execute()) {
            // Commit the transaction
            $pdoConnect->commit();
            header("Location: ../user-student-list.php");
            exit();
        } else {
            // Roll back the transaction on failure
            $pdoConnect->rollBack();
            header("Location: ../user-student-list.php");
            exit();
        }
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    echo "<a href='../add-user-student.php'>Back</a>";

    // Roll back the transaction on exception
    if ($pdoConnect->inTransaction()) {
        $pdoConnect->rollBack();
    }
}

// Close the connection
$pdoConnect = null;
