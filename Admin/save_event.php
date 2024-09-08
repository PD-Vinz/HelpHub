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

function saveEvent($eventDate, $eventTitle, $eventDescription) {
    $pdo = connection();

    $sql = "INSERT INTO tb_calendar (event_date, event_title, event_description) 
            VALUES (:event_date, :event_title, :event_description)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':event_date', $eventDate);
    $stmt->bindParam(':event_title', $eventTitle);
    $stmt->bindParam(':event_description', $eventDescription);

    if ($stmt->execute()) {
        return "Event saved successfully.";
    } else {
        return "Failed to save event.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventDate = $_POST['eventDate'];
    $eventTitle = $_POST['eventTitle'];
    $eventDescription = $_POST['eventDescription'];

    echo saveEvent($eventDate, $eventTitle, $eventDescription);
}
?>
