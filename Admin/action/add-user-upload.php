<?php
ob_start(); // Start output buffering

include_once("../../connection/conn.php");
$pdoConnect = connection();

if (isset($_POST['csv_data'])) {
    // Deserialize the CSV data
    $csvData = unserialize($_POST['csv_data']);
    $filePath = $_POST['file_path'];

    // Default profile picture path
    define('DEFAULT_PHOTO', __DIR__ . '/No-Profile.png');

    try {

        // Get the header row to construct the column names
        $headerRow = $csvData[0];
        $columns = array_map('trim', $headerRow);

        // Default profile picture path
        $imgContent = DEFAULT_PHOTO; // Use defined constant

        $defaultPhoto= file_get_contents($imgContent);


        // Add profile_picture to columns if not present
        if (!in_array('profile_picture', $columns)) {
            $columns[] = 'profile_picture';
        }

        // Prepare the SQL query dynamically
        $placeholders = array_fill(0, count($columns), '?');
        $sql = sprintf(
            "INSERT INTO student_user (%s) VALUES (%s)",
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        $stmt = $pdoConnect->prepare($sql);

        // Loop through the CSV data and insert each row
        foreach ($csvData as $index => $data) {
            if ($index === 0) {
                // Skip the header row
                continue;
            }

            // Debugging: Print the current row data
            //echo "Row $index data: ";
            //print_r($data);
            //echo "<br>";

            // Ensure the data array has the correct number of elements
            if (count($data) !== count($columns) - 1) {
                //echo "Error: Row $index does not match the column count.<br>";
                //echo "Expected: " . (count($columns) - 1) . ", Got: " . count($data) . "<br>";
                continue;
            }

            // Add default photo value if profile_picture column is present
            if (count($data) == count($columns) - 1) {
                $data[] = $defaultPhoto;
                echo "Data with default profile picture: ";
                print_r($data);
                echo "<br>";
            }

            // Convert date format if necessary
            if (isset($data[array_search('birthday', $headerRow)])) {
                $data[array_search('birthday', $headerRow)] = date('Y-m-d', strtotime($data[array_search('birthday', $headerRow)])); // Ensure date format is YYYY-MM-DD
            }

            // Add user_id as password
            $passwordColumnIndex = array_search('password', $columns);
            if ($passwordColumnIndex !== false) {
                $data[$passwordColumnIndex] = $data[0]; // Assuming user_id is the first column
            }

            // Debugging: Print SQL query and data
            //echo "SQL Query: $sql<br>";
            //echo "Data to insert: ";
            //print_r($data);
            //echo "<br>";

            // Check for existing record with the same primary key
            $checkStmt = $pdoConnect->prepare("SELECT COUNT(*) FROM student_user WHERE user_id = ?");
            $checkStmt->execute([$data[0]]);
            $exists = $checkStmt->fetchColumn();

            // Debugging: Print the result of the duplicate check
            //echo "Duplicate check for user_id {$data[0]}: " . ($exists ? 'Yes' : 'No') . "<br>";

            if ($exists) {
                //echo "Duplicate entry for user_id {$data[0]}. Skipping insertion.<br>";
                continue; // Skip the insertion
            }

            // Bind parameters and execute
            $stmt->execute($data);
        }

        //echo "Data inserted successfully.<br>";
        header("Location: ../user-student-list.php");
        // Optionally, delete the uploaded file after processing
        unlink($filePath);
        exit;

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    echo "No data to process.";
}

ob_end_flush(); // Send the output to the browser
?>
