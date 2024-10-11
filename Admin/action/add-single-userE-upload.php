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


            $user_id = $_POST['userid'];
            $password = $_POST['password'];
            $name = $_POST['name'];
            $department = $_POST['department'];
    
            $email_address = $_POST['email'];
            $campus = $_POST['campus'];
     
            $sex = $_POST['sex'];
            $age = $_POST['age'];
            $birthday = $_POST['birthday'];

            // Start a transaction
            $pdoConnect->beginTransaction();
            
            // Prepare an insert statement
            $stmt = $pdoConnect->prepare("INSERT INTO `employee_user`(`user_id`, `password`, `name`,`department`,`email_address` ,`campus` ,`sex` ,`age`,`birthday`,`profile_picture`) 
                                        VALUES (:user_id,:password,:name,:department,:email_address,:campus,:sex,:age,:birthday,:profile_picture)");
            // Bind the blob data

            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_LOB);
            $stmt->bindParam(':password', $user_id, PDO::PARAM_LOB);
            $stmt->bindParam(':name', $name, PDO::PARAM_LOB);
            $stmt->bindParam(':department', $department, PDO::PARAM_LOB);

            $stmt->bindParam(':email_address', $email_address, PDO::PARAM_LOB);
            $stmt->bindParam(':campus', $campus, PDO::PARAM_LOB);

            $stmt->bindParam(':sex', $sex, PDO::PARAM_LOB);
            $stmt->bindParam(':age', $age, PDO::PARAM_LOB);
            $stmt->bindParam(':birthday', $birthday, PDO::PARAM_LOB);
            $stmt->bindParam(':profile_picture', $imgContent, PDO::PARAM_LOB);


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
