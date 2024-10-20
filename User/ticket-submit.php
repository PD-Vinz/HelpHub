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

    if ($identity == "Student"){
        $pdoUserQuery = "SELECT * FROM student_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->execute();
    
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $Email_Add = $Data['email_address'];
            $Name = $Data['name'];
            $Campus = $Data['campus'];
            $Department = $Data['department'];
            $Course = $Data['course'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
            $Sex = $Data['sex'];
            $Age = $Data['age'];
            $UserType = $Data['user_type'];

            $nameParts = explode(' ', $Name);
            $firstName = $nameParts[0];
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }
    } elseif ($identity == "Employee") {
        $pdoUserQuery = "SELECT * FROM employee_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->execute();
    
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $Email_Add = $Data['email_address'];
            $Name = $Data['name'];
            $Campus = $Data['campus'];
            $Department = $Data['department'];
            $Course = $Data['course'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
            $Sex = $Data['sex'];
            $Age = $Data['age'];
            $UserType = $Data['user_type'];

            $nameParts = explode(' ', $Name);
            $firstName = $nameParts[0];
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }
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

            // Start a transaction
            $pdoConnect->beginTransaction();
            
            // Prepare an insert statement
            $stmt = $pdoConnect->prepare("INSERT INTO tb_tickets (created_date, full_name, user_id, email_address, campus, department, course, year_section, sex, age, user_type, issue, description, screenshot, consent, status) 
                                        VALUES (:createddate, :fullname, :usernumber, :email_address, :campus, :department, :course, :year_section, :sex, :age, :usertype, :category, :issue_description, :image, :consent, :status)");
            // Bind the blob data

            $stmt->bindParam(':createddate', $datetime, PDO::PARAM_LOB);
            $stmt->bindParam(':fullname', $Name, PDO::PARAM_LOB);
            $stmt->bindParam(':usernumber', $id, PDO::PARAM_LOB);
            $stmt->bindParam(':email_address', $Email_Add, PDO::PARAM_LOB);
            $stmt->bindParam(':campus', $Campus, PDO::PARAM_LOB);
            $stmt->bindParam(':department', $Department, PDO::PARAM_LOB);
            $stmt->bindParam(':course', $Course, PDO::PARAM_LOB);
            $stmt->bindParam(':year_section', $Y_S, PDO::PARAM_LOB);
            $stmt->bindParam(':sex', $Sex, PDO::PARAM_LOB);
            $stmt->bindParam(':age', $Age, PDO::PARAM_LOB);
            $stmt->bindParam(':usertype', $UserType, PDO::PARAM_LOB);
            $stmt->bindParam(':category', $category, PDO::PARAM_LOB);
            $stmt->bindParam(':issue_description', $issue_description, PDO::PARAM_LOB);
            $stmt->bindParam(':image', $imgContent, PDO::PARAM_LOB);
            $stmt->bindParam(':consent', $consent, PDO::PARAM_LOB);
            $stmt->bindParam(':status', $status, PDO::PARAM_LOB);

            // Execute the statement
            if ($stmt->execute()) {
                    // Get the last inserted ID
                    $lastInsertId = $pdoConnect->lastInsertId();

                    // Commit the transaction
                    $pdoConnect->commit();

                    $ticket_desc = "Ticket Created";

                    $pdoUpdateQuery="INSERT ticket_logs 
                                    SET ticket_id = :id, date_time = :OD, description = :desc, status = :status";
                    $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
                    $pdoResult->bindParam(':id', $lastInsertId, PDO::PARAM_STR);
                    $pdoResult->bindParam(':OD', $datetime, PDO::PARAM_STR);
                    $pdoResult->bindParam(':desc', $ticket_desc, PDO::PARAM_STR);
                    $pdoResult->bindParam(':status', $status, PDO::PARAM_STR);
                    if (!$pdoResult->execute()) {
                        throw new PDOException("Failed to execute the second query");
                    }

                $_SESSION["Address"] = $Email_Add;
                $_SESSION["userName"] = $Name;
                $_SESSION["issue"] = $category;
                $_SESSION["description"] = $issue_description;
                $_SESSION["dateCreated"] = $datetime;
                $_SESSION["imageUrl"] = "https://dhvsuhelphub.com/User/view_image.php?id=" . $lastInsertId;
                $_SESSION["websiteUrl"] = "https://dhvsuhelphub.com/";
                $_SESSION["status"] = $status;
                $_SESSION["ticketnumber"] = $lastInsertId;

                header("Location: generate-email.php");
                exit();
            } else {
                // Roll back the transaction on failure
                $pdoConnect->rollBack();
                header("Location: create-ticket.php?error='yes'");
                exit();
            }
        } else {
            header("Location: create-ticket.php?error=yes");
            exit();
        }
    }


} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    echo "<a href='create-ticket.php'>Back</a>";

    // Roll back the transaction on exception
    if ($pdoConnect->inTransaction()) {
        $pdoConnect->rollBack();
    }
}

// Close the connection
$conn = null;
}
?>