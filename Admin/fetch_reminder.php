<?php
date_default_timezone_set('Asia/Manila');

function connection(): PDO {
    try {
        $pdoConnect = new PDO('mysql:host=localhost;dbname=helphub', 'root', '');
        $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdoConnect;
    } catch (PDOException $e) {
        throw new Exception("Connection Failed: " . $e->getMessage());
    }
}

function getReminders() {
    $pdo = connection();

    $sql = "SELECT id, event_date, event_title, event_description, event_date FROM tb_calendar ORDER BY event_date";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');
echo json_encode(getReminders());
?>
