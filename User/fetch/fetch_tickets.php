<?php
try {
    $status = "Pending";
    $pdoQuery = "SELECT * FROM tb_tickets WHERE status = :status AND user_number = :usernumber";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->bindParam(':status', $status, PDO::PARAM_STR);
    $pdoResult->bindParam(':usernumber', $usernumber, PDO::PARAM_INT);
    $pdoExec = $pdoResult->execute();

    if ($pdoExec) {
        $tickets = array();
        while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
            $tickets[] = $row;
        }
        echo json_encode($tickets);
    } else {
        echo json_encode(['error' => 'Query execution failed']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
