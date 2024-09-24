<?php
include_once("../../connection/conn.php");
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
        $U_T = $Data['user_type'];

        $nameParts = explode(' ', $Name);
        $firstName = $nameParts[0];
    } else {
        // Handle the case where no results are found
        echo "No student found with the given student number.";
    }

}

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $TN = $_POST['Name'];
            $TC = $_POST['Content'];

            // Start a transaction
            $pdoConnect->beginTransaction();
            
            // Prepare an insert statement
            $stmt = $pdoConnect->prepare("INSERT INTO `templates`(`template_name`, `template_content`) 
                                        VALUES (:tn,:tc)");
            // Bind the blob data

            $stmt->bindParam(':tn', $TN, PDO::PARAM_LOB);
            $stmt->bindParam(':tc', $TC, PDO::PARAM_LOB);


            // Execute the statement
            if ($stmt->execute()) {
                    // Commit the transaction
                    $pdoConnect->commit();

                header("Location:../response-templates.php");
                exit();
            } else {
                // Roll back the transaction on failure
                $pdoConnect->rollBack();
                header("Location:../response-templates");
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
$pdoConnect = null;