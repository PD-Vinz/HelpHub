<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Include PHPMailer library
require '../vendor/autoload.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendTicketConfirmation($recipientEmail, $userName, $status, $issue, $description, $dateCreated, $imageUrl, $websiteUrl) {
    global $pdoConnect; // Ensure $pdoConnect is accessible
    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format');
    }

    $mail = new PHPMailer(true); // Create a new PHPMailer instance

    $pdoQuery = "SELECT * FROM php_mailer_configuration WHERE email_purpose = 'Notification' && status = 'Active'";
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
        $mail->Host       = $host;                       // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $username;                 // SMTP username
        $mail->Password   = $password;                    // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
        $mail->Port       = $port;                                    // TCP port to connect to

        // Recipients
        $mail->setFrom($address, $name);
        $mail->addAddress($recipientEmail);                         // Add a recipient

        // Load the HTML template and replace placeholders
        $htmlContent = file_get_contents('template.php'); // Ensure this path is correct
        $htmlContent = str_replace(
            ['{UserName}', '{Status}', '{Issue}', '{Description}', '{DateCreated}', '{ImageUrl}', '{WebsiteUrl}'],
            [$userName, $status, $issue, $description, $dateCreated, $imageUrl, $websiteUrl],
            $htmlContent
        );

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'Your Ticket Has Been Received';
        $mail->Body    = $htmlContent;

        $mail->AltBody = "Your ticket details:\nIssue: $issue\nDescription: $description\nDate Created: $dateCreated"; // Plain text version for non-HTML mail clients

        $mail->send();
        echo 'Ticket confirmation has been sent';
        header("Location: receive-ticket-response.php"); // Redirect after sending email
        exit(); // Prevent further execution after redirection
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Example usage
$Address = $_SESSION["Address"];
$userName = $_SESSION["userName"]; // Retrieve or set these values as needed
$status = $_SESSION["status"];
$issue = $_SESSION["issue"];
$description = $_SESSION["description"];
$dateCreated = $_SESSION["dateCreated"];
$imageUrl = $_SESSION["imageUrl"];
$websiteUrl = $_SESSION["websiteUrl"];

sendTicketConfirmation($Address, $userName, $status, $issue, $description, $dateCreated, $imageUrl, $websiteUrl);
?>
