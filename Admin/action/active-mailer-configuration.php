<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["admin_number"])) {
    header("Location: ../index.php");
    exit();
} else {
    $id = $_SESSION["admin_number"];

    $userQuery = "SELECT * FROM mis_employees WHERE admin_number = :number";
    $pdoResult = $pdoConnect->prepare($userQuery);
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
        echo "No student found with the given student number.";
    }
}

if (isset($_GET['id']) && isset($_GET['name'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $purpose = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);

    try {
        $pdoConnect->beginTransaction();

        $updateSql = "UPDATE php_mailer_configuration SET status = 'Inactive' WHERE email_purpose = :purpose";
        $updateStmt = $pdoConnect->prepare($updateSql);
        $updateStmt->bindParam(':purpose', $purpose);
        $updateStmt->execute();

        $updateSql = "UPDATE php_mailer_configuration SET status = 'Active' WHERE id = :id";
        $updateStmt = $pdoConnect->prepare($updateSql);
        $updateStmt->bindParam(':id', $id);
        $updateStmt->execute();

        $pdoConnect->commit();

        // Provide feedback to the user
        header("Location: ../mailer-configuration.php?status=success");
        exit;
    } catch (PDOException $e) {
        $pdoConnect->rollBack();
        echo "Database error: " . $e->getMessage();
        exit;
    }
}
