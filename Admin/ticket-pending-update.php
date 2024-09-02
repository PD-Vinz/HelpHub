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

    $pdoCountQuery = "SELECT * FROM tb_tickets";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $allTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Pending'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $pendingTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Returned'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $returnedTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Completed'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $completedTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Due'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $dueTickets = $pdoResult->rowCount();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

try {
    $update_id = $_GET["id"];
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
            $USER_TYPE = $Data['user_type'];
            
            // Check USER_TYPE and redirect accordingly
            if ($USER_TYPE == 'Student') {
                header("Location: ticket-opened.php?id=1");
                exit; // Exit after the header call
            } elseif ($USER_TYPE == 'Employee') {
                header("Location: ticket-opened.php?id=2");
                exit; // Exit after the header call
            } else {
                // Handle unexpected USER_TYPE
                echo "Unexpected user type.";
            }
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

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


}




?>