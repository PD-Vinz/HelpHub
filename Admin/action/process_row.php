<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php';

if (isset($_POST['row_data']) && isset($_POST['columns'])) {
    $rowData = json_decode($_POST['row_data'], true);
    $columns = json_decode($_POST['columns'], true);

    try {
        // Prepare the SQL statement dynamically based on columns
        $placeholders = array_fill(0, count($columns), '?');
        $sql = sprintf(
            "INSERT INTO student_user (%s) VALUES (%s)",
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        $stmt = $pdoConnect->prepare($sql);

        // Execute SQL statement with the current row data
        $stmt->execute($rowData);

        // Send email using PHPMailer
        $emailIndex = array_search('email_address', $columns);
        $userIdIndex = array_search('user_id', $columns);
        $firstNameIndex = array_search('first_name', $columns);

        $pdoQuery = "SELECT * FROM php_mailer_configuration WHERE email_purpose = 'Notification' AND status = 'Active'";
        $pdoResult = $pdoConnect->prepare($pdoQuery);
        if (!$pdoResult->execute()) {
            die('Error fetching mailer configuration');
        }
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $host = $Data['host'];
            $username = $Data['username'];
            $password = $Data['password'];
            $port = $Data['port'];
            $address = $Data['address'];
            $name = $Data['name'];
        }

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $port;

        $mail->setFrom($address, $name);
        $mail->addAddress($rowData[$emailIndex]);
        $mail->isHTML(true);
        $mail->Subject = 'Your Account Information';
        $mail->Body = "Hello {$rowData[$firstNameIndex]},<br>Your User ID: {$rowData[$userIdIndex]}<br>Password: {$rowData[$userIdIndex]}";

        $mail->send();

        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
?>
