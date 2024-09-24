<?php
// File path
$file = 'data.txt'; // Path to the file to be updated

// Get the latest update timestamp from the POST request
if (isset($_POST['lastUpdate'])) {
    $lastUpdate = $_POST['lastUpdate'];

    // Write the new update timestamp to the file
    file_put_contents($file, $lastUpdate);

    // Respond with success
    echo json_encode(['status' => 'File updated successfully']);
} else {
    echo json_encode(['status' => 'No update provided']);
}
?>
