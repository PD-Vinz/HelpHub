<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

header('Content-Type: application/json');

try {
    // Fetch distinct positions
    $stmt = $pdoConnect->prepare("SELECT DISTINCT position FROM mis_employees  WHERE position NOT LIKE '%super admin%'");
    $stmt->execute();

    $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($positions);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
