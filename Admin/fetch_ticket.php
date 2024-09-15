<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["admin_number"])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["admin_number"];

    $pdoUserQuery = "SELECT * FROM mis_employees WHERE admin_number = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();
    $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

    if ($Data) {
        $ticket_user = isset($_GET["id"]) && $_GET["id"] == 1 ? "Student" : "Employee";

        $pdoQuery = "SELECT * FROM tb_tickets WHERE status = 'Pending' AND user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();

        $tickets = [];
        while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
            $tickets[] = $row;
        }

        echo json_encode($tickets);
    } else {
        echo json_encode(['error' => 'No data found']);
    }
}
?>
