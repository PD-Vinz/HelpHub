<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();


header('Content-Type: application/json');

$positionId = isset($_GET['position_id']) ? $_GET['position_id'] : '';

if ($positionId) {
    // Replace with your actual database connection
    $stmt = $pdoConnect->prepare('SELECT admin_number, f_name FROM mis_employees WHERE position = :position_id');
    $stmt->execute(['position_id' => $positionId]);

    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($employees);
} else {
    echo json_encode([]);
}
