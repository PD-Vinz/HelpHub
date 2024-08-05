<?php
// Define the directory path
$directory = __DIR__ . '/Templates/';

// Initialize an array to hold the file list
$fileList = [];

// Open the directory
if ($handle = opendir($directory)) {
    // Loop through the directory entries
    while (false !== ($entry = readdir($handle))) {
        // Check for .txt files
        if ($entry !== '.' && $entry !== '..' && pathinfo($entry, PATHINFO_EXTENSION) === 'txt') {
            // Add the file to the list
            $fileList[] = $entry;
        }
    }
    // Close the directory
    closedir($handle);
}

// Return the list as a JSON response
header('Content-Type: application/json');
echo json_encode($fileList);
?>
