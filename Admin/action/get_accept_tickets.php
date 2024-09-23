<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

$query = $pdoConnect->prepare("SELECT accept_tickets FROM settings WHERE id = :id");
$query->execute(['id' => 1]);
$result = $query->fetch(PDO::FETCH_ASSOC);

echo json_encode(['accept_tickets' => $result['accept_tickets']]);
