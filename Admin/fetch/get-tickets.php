<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

header('Content-Type: application/json');

try {
      $pdoCountQuery = "SELECT * FROM tb_tickets";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->execute();
        $allTickets = $pdoResult->rowCount();

        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Pending' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();

    echo json_encode($tickets);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
