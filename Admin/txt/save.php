<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get contents from POST request
    $content1 = $_POST['content1'];
    $content2 = $_POST['content2'];
    $content3 = $_POST['content3'];

    // Define the file paths
    $file1 = 'campus.txt';
    $file2 = 'department.txt';
    $file3 = 'course.txt';

    // Try saving both files
    $success1 = file_put_contents($file1, $content1);
    $success2 = file_put_contents($file2, $content2);
    $success3 = file_put_contents($file3, $content3);

    // Check for success
    if ($success1 !== false && $success2 !== false && $success3 !== false) {
        echo 'Files saved successfully';
    } else {
        http_response_code(500);
        echo 'Error saving files';
    }
} else {
    http_response_code(405);
    echo 'Method not allowed';
}
?>
