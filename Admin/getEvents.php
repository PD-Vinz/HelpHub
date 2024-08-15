<?php
// Database connection
$host = 'localhost'; // Your database host
$dbname = 'helphub'; // Your database name
$username = ''; // Your database username
$password = ''; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get events
    $stmt = $pdo->query('SELECT id, event_date, event_title, event_description FROM tb_calendar');

    // Fetch all results as an associative array
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as a JSON object
    echo json_encode($events);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>