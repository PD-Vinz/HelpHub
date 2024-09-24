<?php
// Database connection (using PDO for security and flexibility)
include_once("../../connection/conn.php");
$pdoConnect = connection();

try {
    // Query to get the timestamp of the latest update from the 'tb_tickets' table
    $stmt = $pdoConnect->query("SELECT MAX(updated_at) AS lastUpdate FROM tb_tickets");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $dbLastUpdate = $row['lastUpdate']; // Latest database update timestamp

    // Return the database update time in JSON format
    echo json_encode(['dbLastUpdate' => $dbLastUpdate]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
