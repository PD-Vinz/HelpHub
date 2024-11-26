<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

include_once("../../connection/conn.php"); // Ensure this path is correct
$pdoConnect = connection(); // Assuming this function returns a PDO connection

function outputJSON($data) {
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

function base64EncodeImage($image) {
    if ($image === null) {
        return null;
    }
    $type = 'image/jpeg'; // Adjust this if you have different image types
    return 'data:' . $type . ';base64,' . base64_encode($image);
}

try {
    if (!isset($_GET['query'])) {
        throw new Exception('No query provided');
    }

    $query = htmlspecialchars($_GET['query']);
    $user = htmlspecialchars($_GET['user']);

    if ($user === "Student") {
        $stmt = $pdoConnect->prepare("SELECT * FROM student_user WHERE user_id LIKE :query AND user_type = :user");
    } elseif ($user === "Employee") {
        $stmt = $pdoConnect->prepare("SELECT * FROM employee_user WHERE user_id LIKE :query AND user_type = :user");
    } else {
        throw new Exception('Invalid user type');
    }

    $stmt->execute([':query' => '%' . $query . '%', ':user' => $user]);

    $results = $stmt->fetchAll();

    // Convert BLOB to base64
    foreach ($results as &$row) {
        if (isset($row['profile_picture'])) {
            $row['profile_picture'] = base64EncodeImage($row['profile_picture']);
        }
    }

    outputJSON(['success' => true, 'data' => $results]);
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    outputJSON(['success' => false, 'error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
