<?php

header('Content-Type: application/json');

$dsn = 'mysql:host=localhost;dbname=helphub;charset=utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);


    if (isset($_GET["id"]) && $_GET["id"] == 1) {

// Get the chart type from the query parameter
$chartType = $_GET['chart'] ?? '';

// Define the SQL query based on the chart type
switch ($chartType) {
    case 'age-groups':
        $stmt = $pdo->query('SELECT age AS label, COUNT(*) as value FROM tb_tickets WHERE user_type = "Student" GROUP BY age');
        break;
    case 'genders':
        $stmt = $pdo->query('SELECT sex AS label, COUNT(*) as value FROM tb_tickets WHERE user_type = "Student" GROUP BY sex');
        break;
    case 'locations':
        $stmt = $pdo->query('SELECT campus AS label, COUNT(*) as value FROM tb_tickets WHERE user_type = "Student" GROUP BY campus');
        break;
    default:
        echo json_encode([]);
        exit;
}

// Fetch data and return as JSON
$data = $stmt->fetchAll();
echo json_encode($data);


    } elseif (isset($_GET["id"]) && $_GET["id"] == 2) {

// Get the chart type from the query parameter
$chartType = $_GET['chart'] ?? '';

// Define the SQL query based on the chart type
switch ($chartType) {
    case 'age-groups':
        $stmt = $pdo->query('SELECT age AS label, COUNT(*) as value FROM tb_tickets WHERE user_type = "Employee" GROUP BY age');
        break;
    case 'genders':
        $stmt = $pdo->query('SELECT sex AS label, COUNT(*) as value FROM tb_tickets WHERE user_type = "Employee" GROUP BY sex');
        break;
    case 'locations':
        $stmt = $pdo->query('SELECT campus AS label, COUNT(*) as value FROM tb_tickets WHERE user_type = "Employee" GROUP BY campus');
        break;
    default:
        echo json_encode([]);
        exit;
}

// Fetch data and return as JSON
$data = $stmt->fetchAll();
echo json_encode($data);
    }


} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}


