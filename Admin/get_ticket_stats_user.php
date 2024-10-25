<?php
// File: get_ticket_stats_student.php
include_once("../connection/conn.php");
require_once('../connection/bdd.php');

$pdoConnect = connection();

try {
    // Validate the id parameter
    if (!isset($_GET["id"]) || !in_array($_GET["id"], ['1', '2'])) {
        throw new Exception("Invalid or missing id parameter");
    }

    $ticket_user = ($_GET["id"] == '1') ? "Student" : "Employee";

    $stats = [
        'pending' => 0,
        'processing' => 0,
        'resolved' => 0,
        'returned' => 0,
        'priority' => 0
    ];

    $statuses = ['Pending', 'Processing', 'Resolved', 'Returned'];
    $keys = ['pending', 'processing', 'resolved', 'returned'];

    foreach ($statuses as $index => $status) {
        $pdoCountQuery = "SELECT COUNT(*) as count FROM tb_tickets WHERE status = :status AND user_type = :user_type";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->execute(['status' => $status, 'user_type' => $ticket_user]);
        $count = $pdoResult->fetch(PDO::FETCH_ASSOC)['count'];
        $stats[$keys[$index]] = $count;
    }

    // Handle 'Priority' tickets
    $pdoCountQuery = "SELECT COUNT(*) as count FROM tb_tickets WHERE user_type = :user_type AND (status = 'Pending' OR status = 'Processing')";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute(['user_type' => $ticket_user]);
    $count = $pdoResult->fetch(PDO::FETCH_ASSOC)['count'];
    $stats['priority'] = $count;

    header('Content-Type: application/json');
    echo json_encode($stats);
} catch (Exception $e) {
    error_log('Error in get_ticket_stats_student.php: ' . $e->getMessage());
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    // Close the database connection
    $pdoConnect = null;
}