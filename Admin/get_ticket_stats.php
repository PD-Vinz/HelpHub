<?php
// File: get_ticket_stats.php
include_once("../connection/conn.php");
require_once('../connection/bdd.php');

$pdoConnect = connection();

try {
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
        $pdoCountQuery = "SELECT COUNT(*) as count FROM tb_tickets WHERE status = :status";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->execute(['status' => $status]);
        $count = $pdoResult->fetch(PDO::FETCH_ASSOC)['count'];
        $stats[$keys[$index]] = $count;
    }

    // Handle priority tickets (YES in the priority column) with status either Pending or Processing
    $pdoPriorityQuery = "SELECT COUNT(*) as count FROM tb_tickets WHERE priority = 'YES' AND (status = 'Pending' OR status = 'Processing')";
    $pdoPriorityResult = $pdoConnect->prepare($pdoPriorityQuery);
    $pdoPriorityResult->execute();
    $priorityCount = $pdoPriorityResult->fetch(PDO::FETCH_ASSOC)['count'];
    $stats['priority'] = $priorityCount;

    header('Content-Type: application/json');
    echo json_encode($stats);
} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
