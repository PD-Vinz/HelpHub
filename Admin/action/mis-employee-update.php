<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $imgContent = null; // Initialize to null by default
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            
            // Validate file size (6MB)
            $maxSize = 6 * 1024 * 1024; // 6MB in bytes
            if ($image['size'] > $maxSize) {
                echo "File size exceeds 6MB limit.";
                exit();
            }
        
            // Validate file type
            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            $fileType = mime_content_type($image['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                echo "Only PNG, JPG, and JPEG files are allowed.";
                exit();
            }

            // Read the image content
            $imgContent = file_get_contents($image['tmp_name']);
        }

        // Start a transaction
        $pdoConnect->beginTransaction();

        // Collect form data
        $admin_number = $_POST['username'];
        $password = !empty($_POST['password']) ? $_POST['password'] : null; // Conditional for password
        $f_name = $_POST['firstname'] ?? null;
        $l_name = $_POST['lastname'] ?? null;
        $position = $_POST['position'] ?? null;
        $user_type = $_POST['type'] ?? null;
        $email_address = $_POST['email'] ?? null;
        $birthday = $_POST['birthday'] ?? null;
        $age = $_POST['age'] ?? 0;
        $sex = $_POST['sex'] ?? null;

        // Start debugging
        echo "Password: " . ($password !== null ? 'Provided' : 'Not provided') . "<br>";
        echo "Image content: " . (isset($imgContent) ? 'Provided' : 'Not provided') . "<br>";
        
        // Array to hold the fields to update
        $fieldsToUpdate = [
            "f_name = :f_name",
            "l_name = :l_name",
            "position = :position",
            "user_type = :user_type",
            "email_address = :email_address",
            "birthday = :birthday",
            "age = :age",
            "sex = :sex"
        ];

        // Conditionally add password if provided
        if ($password !== null && $password !== '') {
            $fieldsToUpdate[] = "password = :password";
        }

        // Conditionally add image if provided
        if ($imgContent !== null && $imgContent !== '') {
            $fieldsToUpdate[] = "profile_picture = :profile_picture";
        }

        // Build the SQL query dynamically
        $sql = "UPDATE mis_employees SET " . implode(", ", $fieldsToUpdate) . " WHERE admin_number = :admin_number";

        // Output SQL for debugging
        echo "Final SQL Query: $sql <br>";

        // Prepare the statement
        $stmt = $pdoConnect->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':admin_number', $admin_number, PDO::PARAM_STR);
        $stmt->bindParam(':f_name', $f_name, PDO::PARAM_STR);
        $stmt->bindParam(':l_name', $l_name, PDO::PARAM_STR);
        $stmt->bindParam(':position', $position, PDO::PARAM_STR);
        $stmt->bindParam(':user_type', $user_type, PDO::PARAM_STR);
        $stmt->bindParam(':email_address', $email_address, PDO::PARAM_STR);
        $stmt->bindParam(':birthday', $birthday, PDO::PARAM_STR);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':sex', $sex, PDO::PARAM_STR);

        // Bind the password only if it is provided
        if ($password !== null && $password !== '') {
            // Hash the password before saving
            //$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        }

        // Bind the image content only if it is provided
        if ($imgContent !== null && $imgContent !== '') {
            $stmt->bindParam(':profile_picture', $imgContent, PDO::PARAM_LOB);
        }

        // Execute the statement and handle the transaction
        if ($stmt->execute()) {
            $pdoConnect->commit();
            header("Location: ../employee.php");
            exit();
        } else {
            $pdoConnect->rollBack();
            echo "Failed to update employee record.";
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    if ($pdoConnect->inTransaction()) {
        $pdoConnect->rollBack();
    }
}

$pdoConnect = null;
?>
