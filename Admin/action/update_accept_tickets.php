<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acceptTickets = $_POST['accept_tickets'];
    $updateQuery = $pdoConnect->prepare("UPDATE settings SET accept_tickets = :accept_tickets WHERE id = :id");
    $updateQuery->execute(['accept_tickets' => $acceptTickets, 'id' => 1]);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'fail']);
}
