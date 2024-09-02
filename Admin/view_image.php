<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

// Retrieve ticket ID from the URL parameter
$ticket_id = isset($_GET['id']) ? $_GET['id'] : null;

// Ensure that $ticket_id is valid and sanitize it
if ($ticket_id === null || !is_numeric($ticket_id)) {
    die('Invalid ID');
}

// Query the database to get the screenshot
$pdoQuery = $pdoConnect->prepare("SELECT screenshot FROM tb_tickets WHERE ticket_id = :id");
$pdoQuery->execute(array(':id' => $ticket_id));
$pdoResult = $pdoQuery->fetch(PDO::FETCH_ASSOC);

// Check if image data is found
if ($pdoResult && isset($pdoResult['screenshot'])) {
    // Encode image data in Base64
    $screenshotBase64 = base64_encode($pdoResult['screenshot']);
    $imageType = 'image/jpeg'; // Change to the correct image type if needed

    // Set the content type header
    header('Content-Type: ' . $imageType);
    echo base64_decode($screenshotBase64); // Output the image data
} else {
    // Handle case where no image is found
    header('HTTP/1.0 404 Not Found');
    echo 'Image not found';
}

$pdoConnect = null;
?>
