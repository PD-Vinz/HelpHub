<?php
ob_start(); // Start output buffering

include_once("../../connection/conn.php");
$pdoConnect = connection();

// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php'; // Path to your PHPMailer autoload file

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

        // Default profile picture content
        $defaultPhoto = file_get_contents(DEFAULT_PHOTO);

        // Add 'profile_picture' to columns if not present
        if (!in_array('profile_picture', $columns)) {
            $columns[] = 'profile_picture';
        }

        // Prepare SQL query dynamically
        $placeholders = array_fill(0, count($columns), '?');
        $sql = sprintf(
            "INSERT INTO student_user (%s) VALUES (%s)",
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        $stmt = $pdoConnect->prepare($sql);

        // Determine the index positions
        $userIdIndex = array_search('user_id', $columns);
        $passwordIndex = array_search('password', $columns);
        $birthdayIndex = array_search('birthday', $columns);

        $template = file_get_contents(__DIR__ . '/email_template.html');

        // Loop through the CSV data and insert each row
        foreach ($csvData as $index => $data) {
            if ($index === 0) continue; // Skip header row

            // Validate row length
            if (count($data) !== count($columns) - 1) {
                echo "Row $index skipped: Column count mismatch.<br>";
                continue;
            }

            // Set profile picture if missing
            if (count($data) === count($columns) - 1) {
                $data[] = $defaultPhoto;
            }

            // Only convert birthday format if present
            if ($birthdayIndex !== false && isset($data[$birthdayIndex])) {
                $data[$birthdayIndex] = date('Y-m-d', strtotime($data[$birthdayIndex]));
            }

            // Ensure password matches user_id if password column exists
            if ($passwordIndex !== false && $userIdIndex !== false) {
                $data[$passwordIndex] = $data[$userIdIndex];
            }

            // Check for existing record with the same user_id
            $checkStmt = $pdoConnect->prepare("SELECT COUNT(*) FROM student_user WHERE user_id = ?");
            $checkStmt->execute([$data[$userIdIndex]]);
            if ($checkStmt->fetchColumn()) continue;

            // Insert data
            $stmt->execute($data);

            // Send email with user ID and password
            $emailColumnIndex = array_search('email_address', $columns);
            $userIdColumnIndex = array_search('user_id', $columns);
            $passwordColumnIndex = array_search('password', $columns);
            $userNameColumnIndex = array_search('first_name', $columns); // Assuming 'name' is the user's name column

            // Extract email, user ID, and password based on column indices
            $email = $data[$emailColumnIndex];
            $userId = $data[$userIdColumnIndex];
            $password = $data[$passwordColumnIndex];
            $userName = $data[$userNameColumnIndex] ?? 'User'; // Use 'User' if no name provided

            // Replace placeholders in the email template
            $emailBody = str_replace(
                ['{{USER_NAME}}', '{{USER_ID}}', '{{PASSWORD}}'],
                [$userName, $userId, $password],
                $template
            );

            // Initialize PHPMailer
            $mail = new PHPMailer(true);

            // Fetch mailer configuration from the database
            $pdoQuery = "SELECT * FROM php_mailer_configuration WHERE email_purpose = 'Notification' AND status = 'Active'";
            $pdoResult = $pdoConnect->prepare($pdoQuery);
            if (!$pdoResult->execute()) {
                die('Error fetching mailer configuration');
            }
            $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

            if ($Data) {
                $host = $Data['host'];
                $username = $Data['username'];
                $mailPassword = $Data['password'];
                $port = $Data['port'];
                $address = $Data['address'];
                $name = $Data['name'];
            }

            try {
                $mail->isSMTP();
                $mail->Host = $host;
                $mail->SMTPAuth = true;
                $mail->Username = $username;
                $mail->Password = $mailPassword;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = $port;

                $mail->setFrom($address, $name);
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your Account Information';
                $mail->Body = $emailBody;

                $mail->send();
            } catch (Exception $e) {
                echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }

        header("Location: ../user-student-list.php");
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
