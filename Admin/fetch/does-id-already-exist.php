<?php
include_once("../../connection/conn.php"); // Make sure this path is correct
$pdoConnect = connection(); // Assuming this function returns a PDO connection

session_start();

// Get the User ID from the query parameter
$userId = isset($_GET['userid']) ? $_GET['userid'] : '';
$usertype = isset($_GET['usertype']) ? $_GET['usertype'] : '';

// Prepare and execute the SQL statement
if ($usertype == "Student"){
    $stmt = $pdoConnect->prepare("SELECT COUNT(*) FROM student_user WHERE user_id = :userid");
} elseif ($usertype == "Employee") {
    $stmt = $pdoConnect->prepare("SELECT COUNT(*) FROM employee_user WHERE user_id = :userid");
}

$stmt->bindParam(':userid', $userId, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->fetchColumn(); // Fetch the count directly

// Determine if the User ID is available
$isAvailable = ($count == 0);

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode(['available' => $isAvailable]);

// Close the database connection
$pdoConnect = null; // Close the PDO connection

