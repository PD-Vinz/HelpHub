<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {

try {

    $id = $_SESSION["user_id"];
    $identity = $_SESSION["user_identity"];
    $status = 'Pending';
    $category = $_POST['category'];
    $issue_description = $_POST['issue-description'];
    $consent = $_POST['consent'];
    $datetime = date('Y-m-d H:i:s');

 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

            // Start a transaction
            $pdoConnect->beginTransaction();
            
            // Prepare an insert statement
            $stmt = $pdoConnect->prepare("update settings ( system_name
                                        VALUES (:createddate, :fullname, :usernumber, :campus, :department, :course, :year_section, :sex, :age, :usertype, :category, :issue_description, :image, :consent, :status)");
            // Bind the blob data

            
            $stmt->bindParam(':image', $imgContent, PDO::PARAM_LOB);
            $stmt->bindParam(':consent', $consent, PDO::PARAM_LOB);
            $stmt->bindParam(':status', $status, PDO::PARAM_LOB);

            // Execute the statement
           
        } else {
            header("Location: create-ticket.php?error=1");
            exit();
        }
    }


} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    echo "<a href='index.html'>Back</a>";

    // Roll back the transaction on exception
    if ($pdoConnect->inTransaction()) {
        $pdoConnect->rollBack();
    }
}

// Close the connection
$conn = null;
}
?>