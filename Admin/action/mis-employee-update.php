<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $imgContent = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            
            // Validate file size (6MB)
            $maxSize = 6 * 1024 * 1024;
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
        }

        $pdoConnect->beginTransaction();

        // Collect form data
        $admin_number = $_POST['username'];
        $password = !empty($_POST['password']) ? $_POST['password'] : null;
        $f_name = $_POST['firstname'] ?? null;
        $l_name = $_POST['lastname'] ?? null;
        $m_name = $_POST['middlename'] ?? null;
        $m_initial = $_POST['middleinitial'] ?? null;
        $ext_name = $_POST['extname'] ?? null;
        $position = $_POST['position'] ?? null;
        $user_type = $_POST['type'] ?? null;
        $email_address = $_POST['email'] ?? null;
        $alt_email_address = $_POST['altemail'] ?? null;
        $birthday = $_POST['birthday'] ?? null;
        $age = $birthday ? (new DateTime($birthday))->diff(new DateTime())->y : 0;
        $sex = $_POST['sex'] ?? null;

        // Prepare fields to update dynamically
        $fieldsToUpdate = [
            "f_name = :f_name",
            "l_name = :l_name",
            "m_name = :m_name", 
            "m_initial = :m_initial", 
            "ext_name = :ext_name",
            "position = :position",
            "user_type = :user_type",
            "email_address = :email_address",
            "alt_email_address = :alt_email_address",
            "birthday = :birthday",
            "age = :age",
            "sex = :sex"
        ];

        if ($password !== null) {
            $fieldsToUpdate[] = "password = :password";
        }

        if ($imgContent !== null) {
            $fieldsToUpdate[] = "profile_picture = :profile_picture";
        }

        $sql = "UPDATE mis_employees SET " . implode(", ", $fieldsToUpdate) . " WHERE admin_number = :admin_number";

        $stmt = $pdoConnect->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':admin_number', $admin_number, PDO::PARAM_STR);
        $stmt->bindParam(':f_name', $f_name, PDO::PARAM_STR);
        $stmt->bindParam(':l_name', $l_name, PDO::PARAM_STR);
        $stmt->bindParam(':m_name', $m_name, PDO::PARAM_STR);
        $stmt->bindParam(':m_initial', $m_initial, PDO::PARAM_STR);
        $stmt->bindParam(':ext_name', $ext_name, PDO::PARAM_STR);
        $stmt->bindParam(':position', $position, PDO::PARAM_STR);
        $stmt->bindParam(':user_type', $user_type, PDO::PARAM_STR);
        $stmt->bindParam(':email_address', $email_address, PDO::PARAM_STR);
        $stmt->bindParam(':alt_email_address', $alt_email_address, PDO::PARAM_STR);
        $stmt->bindParam(':birthday', $birthday, PDO::PARAM_STR);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':sex', $sex, PDO::PARAM_STR);

        if ($password !== null) {
            $hashPassword = password_hash($password, PASSWORD_ARGON2I);
            $stmt->bindParam(':password', $hashPassword, PDO::PARAM_STR);
        }

        if ($imgContent !== null) {
            $stmt->bindParam(':profile_picture', $imgContent, PDO::PARAM_LOB);
        }

        if ($stmt->execute()) {
            $pdoConnect->commit();
            header("Location: ../employee.php");
            exit();
        } else {
            $pdoConnect->rollBack();
            $_SESSION['error_message'] = "Failed to update employee record.";
            header("Location: ../add-employee.php");
            exit();
        }
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Connection failed: " . $e->getMessage();
    if ($pdoConnect->inTransaction()) {
        $pdoConnect->rollBack();
    }
    header("Location: ../add-employee.php");
    exit();
}

$pdoConnect = null;
?>
