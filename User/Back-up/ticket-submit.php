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
    $id = 7;
    $status = 'Pending';

    // Set the PDO error mode to exception
    $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and bind parameters
    $stmt = $pdoConnect->prepare("INSERT INTO tb_tickets (id, issue, description, screenshot, consent, status) VALUES (:id, :category, :issue_description, :image_path, :consent, :status)");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':issue_description', $issue_description);
    $stmt->bindParam(':image_path', $image_path);
    $stmt->bindParam(':consent', $consent);
    $stmt->bindParam(':status', $status);

    // Fetch data from POST
    $category = $_POST['category'];
    $issue_description = $_POST['issue-description'];
    $consent = $_POST['consent'];

    // Upload image
    if ($_FILES['img']['tmp_name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);

        if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
            $image_path = $target_file;

            // Execute the prepared statement
            $stmt->execute();

            echo "New record created successfully";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
}
?>
