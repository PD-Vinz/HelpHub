<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get contents from POST request
    $content1 = $_POST['content1'];
    $content2 = $_POST['content2'];

    // Define the file paths
    $file1 = '../issue-template/employee-issue.txt';
    $file2 = '../issue-template/student-issue.txt';

    // Try saving both files
    $success1 = file_put_contents($file1, $content1);
    $success2 = file_put_contents($file2, $content2);

    // Check for success
    if ($success1 !== false && $success2 !== false) {
        echo 'templates.php';
    } else {
        http_response_code(500);
        echo 'Error saving files';
    }
} else {
    http_response_code(405);
    echo 'Method not allowed';
}
?>
