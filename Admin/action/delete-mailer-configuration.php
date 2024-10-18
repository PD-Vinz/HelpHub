<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["admin_number"])) {
    header("Location: ../index.php");
    exit();
} else {
    $adminId = $_SESSION["admin_number"];

    // Fetch the user data based on session variable
    $userQuery = "SELECT * FROM mis_employees WHERE admin_number = :number";
    $pdoResult = $pdoConnect->prepare($userQuery);
    $pdoResult->bindParam(':number', $adminId);
    $pdoResult->execute();

    $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

    if ($Data) {
        $Name = $Data['f_name'];
        $Position = $Data['position'];
        $U_T = $Data['user_type'];

        $nameParts = explode(' ', $Name);
        $firstName = $nameParts[0];
    } else {
        echo "No employee found with the given admin number.";
        exit; // Stop execution if no user found
    }
}

// Check if the 'id' parameter is set
if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Validate that the ID is a valid number
    if ($id === false || $id <= 0) {
        echo "Invalid ID specified.";
        exit; // Stop execution for invalid input
    }

    try {
        $pdoConnect->beginTransaction();

        // Correct DELETE statement
        $deleteSql = "DELETE FROM php_mailer_configuration WHERE id = :id";
        $deleteStmt = $pdoConnect->prepare($deleteSql);
        $deleteStmt->bindParam(':id', $id);
        $deleteStmt->execute();

        // Check if any rows were affected
        if ($deleteStmt->rowCount() > 0) {
            $pdoConnect->commit();
            // Provide feedback to the user
            header("Location: ../mailer-configuration.php?status=success");
            exit;
        } else {
            $pdoConnect->rollBack();
            header("Location: ../mailer-configuration.php?status=not_found");
            exit; // Redirect if no record was found to delete
        }
    } catch (PDOException $e) {
        $pdoConnect->rollBack();
        echo "Database error: " . $e->getMessage();
        exit;
    }
} else {
    echo "No ID specified for deletion.";
}
