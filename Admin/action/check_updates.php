<?php
// Database connection (using PDO for security and flexibility)
include_once("../../connection/conn.php");
$pdoConnect = connection();

try {
    // Query to get the timestamp of the latest update
    $stmt = $pdoConnect->query("SELECT MAX(updated_at) AS lastUpdate FROM tb_tickets");

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $lastUpdate = $row['lastUpdate'];

    // Return the latest update timestamp in JSON format
    echo json_encode(['lastUpdate' => $lastUpdate]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
