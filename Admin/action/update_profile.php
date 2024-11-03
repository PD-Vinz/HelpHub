<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Ensure user is logged in
if (!isset($_SESSION["admin_number"])) {
    header("Location: ../../index.php");
    exit();
}

$user_id = $_SESSION["admin_number"];

if (isset($_POST['update'])) {
    try {
        // Handle profile picture if uploaded
        $imgContent = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = $_FILES['image'];
            $maxSize = 6 * 1024 * 1024; // 6MB
            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            $fileType = mime_content_type($image['tmp_name']);

            if ($image['size'] > $maxSize) {
                echo "File size exceeds 6MB limit.";
                exit;
            }

            if (!in_array($fileType, $allowedTypes)) {
                echo "Only PNG, JPG, and JPEG files are allowed.";
                exit;
            }

            $imgContent = file_get_contents($image['tmp_name']);
        }

        // Sanitize and retrieve form inputs
        $NewFName = htmlspecialchars($_POST['first_name']);
        $NewLName = htmlspecialchars($_POST['last_name']);
        $NewMName = htmlspecialchars($_POST['middle_name'] ?? null);
        $NewMInitial = htmlspecialchars($_POST['middle_initial'] ?? null);
        $NewEXTName = htmlspecialchars($_POST['ext_name'] ?? null);
        $NewSex = $_POST['sex'];
        $NewBday = $_POST['bday'];
        $NewEmail = filter_var($_POST['emailadd'], FILTER_VALIDATE_EMAIL);
        $NewAltEmail = filter_var($_POST['altemailadd'] ?? null, FILTER_VALIDATE_EMAIL);

        // Calculate age
        $NewAge = $NewBday ? (new DateTime())->diff(new DateTime($NewBday))->y : 0;

        // Prepare update query
        $pdoUserQuery = "UPDATE mis_employees SET 
                            f_name = :fname, 
                            l_name = :lname, 
                            m_name = :mname, 
                            m_initial = :minitial, 
                            ext_name = :extname, 
                            email_address = :email, 
                            alt_email_address = :altemail, 
                            birthday = :birthday, 
                            age = :age, 
                            sex = :sex" 
                            . ($imgContent ? ", profile_picture = :P_P " : " ") . 
                            "WHERE admin_number = :number";

        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $user_id);
        $pdoResult->bindParam(':fname', $NewFName);
        $pdoResult->bindParam(':lname', $NewLName);
        $pdoResult->bindParam(':mname', $NewMName);
        $pdoResult->bindParam(':minitial', $NewMInitial);
        $pdoResult->bindParam(':extname', $NewEXTName);
        $pdoResult->bindParam(':email', $NewEmail);
        $pdoResult->bindParam(':altemail', $NewAltEmail);
        $pdoResult->bindParam(':birthday', $NewBday);
        $pdoResult->bindParam(':age', $NewAge);
        $pdoResult->bindParam(':sex', $NewSex);

        if ($imgContent) {
            $pdoResult->bindParam(':P_P', $imgContent);
        }

        $pdoResult->execute();

        // Set a session variable to indicate a successful update
        $_SESSION['update_success'] = true;

        // Redirect to profile
        header("location: ../profile.php");
        exit();

    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>
