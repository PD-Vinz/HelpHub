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
 // for displaying system details
 $query = $pdoConnect->prepare("SELECT system_name, short_name, system_logo, system_cover FROM settings WHERE id = :id");
 $query->execute(['id' => 1]);
 $Datas = $query->fetch(PDO::FETCH_ASSOC);
 $sysName = $Datas['system_name'] ?? '';
 $shortName = $Datas['short_name'] ?? '';
  $systemCover = $Datas['system_cover'];
  $S_L = $Datas['system_logo'];
  $S_LBase64 = '';
  if (!empty($S_L)) {
      $base64Image = base64_encode($S_L);
      $imageType = 'image/png'; // Default MIME type
      $S_LBase64 = 'data:' . $imageType . ';base64,' . $base64Image;
  }
// for displaying system details //end
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

$ticket_user = $_GET["user"];

if (isset($_GET["form"]) && $_GET["form"] == 'close') {

    try {
        // If not using transactions, you don't need to start one
        // $pdoConnect->beginTransaction();

        $update_id = $_GET["id"];
        $status = "Resolved";
        $FD = date('Y-m-d H:i:s');
        $_SESSION["dateResolved"] = $FD;
        $Resolution = $_POST['resolution'];

        $pdoUserQuery = "SELECT * FROM tb_tickets WHERE ticket_id = :id";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':id', $update_id);
        $pdoResult->execute();
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
        if ($Data) {
            $OD = $Data['opened_date'];

            $_SESSION["ticketid"] = $Data['ticket_id'];
            $_SESSION["Address"] = $Data['email_address'];
            $_SESSION["userName"] = $Data['full_name'];
            $_SESSION["status"] = $Data['status'];
            $_SESSION["employee"] = $Data['employee'];
            $_SESSION["issue"] = $Data['issue'];
            $_SESSION["description"] = $Data['description'];
            $_SESSION["dateCreated"] = $Data['created_date'];
            $_SESSION["dateOpened"] = $Data['opened_date'];
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }

        $date1 = new DateTime($FD);
        $date2 = new DateTime($OD);

        $interval = $date1->diff($date2);
        echo "Years: " . $interval->y . "<br>";
        echo "Months: " . $interval->m . "<br>";
        echo "Days: " . $interval->d . "<br>";
        echo "Hours: " . $interval->h . "<br>";
        echo "Minutes: " . $interval->i . "<br>";
        echo "Seconds: " . $interval->s . "<br>";

if ($interval->y == 0){
    if ($interval->m == 0){
        if ($interval->d == 0){
            if ($interval->h == 0){
                if ($interval->i == 0){
                    if ($interval->s == 0){
                        echo "Invalid Time";
                    } else {
                        $Duration = $interval->s . "Seconds"  ;
                    }
                } else {
                    $Duration = $interval->i . " Minutes, ";
                }
            } else {
                $Duration = $interval->h . " Hours, " . $interval->i . " Minutes, ";
            }
        } else {
            $Duration = $interval->d . " Days, " . $interval->h . " Hours, " . $interval->i . " Minutes, ";
        }
    } else {
        $Duration = $interval->m . " Months, " . $interval->d . " Days, " . $interval->h . " Hours, " . $interval->i . " Minutes, " ;
    }
} else {
    $Duration = $interval->y . " Years, " . $interval->m . " Months, " . $interval->d . " Days, " . $interval->h . " Hours, " . $interval->i . " Minutes, ";
}


        $pdoUpdateQuery="UPDATE tb_tickets 
                        SET   status = :status, finished_date = :FD, duration = :duration , resolution = :resolution
                        WHERE ticket_id = :id";
        $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
        $pdoResult->bindParam(':id', $update_id, PDO::PARAM_STR);
        $pdoResult->bindParam(':FD', $FD, PDO::PARAM_STR);
        $pdoResult->bindParam(':duration', $Duration, PDO::PARAM_STR);
        $pdoResult->bindParam(':resolution', $Resolution, PDO::PARAM_STR);
        $pdoResult->bindParam(':status', $status, PDO::PARAM_STR);
        if (!$pdoResult->execute()) {
            throw new PDOException("Failed to execute the first query");
        }
    
        $ticket_desc = "Ticket Completed";
    
        $pdoUpdateQuery="INSERT INTO ticket_logs 
                        SET ticket_id = :id, date_time = :FD, description = :desc, status = :status";
        $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
        $pdoResult->bindParam(':id', $update_id, PDO::PARAM_STR);
        $pdoResult->bindParam(':FD', $FD, PDO::PARAM_STR);
        $pdoResult->bindParam(':desc', $ticket_desc, PDO::PARAM_STR);
        $pdoResult->bindParam(':status', $status, PDO::PARAM_STR);
        if (!$pdoResult->execute()) {
            throw new PDOException("Failed to execute the second query");
        }
    
        $mis_desc = "Completed and closed a ticket with ID = " . $update_id;
    
        $pdoUpdateQuery="INSERT INTO mis_history_logs 
                        SET admin_number = :id, date_time = :FD, description = :desc";
        $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
        $pdoResult->bindParam(':id', $id, PDO::PARAM_STR);
        $pdoResult->bindParam(':FD', $FD, PDO::PARAM_STR);
        $pdoResult->bindParam(':desc', $mis_desc, PDO::PARAM_STR);
        if (!$pdoResult->execute()) {
            throw new PDOException("Failed to execute the third query");
        }
    
        // If not using transactions, you don't need to commit one
        //$pdoConnect->commit();

        $_SESSION["imageUrl"] = "https://dhvsuhelphub.com/User/view_image.php?id=" . $update_id;
        $_SESSION["websiteUrl"] = "https://dhvsuhelphub.com/";

        if (isset($ticket_user) && $ticket_user == "Student") {
            $_SESSION["USER_TYPE"] = "Student";
            header("Location: generate-ticket-update-email-resolved.php");
        } elseif (isset($ticket_user) && $ticket_user == "Employee") {
            $_SESSION["USER_TYPE"] = "Employee";
            header("Location: generate-ticket-update-email-resolved.php");
        }
        
        header("generate-ticket-update-email-resolved.php");
    
    } catch (PDOException $e) {
        // No need for rollBack() if transactions are not used
        //$if ($pdoConnect->inTransaction()) { // Check if a transaction is active
        //$pdoConnect->rollBack();
        //}
        echo "Error: " . $e->getMessage();
    }

} elseif (isset($_GET["form"]) && $_GET["form"] == 'return') {
    
    try {
        // If not using transactions, you don't need to start one
        // $pdoConnect->beginTransaction();

        $update_id = $_GET["id"];
        $status = "Returned";
        $FD = date('Y-m-d H:i:s');
        $Resolution = $_POST['resolution'];

        $pdoUserQuery = "SELECT * FROM tb_tickets WHERE ticket_id = :id";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':id', $update_id);
        $pdoResult->execute();
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
        if ($Data) {
            $OD = $Data['opened_date'];
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }

        $date1 = new DateTime($FD);
        $date2 = new DateTime($OD);

        $interval = $date1->diff($date2);
        echo "Years: " . $interval->y . "<br>";
        echo "Months: " . $interval->m . "<br>";
        echo "Days: " . $interval->d . "<br>";
        echo "Hours: " . $interval->h . "<br>";
        echo "Minutes: " . $interval->i . "<br>";
        echo "Seconds: " . $interval->s . "<br>";

if ($interval->y == 0){
    if ($interval->m == 0){
        if ($interval->d == 0){
            if ($interval->h == 0){
                if ($interval->i == 0){
                    if ($interval->s == 0){
                        echo "Invalid Time";
                    } else {
                        $Duration = $interval->s . "Seconds"  ;
                    }
                } else {
                    $Duration = $interval->i . "Minutes, " . $interval->s . "Seconds"  ;
                }
            } else {
                $Duration = $interval->h . "Hours, " . $interval->i . "Minutes, " . $interval->s . "Seconds"  ;
            }
        } else {
            $Duration = $interval->d . "Days, " . $interval->h . "Hours, " . $interval->i . "Minutes, " . $interval->s . "Seconds,"  ;
        }
    } else {
        $Duration = $interval->m . "Months, " . $interval->d . "Days, " . $interval->h . "Hours, " . $interval->i . "Minutes, " . $interval->s . "Seconds"  ;
    }
} else {
    $Duration = $interval->y . "Years, " . $interval->m . "Months, " . $interval->d . "Days, " . $interval->h . "Hours, " . $interval->i . "Minutes, " . $interval->s . "Seconds"  ;
}


        $pdoUpdateQuery="UPDATE tb_tickets 
                        SET   status = :status, finished_date = :FD, duration = :duration , resolution = :resolution
                        WHERE ticket_id = :id";
        $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
        $pdoResult->bindParam(':id', $update_id, PDO::PARAM_STR);
        $pdoResult->bindParam(':FD', $FD, PDO::PARAM_STR);
        $pdoResult->bindParam(':duration', $Duration, PDO::PARAM_STR);
        $pdoResult->bindParam(':resolution', $Resolution, PDO::PARAM_STR);
        $pdoResult->bindParam(':status', $status, PDO::PARAM_STR);
        if (!$pdoResult->execute()) {
            throw new PDOException("Failed to execute the first query");
        }
    
        $ticket_desc = "Ticket Returned";
    
        $pdoUpdateQuery="INSERT INTO ticket_logs 
                        SET ticket_id = :id, date_time = :FD, description = :desc, status = :status";
        $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
        $pdoResult->bindParam(':id', $update_id, PDO::PARAM_STR);
        $pdoResult->bindParam(':FD', $FD, PDO::PARAM_STR);
        $pdoResult->bindParam(':desc', $ticket_desc, PDO::PARAM_STR);
        $pdoResult->bindParam(':status', $status, PDO::PARAM_STR);
        if (!$pdoResult->execute()) {
            throw new PDOException("Failed to execute the second query");
        }
    
        $mis_desc = "Returned and closed a ticket with ID = " . $update_id;
    
        $pdoUpdateQuery="INSERT INTO mis_history_logs 
                        SET admin_number = :id, date_time = :FD, description = :desc";
        $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
        $pdoResult->bindParam(':id', $id, PDO::PARAM_STR);
        $pdoResult->bindParam(':FD', $FD, PDO::PARAM_STR);
        $pdoResult->bindParam(':desc', $mis_desc, PDO::PARAM_STR);
        if (!$pdoResult->execute()) {
            throw new PDOException("Failed to execute the third query");
        }
    
        // If not using transactions, you don't need to commit one
        //$pdoConnect->commit();
        if (isset($ticket_user) && $ticket_user == "Student") {
            header("Location: ticket-opened.php?id=1");
        } elseif (isset($ticket_user) && $ticket_user == "Employee") {
            header("Location: ticket-opened.php?id=2");
        }
        
    
    } catch (PDOException $e) {
        // No need for rollBack() if transactions are not used
        //$if ($pdoConnect->inTransaction()) { // Check if a transaction is active
        //$pdoConnect->rollBack();
        //}
        echo "Error: " . $e->getMessage();
    }
    
} elseif (isset($_GET["form"]) && $_GET["form"] == 'transfer') {
        
    try {
        // If not using transactions, you don't need to start one
        // $pdoConnect->beginTransaction();

        $update_id = $_GET["id"];
        $status = "Transferred";
        $newemployee = $_POST['employee'];
        $FD = date('Y-m-d H:i:s');

        $pdoUpdateQuery="UPDATE tb_tickets 
                        SET employee = :employee
                        WHERE ticket_id = :id";
        $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
        $pdoResult->bindParam(':id', $update_id, PDO::PARAM_STR);
        $pdoResult->bindParam(':employee', $newemployee, PDO::PARAM_STR);
        
        if (!$pdoResult->execute()) {
            throw new PDOException("Failed to execute the first query");
        }
    
        $ticket_desc = "Ticket Transferred to " . $newemployee;
    
        $pdoUpdateQuery="INSERT INTO ticket_logs SET ticket_id = :id, date_time = :FD, description = :desc, status = :status";
        $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
        $pdoResult->bindParam(':id', $update_id, PDO::PARAM_STR);
        $pdoResult->bindParam(':FD', $FD, PDO::PARAM_STR);
        $pdoResult->bindParam(':desc', $ticket_desc, PDO::PARAM_STR);
        $pdoResult->bindParam(':status', $status, PDO::PARAM_STR);
        if (!$pdoResult->execute()) {
            throw new PDOException("Failed to execute the second query");
        }
    
        $mis_desc = "Transferred ticket with ID = " . $update_id . " to " . $newemployee;
    
        $pdoUpdateQuery="INSERT INTO mis_history_logs SET admin_number = :id, date_time = :FD, description = :desc";
        $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
        $pdoResult->bindParam(':id', $update_id, PDO::PARAM_STR);
        $pdoResult->bindParam(':FD', $FD, PDO::PARAM_STR);
        $pdoResult->bindParam(':desc', $mis_desc, PDO::PARAM_STR);
        if (!$pdoResult->execute()) {
            throw new PDOException("Failed to execute the third query");
        }
    
        // If not using transactions, you don't need to commit one
        //$pdoConnect->commit();
        if (isset($ticket_user) && $ticket_user == "Student") {
            header("Location: ticket-opened.php?id=1");
        } elseif (isset($ticket_user) && $ticket_user == "Employee") {
            header("Location: ticket-opened.php?id=2");
        }
        
    
    } catch (PDOException $e) {
        // No need for rollBack() if transactions are not used
        //$if ($pdoConnect->inTransaction()) { // Check if a transaction is active
        //$pdoConnect->rollBack();
        //}
        echo "Error: " . $e->getMessage();
    }

} else {
    // Handle the case where no results are found
    echo "Cannot proceed with your request";
}





}