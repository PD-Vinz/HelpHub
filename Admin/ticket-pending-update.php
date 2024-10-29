<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["admin_number"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["admin_number"];

    $pdoUserQuery = "SELECT * FROM mis_employees WHERE admin_number = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();

    $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

    if ($Data) {
        $Name = $Data['f_name'];
        $Position = $Data['position'];
        $U_S = $Data['user_type'];

        $nameParts = explode(' ', $Name);
        $firstName = $nameParts[0];
    } else {
        // Handle the case where no results are found
        echo "No student found with the given student number.";
    }

try {
    $update_id = $_GET["id"];

    function handleValue1($pdoConnect) {
        global $Name;
        global $update_id;  
        global $id; 
        $status = "Processing";
        $OD = date('Y-m-d H:i:s');
        
        $pdoUpdateQuery="UPDATE tb_tickets 
                        SET employee = :employee, opened_date = :OD, status = :status
                        WHERE ticket_id = :id";
        $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
        $pdoResult->bindParam(':id', $update_id, PDO::PARAM_STR);
        $pdoResult->bindParam(':employee', $Name, PDO::PARAM_STR);
        $pdoResult->bindParam(':OD', $OD, PDO::PARAM_STR);
        $pdoResult->bindParam(':status', $status, PDO::PARAM_STR);
        if (!$pdoResult->execute()) {
            throw new PDOException("Failed to execute the first query");
        }
    
        $ticket_desc = "Ticket Opened";
    
        $pdoUpdateQuery="INSERT ticket_logs 
                        SET ticket_id = :id, date_time = :OD, description = :desc, status = :status";
        $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
        $pdoResult->bindParam(':id', $update_id, PDO::PARAM_STR);
        $pdoResult->bindParam(':OD', $OD, PDO::PARAM_STR);
        $pdoResult->bindParam(':desc', $ticket_desc, PDO::PARAM_STR);
        $pdoResult->bindParam(':status', $status, PDO::PARAM_STR);
        if (!$pdoResult->execute()) {
            throw new PDOException("Failed to execute the second query");
        }
    
        $mis_desc = "Opened a ticket with ID = " . $update_id;
    
        $pdoUpdateQuery="INSERT mis_history_logs 
                        SET admin_number = :id, date_time = :OD, description = :desc";
        $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
        $pdoResult->bindParam(':id', $id, PDO::PARAM_STR);
        $pdoResult->bindParam(':OD', $OD, PDO::PARAM_STR);
        $pdoResult->bindParam(':desc', $mis_desc, PDO::PARAM_STR);
        if (!$pdoResult->execute()) {
            throw new PDOException("Failed to execute the third query");
        }
    
    try {
            // Ensure $update_id is defined and properly sanitized
            if (!isset($update_id)) {
                throw new Exception("Ticket ID is not set.");
            }
        
            // Prepare and execute the query
            $pdoUserQuery = "SELECT * FROM tb_tickets WHERE ticket_id = :id";
            $pdoResult = $pdoConnect->prepare($pdoUserQuery);
            $pdoResult->bindParam(':id', $update_id, PDO::PARAM_INT); // Use PDO::PARAM_INT if ticket_id is an integer
            $pdoResult->execute();
        
            // Fetch the result
            $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
        
            if ($Data) {

                $_SESSION["USER_TYPE"] = $Data['user_type'];
                $_SESSION["ticketid"] = $Data['ticket_id'];
                $_SESSION["Address"] = $Data['email_address'];
                $_SESSION["userName"] = $Data['full_name'];
                $_SESSION["status"] = $Data['status'];
                $_SESSION["employee"] = $Data['employee'];
                $_SESSION["issue"] = $Data['issue'];
                $_SESSION["description"] = $Data['description'];
                $_SESSION["dateCreated"] = $Data['created_date'];
                $_SESSION["dateOpened"] = $Data['opened_date'];
                $_SESSION["imageUrl"] = "https://dhvsuhelphub.com/User/view_image.php?id=" . $update_id;
                $_SESSION["websiteUrl"] = "https://dhvsuhelphub.com/";

                header("Location: generate-ticket-update-email.php");
                exit();
            } else {
                // Handle the case where no results are found
                echo "No student found with the given ticket ID.";
            }
        } catch (PDOException $e) {
            // Handle PDO exceptions
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            // Handle other exceptions
            echo "Error: " . $e->getMessage();
        }
    }

    // Prepare the SQL query
    $checkquery = "SELECT employee, user_type FROM tb_tickets WHERE ticket_id = :id";
    $pdoResult = $pdoConnect->prepare($checkquery);
    $pdoResult->bindParam(':id', $update_id, PDO::PARAM_STR);
    $pdoResult->execute();
    
    // Fetch the result
    $result = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        // Check the value of the employee field
        $employeeValue = $result['employee'];
        $usertypeValue = $result['user_type'];
    
        if ($employeeValue == 'No employee assigned') { // Replace 'some_value' with your specific value to check against
            // Do something if the condition is true
            handleValue1($pdoConnect);
        } elseif ($employeeValue == '') {
            handleValue1($pdoConnect);
        } else {
            if ($usertypeValue == "Student"){
            header("Location: ticket-pending.php?failed=true&id=1");
            } elseif ($usertypeValue == "Employee") {
            header("Location: ticket-pending.php?failed=true&id=2");
            }
        }
    } else {
        handleValue1($pdoConnect);
    }


} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


}




?>