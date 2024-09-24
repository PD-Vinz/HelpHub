<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
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
            
            // Process image content as needed
            // For example, save it to a file or database
        } else {
            define('DEFAULT_PHOTO', __DIR__ . '/No-Profile.png');
            $noimage = DEFAULT_PHOTO;
            $imgContent = file_get_contents($noimage);
        }


            $admin_number = $_POST['userid'];
            $password = $_POST['password'];
            $f_name = $_POST['firstname'];
            $l_name = $_POST['lastname'];
            $position = $_POST['position'];
            $user_type = $_POST['type'];
            $email_address = $_POST['email'];
            $birthday = $_POST['birthday'];
            $age = $_POST['age'];
            $sex = $_POST['sex'];

            // Start a transaction
            $pdoConnect->beginTransaction();
            
            // Prepare an insert statement
            $stmt = $pdoConnect->prepare("INSERT INTO `mis_employees`(`admin_number`, `password`, `f_name`, `l_name`, `position`, `user_type`, `email_address`, `birthday`, `age`, `sex`, `profile_picture`) 
                                        VALUES (:admin_number,:password,:f_name,:l_name,:position,:user_type,:email_address,:birthday,:age,:sex,:profile_picture)");
            // Bind the blob data

            $stmt->bindParam(':admin_number', $admin_number, PDO::PARAM_LOB);
            $stmt->bindParam(':password', $password, PDO::PARAM_LOB);
            $stmt->bindParam(':f_name', $f_name, PDO::PARAM_LOB);
            $stmt->bindParam(':l_name', $l_name, PDO::PARAM_LOB);
            $stmt->bindParam(':position', $position, PDO::PARAM_LOB);
            $stmt->bindParam(':user_type', $user_type, PDO::PARAM_LOB);
            $stmt->bindParam(':email_address', $email_address, PDO::PARAM_LOB);
            $stmt->bindParam(':birthday', $birthday, PDO::PARAM_LOB);
            $stmt->bindParam(':age', $age, PDO::PARAM_LOB);
            $stmt->bindParam(':sex', $sex, PDO::PARAM_LOB);
            $stmt->bindParam(':profile_picture', $imgContent, PDO::PARAM_LOB);


            // Execute the statement
            if ($stmt->execute()) {
                    // Commit the transaction
                    $pdoConnect->commit();

                header("Location:../employee.php");
                exit();
            } else {
                // Roll back the transaction on failure
                $pdoConnect->rollBack();
                header("Location: ../employee.php");
                exit();
            }
        
    }


} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    echo "<a href='../add-employee.php'>Back</a>";

    // Roll back the transaction on exception
    if ($pdoConnect->inTransaction()) {
        $pdoConnect->rollBack();
    }
}

// Close the connection
$pdoConnect = null;
