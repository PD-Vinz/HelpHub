<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

if (!isset($_SESSION["address"]) && !isset($_SESSION["user"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} elseif (!isset($_GET['regenerate'])){

if (isset($_POST['Verify_Account'])) {
    if (isset($_SESSION['otp_generated']) && $_SESSION['otp_generated']) {
        header("Location: account-validation.php?generated=yes");
        exit;
    }
}
}

$Address = $_SESSION["address"];
$User = $_SESSION["user"];
$id = $_SESSION["first-time"];


// Include PHPMailer library
require '../vendor/autoload.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOTP($recipientEmail, $otp) {
    global $pdoConnect; // Ensure $pdoConnect is accessible
    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format');
    }

    $mail = new PHPMailer(true); // Create a new PHPMailer instance

    $pdoQuery = "SELECT * FROM php_mailer_configuration WHERE email_purpose = 'OTP' && status = 'active'";
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
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = $host;                       // Specify main and backup SMTP servers "smtp.gmail.com"
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $username;                 // SMTP username
        $mail->Password   = $password;                    // SMTP password (App Password if 2FA is enabled) "zowodbzxhjochnyv"
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = $port;                                    // TCP port to connect to

        // Recipients
        $mail->setFrom($address, $name);
        $mail->addAddress($recipientEmail);                         // Add a recipient

        // Load the HTML template and replace $otp
        $htmlContent = file_get_contents('template.php'); // Ensure this path is correct
        $htmlContent = str_replace('$otp', $otp, $htmlContent);

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'Your One-Time Password (OTP)';
        $mail->Body = $htmlContent; // Ensure this path is correct

                                                                    //https://drive.google.com/file/d/1tTULPtMo8vufaRhxMy-ZdpshPnFSw94M/view?usp=sharing
                                                                    //https://drive.google.com/file/d/1fXFC6pdXPFDF7PbyWIuoVhSAkfyD2J6P/view?usp=sharing
                                                                    // "Your OTP code is: <b>$otp</b>";
        $mail->AltBody = "Your OTP code is: $otp";                 // Plain text version for non-HTML mail clients

        $mail->send();
        echo 'OTP has been sent';
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_generated'] = true;
        $_SESSION['otp_time'] = time(); // Store the current timestamp
        header("Location: account-validation.php");
        exit(); // Prevent further execution after redirection
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Example usage
$otp = rand(100000, 999999); // Generate a 6-digit OTP
sendOTP($Address, $otp); // Replace with the actual recipient email





