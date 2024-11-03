<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Handle the profile image upload or use a default image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            
            // Validate file size (6MB)
            $maxSize = 6 * 1024 * 1024; // 6MB in bytes
            if ($image['size'] > $maxSize) {
                $_SESSION['error_message'] = "File size exceeds 6MB limit.";
                header("Location: ../add-employee.php");
                exit();
            }
        
            // Validate file type
            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            $fileType = mime_content_type($image['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                $_SESSION['error_message'] = "Only PNG, JPG, and JPEG files are allowed.";
                header("Location: ../add-employee.php");
                exit();
            }
        
            $imgContent = file_get_contents($image['tmp_name']);
            
        } else {
            // Use default image if none is uploaded
            define('DEFAULT_PHOTO', __DIR__ . '/No-Profile.png');
            $imgContent = file_get_contents(DEFAULT_PHOTO);
        }

        // Sanitize and retrieve form inputs
        $admin_number = $_POST['userid'];
        $password = $_POST['password'];
        $NewFName = htmlspecialchars($_POST['first_name']);
        $NewLName = htmlspecialchars($_POST['last_name']);
        $NewMName = htmlspecialchars($_POST['middle_name'] ?? null);
        $NewMInitial = htmlspecialchars($_POST['middle_initial'] ?? null);
        $NewEXTName = htmlspecialchars($_POST['ext_name'] ?? null);
        $position = htmlspecialchars($_POST['position']);
        $user_type = htmlspecialchars($_POST['type']);
        $email_address = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $alt_email_address = filter_var($_POST['altemail'] ?? null, FILTER_VALIDATE_EMAIL);
        $birthday = $_POST['birthday'];
        $sex = $_POST['sex'];
        $AccStat = 'Enabled';

        // Hash the password
        $hashPassword = password_hash($password, PASSWORD_ARGON2I);

        // Calculate age
        $age = $birthday ? (new DateTime($birthday))->diff(new DateTime())->y : 0;

        // Start a transaction
        $pdoConnect->beginTransaction();
        
        // Prepare the insert statement
        $stmt = $pdoConnect->prepare("INSERT INTO mis_employees 
                                        (admin_number, password, f_name, l_name, m_name, m_initial, ext_name, position, user_type, email_address, alt_email_address, birthday, age, sex, profile_picture, account_status) 
                                        VALUES (:admin_number, :password, :f_name, :l_name, :m_name, :m_initial, :ext_name, :position, :user_type, :email_address, :alt_email_address, :birthday, :age, :sex, :profile_picture, :accstat)");

        // Bind parameters
        $stmt->bindParam(':admin_number', $admin_number, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashPassword, PDO::PARAM_STR);
        $stmt->bindParam(':f_name', $NewFName, PDO::PARAM_STR);
        $stmt->bindParam(':l_name', $NewLName, PDO::PARAM_STR);
        $stmt->bindParam(':m_name', $NewMName, PDO::PARAM_STR);
        $stmt->bindParam(':m_initial', $NewMInitial, PDO::PARAM_STR);
        $stmt->bindParam(':ext_name', $NewEXTName, PDO::PARAM_STR);
        $stmt->bindParam(':position', $position, PDO::PARAM_STR);
        $stmt->bindParam(':user_type', $user_type, PDO::PARAM_STR);
        $stmt->bindParam(':email_address', $email_address, PDO::PARAM_STR);
        $stmt->bindParam(':alt_email_address', $alt_email_address, PDO::PARAM_STR);
        $stmt->bindParam(':birthday', $birthday, PDO::PARAM_STR);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':sex', $sex, PDO::PARAM_STR);
        $stmt->bindParam(':profile_picture', $imgContent, PDO::PARAM_LOB);
        $stmt->bindParam(':accstat', $AccStat, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            $pdoConnect->commit();
            header("Location: ../employee.php");
            exit();
        } else {
            $pdoConnect->rollBack();
            $_SESSION['error_message'] = "Failed to add employee.";
            header("Location: ../add-employee.php");
            exit();
        }
        
    }

} catch(PDOException $e) {
    $_SESSION['error_message'] = "Connection failed: " . $e->getMessage();
    header("Location: ../add-employee.php");
    exit();
}

// Close the connection
$pdoConnect = null;
