<?php

header('Content-Type: application/json');
include_once("../../connection/conn.php");
$pdoConnect = connection();

try {

    if (isset($_GET["id"]) && $_GET["id"] == 1) {

// Get the chart type from the query parameter
$chartType = $_GET['chart'] ?? '';

// Define the SQL query based on the chart type
switch ($chartType) {
    case 'age-groups':
        $stmt = $pdoConnect->query('SELECT age AS label, COUNT(*) as value FROM tb_tickets WHERE user_type = "Student" && status = "Resolved" GROUP BY age');
        break;
    case 'genders':
        $stmt = $pdoConnect->query('SELECT sex AS label, COUNT(*) as value FROM tb_tickets WHERE user_type = "Student" && status = "Resolved" GROUP BY sex');
        break;
    case 'locations':
        $stmt = $pdoConnect->query('SELECT campus AS label, COUNT(*) as value FROM tb_tickets WHERE user_type = "Student" && status = "Resolved" GROUP BY campus');
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
        $stmt = $pdoConnect->query('SELECT age AS label, COUNT(*) as value FROM tb_tickets WHERE user_type = "Employee" && status = "Resolved" GROUP BY age');
        break;
    case 'genders':
        $stmt = $pdoConnect->query('SELECT sex AS label, COUNT(*) as value FROM tb_tickets WHERE user_type = "Employee" && status = "Resolved" GROUP BY sex');
        break;
    case 'locations':
        $stmt = $pdoConnect->query('SELECT campus AS label, COUNT(*) as value FROM tb_tickets WHERE user_type = "Employee" && status = "Resolved" GROUP BY campus');
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


