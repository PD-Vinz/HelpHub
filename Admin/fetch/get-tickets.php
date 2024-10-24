<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

try {
    include_once("../../connection/conn.php");
    $pdoConnect = connection();

    // Your database connection and query logic here
    $query = "SELECT ticket_id, status, employee, created_date, full_name, issue FROM tb_tickets";
    $stmt = $pdoConnect->query($query);
    $ticket = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If no tickets are found, return an empty array instead of null
    if (!$ticket) {
        $ticket = [];
    }

    // Clear any output that might have been generated
    ob_clean();

    // Set the correct content type
    header('Content-Type: application/json');

    // Output the JSON data
    echo json_encode([
        'data' => $ticket,
        'count' => count($ticket),
        'query' => $query,
        'error' => null
    ]);
} catch (PDOException $e) {
    // Clear any output that might have been generated
    ob_clean();

    // Set the correct content type
    header('Content-Type: application/json');

    // Log the error
    error_log('Database error in get-tickets.php: ' . $e->getMessage());

    // Return a JSON error response
    echo json_encode([
        'error' => 'An error occurred while fetching tickets',
        'details' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
} catch (Exception $e) {
    // Clear any output that might have been generated
    ob_clean();

    // Set the correct content type
    header('Content-Type: application/json');

    // Log the error
    error_log('Error in get-tickets.php: ' . $e->getMessage());

    // Return a JSON error response
    echo json_encode([
        'error' => 'An error occurred while processing your request',
        'details' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
}

// End output buffering and flush output
ob_end_flush();