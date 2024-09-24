<?php
header('Content-Type: application/json');
include_once("../../connection/conn.php");
$pdoConnect = connection();

try {
    // Get the current year
    $currentYear = date('Y');
    
    // Get the chart type and employee parameter from the query string
    $chartType = $_GET['chart'] ?? '';
    $employee = $_GET['employee'] ?? '';

    switch ($chartType) {
        case 'all':
            // Prepare the SQL query
            $stmt = $pdoConnect->prepare('
                SELECT status AS label, COUNT(*) AS value
                FROM tb_tickets
                WHERE employee = :employee
                AND YEAR(opened_date) = :currentYear
                GROUP BY status
            ');
            break;
        case 'student':
            // Prepare the SQL query
            $stmt = $pdoConnect->prepare('
                SELECT status AS label, COUNT(*) AS value
                FROM tb_tickets
                WHERE employee = :employee
                AND YEAR(opened_date) = :currentYear
                AND user_type = "Student"
                GROUP BY status
            ');
            break;
        case 'employee':
            // Prepare the SQL query
            $stmt = $pdoConnect->prepare('
                SELECT status AS label, COUNT(*) AS value
                FROM tb_tickets
                WHERE employee = :employee
                AND YEAR(opened_date) = :currentYear
                AND user_type = "Employee"
                GROUP BY status
            ');
            break;
        default:
            echo json_encode(['error' => 'Invalid chart type']);
            exit;
    }

    // Bind the parameters
    $stmt->bindParam(':employee', $employee, PDO::PARAM_STR);
    $stmt->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
    
    // Execute the query
    $stmt->execute();

    // Fetch the data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if data is empty
    if (empty($data)) {
        echo json_encode(['error' => 'No data found']);
    } else {
        echo json_encode($data);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
?>
