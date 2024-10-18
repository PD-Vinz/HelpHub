<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/HelpHub/vendor/phpmailer/src/Exception.php';
require 'C:/xampp/htdocs/HelpHub/vendor/phpmailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/HelpHub/vendor/phpmailer/src/SMTP.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $smtpSecure = $_POST['smtpsecure'];
    $port = $_POST['port'];

    // Map string to PHPMailer constants
    if ($smtpSecure === "PHPMailer::ENCRYPTION_STARTTLS") {
        $smtpSecure = PHPMailer::ENCRYPTION_STARTTLS;
    } elseif ($smtpSecure === "PHPMailer::ENCRYPTION_SMTPS") {
        $smtpSecure = PHPMailer::ENCRYPTION_SMTPS;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $smtpSecure; // Apply the mapped constant here
        $mail->Port = $port;

        // Attempt to connect to the SMTP server
        if ($mail->smtpConnect()) {
            $mail->smtpClose();
            $response['success'] = true;
            $response['message'] = 'SMTP settings are valid and the connection was successful.';
        } else {
            throw new Exception("Failed to connect to SMTP server.");
        }
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = 'SMTP Error: ' . $mail->ErrorInfo;
    }
}

// Send JSON response back to the front-end
header('Content-Type: application/json');
echo json_encode($response);
