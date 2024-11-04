<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

$id = $_SESSION["user_id"];
$identity = $_SESSION["user_identity"];

if (isset($_POST['update'])) {

    // File validation function
    function validateImage($image) {
        $maxSize = 6 * 1024 * 1024; // 6MB
        $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        
        // Debugging: Check the actual MIME type
        $fileType = mime_content_type($image['tmp_name']);
        $fileTypeDirect = $image['type'];
    
        if ($image['size'] > $maxSize) {
            echo "File size exceeds 6MB limit.";
            exit;
        }
        
        // Check both mime_content_type and the type in the $_FILES array
        if (!in_array($fileType, $allowedTypes) && !in_array($fileTypeDirect, $allowedTypes)) {
            echo "Only PNG, JPG, and JPEG files are allowed. Detected: $fileType, $fileTypeDirect";
            exit;
        }
    
        return file_get_contents($image['tmp_name']);
    }

    // Student or employee details from the form
    $NewFirstName = $_POST['first_name'] ?? '';
    $NewLastName = $_POST['last_name'] ?? '';
    $NewMiddleName = $_POST['middle_name'] ?? '';
    $NewMiddleInitialName = $_POST['middle_initial'] ?? '';
    $NewExtName = $_POST['ext_name'] ?? '';
    $NewSex = $_POST['sex'] ?? '';
    $NewBday = $_POST['bday'] ?? '';
    $NewCampus = $_POST['campus'] ?? '';
    $NewDept = $_POST['dept'] ?? '';
    $NewCourse = $_POST['course'] ?? '';
    $NewYS = $_POST['ys'] ?? '';

    $NewAltEmail = $_POST['altemailadd'] ?? '';

    // Calculate age if birthday provided
    $NewAge = $NewBday ? (new DateTime())->diff(new DateTime($NewBday))->y : 0;

    // Handle image upload if provided
    $imgContent = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imgContent = validateImage($_FILES['image']);
    }

    try {
        $table = ($identity == "Student") ? "student_user" : "employee_user";
        $fields = "first_name = :first_name, last_name = :last_name, middle_name = :middle_name, middle_initial = :middle_initial, ext_name = :ext_name, birthday = :birthday, age = :age, campus = :campus, department = :department, sex = :sex";
        
        if (!empty($NewAltEmail)){
            $fields .= ", alt_email_address = :alt_email";
        }
        
        if ($identity == "Student") {
            $fields .= ", course = :course, year_section = :year_section";
        }
        if ($imgContent) {
            $fields .= ", profile_picture = :P_P";
        }

        $query = "UPDATE $table SET $fields WHERE user_id = :number";
        $stmt = $pdoConnect->prepare($query);
        $stmt->bindParam(':number', $id);
        $stmt->bindParam(':first_name', $NewFirstName);
        $stmt->bindParam(':last_name', $NewLastName);
        $stmt->bindParam(':middle_name', $NewMiddleName);
        $stmt->bindParam(':middle_initial', $NewMiddleInitialName);
        $stmt->bindParam(':ext_name', $NewExtName);
        $stmt->bindParam(':birthday', $NewBday);
        $stmt->bindParam(':age', $NewAge);
        $stmt->bindParam(':campus', $NewCampus);
        $stmt->bindParam(':department', $NewDept);
        $stmt->bindParam(':sex', $NewSex);

        if (!empty($NewAltEmail)){
            $stmt->bindParam(':alt_email', $NewAltEmail);
        }
        
        if ($identity == "Student") {
            $stmt->bindParam(':course', $NewCourse);
            $stmt->bindParam(':year_section', $NewYS);
        }
        if ($imgContent) {
            $stmt->bindParam(':P_P', $imgContent, PDO::PARAM_LOB);
        }

        $stmt->execute();

        $_SESSION['update_success'] = true;
        header("location: profile.php");
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>
