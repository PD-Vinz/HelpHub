<?php
// File: get_events.php
include_once("../connection/conn.php");
require_once('../connection/bdd.php');

$pdoConnect = connection();

$start = $_GET['start'];
$end = $_GET['end'];

$sql = "SELECT id, title, start, end, color FROM events WHERE start BETWEEN :start AND :end";
$req = $pdoConnect->prepare($sql);
$req->bindParam(':start', $start, PDO::PARAM_STR);
$req->bindParam(':end', $end, PDO::PARAM_STR);
$req->execute();

$events = $req->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($events);