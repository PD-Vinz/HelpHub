<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["student_number"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {

try {

    $id = $_SESSION["student_number"];
    $status = 'Pending';
    $category = $_POST['category'];
    $issue_description = $_POST['issue-description'];
    $consent = $_POST['consent'];
    $datetime = date('Y-m-d H:i:s');

    $pdoUserQuery = "SELECT * FROM student_user WHERE student_number = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();

    $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

    if ($Data) {
        $Email_Add = $Data['email_address'];
        $Name = $Data['name'];
        $Department = $Data['department'];
        $Course = $Data['course'];
        $Y_S = $Data['year_section'];
        $P_P = $Data['profile_picture'];
        $Sex = $Data['sex'];

        $nameParts = explode(' ', $Name);
        $firstName = $nameParts[0];
    } else {
        // Handle the case where no results are found
        echo "No student found with the given student number.";
    }

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
            
            // Prepare an insert statement
            $stmt = $pdoConnect->prepare("INSERT INTO tb_tickets (created_date, full_name, user_number, issue, description, screenshot, consent, status) 
                                        VALUES (:createddate, :fullname, :usernumber, :category, :issue_description, :image, :consent, :status)");
            // Bind the blob data

            $stmt->bindParam(':createddate', $datetime, PDO::PARAM_LOB);
            $stmt->bindParam(':fullname', $Name, PDO::PARAM_LOB);
            $stmt->bindParam(':usernumber', $id, PDO::PARAM_LOB);
            $stmt->bindParam(':category', $category, PDO::PARAM_LOB);
            $stmt->bindParam(':issue_description', $issue_description, PDO::PARAM_LOB);
            $stmt->bindParam(':image', $imgContent, PDO::PARAM_LOB);
            $stmt->bindParam(':consent', $consent, PDO::PARAM_LOB);
            $stmt->bindParam(':status', $status, PDO::PARAM_LOB);

            // Execute the statement
            if ($stmt->execute()) {
                header("Location: receive-ticket-response.php");
                exit();
            } else {
                header("Location: create-ticket.php?error=1");
                exit();
            }
        } else {
            header("Location: create-ticket.php?error=1");
            exit();
        }
    }


} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    echo "<a href='index.html'>Back</a>";
}

// Close the connection
$conn = null;
}
?>