<?php
date_default_timezone_set('Asia/Manila');

function connection(): PDO {

try {
    $pdoConnect = new PDO('mysql:host=localhost;dbname=helphub', 'root', '');
    $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdoConnect;
}
catch (PDOException $e){
    throw new Exception("Connection Failed: " . $e->getMessage());
}
}
?>