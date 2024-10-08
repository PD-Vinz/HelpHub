<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

if (!isset($_SESSION["address"]) && !isset($_SESSION["user"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} elseif (!isset($_GET['regenerate'])) {
    if (isset($_SESSION['otp_generated']) && $_SESSION['otp_generated'] === true) {
        header("Location: otp.php?generated=yes");
        exit;
    }
}
$Address = $_SESSION["address"];
$User = $_SESSION["user"];
$id = $_SESSION["forgot_id"];

// Include PHPMailer library
require '../vendor/autoload.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOTP($recipientEmail, $otp, $pdoConnect) {
    global $pdoConnect; // Ensure $pdoConnect is accessible
    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format');
    }

    $mail = new PHPMailer(true); // Create a new PHPMailer instance

    $pdoQuery = "SELECT * FROM php_mailer_configuration WHERE email_purpose = 'OTP' && status = 'Active'";
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

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $port;

        // Recipients
        $mail->setFrom($address, $name);
        $mail->addAddress($recipientEmail);

        // Load the HTML template and replace OTP
        $templateFile = 'template.php';
        if (file_exists($templateFile)) {
            $htmlContent = file_get_contents($templateFile);
            $htmlContent = str_replace('$otp', $otp, $htmlContent);
        } else {
            die('Template file not found');
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your One-Time Password (OTP)';
        $mail->Body = $htmlContent;
        $mail->AltBody = "Your OTP code is: $otp"; // Plain text version

        $mail->send();
        echo 'OTP has been sent';
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_generated'] = true;
        $_SESSION['otp_time'] = time(); // Store the current timestamp
        header("Location: otp.php");
        exit(); // Prevent further execution after redirection
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Example usage
$otp = rand(100000, 999999); // Generate a 6-digit OTP
sendOTP($Address, $otp, $pdoConnect); // Call with $pdoConnect
?>
