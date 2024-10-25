<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Include PHPMailer library
require '../vendor/autoload.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendTicketConfirmation($recipientEmail, $userName, $ticketid, $status, $employee, $issue, $description, $dateCreated, $dateOpened, $dateResolved, $imageUrl, $websiteUrl) {
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
        if ($status == "Resolved") {
        $htmlContent = file_get_contents('template-resolve.php'); // Ensure this path is correct
        } elseif ($status == "Returned"){
        $htmlContent = file_get_contents('template-return.php'); // Ensure this path is correct    
        }

        $htmlContent = str_replace(
            ['{UserName}', '{TicketID}', '{Status}', '{Issue}', '{Employee}', '{Description}', '{DateCreated}', '{DateOpened}', '{DateResolved}', '{ImageUrl}', '{WebsiteUrl}'],
            [$userName, $ticketid, $status, $issue, $employee, $description, $dateCreated, $dateOpened, $dateResolved, $imageUrl, $websiteUrl],
            $htmlContent
        );

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'Your issue has been '. $status .' and your ticket is now closed.';
        $mail->Body    = $htmlContent;

        $mail->AltBody = "Your ticket details:\nIssue: $issue\nDescription: $description\nDate Created: $dateCreated"; // Plain text version for non-HTML mail clients

        $mail->send();

        unset($_SESSION["Address"]);
        unset($_SESSION["userName"]);
        unset($_SESSION["ticketid"]);
        unset($_SESSION["status"]);
        unset($_SESSION["employee"]);
        unset($_SESSION["issue"]);
        unset($_SESSION["description"]);
        unset($_SESSION["dateCreated"]);
        unset($_SESSION["dateOpened"]);
        unset($_SESSION["dateResolved"]);
        unset($_SESSION["imageUrl"]);
        unset($_SESSION["websiteUrl"]);
        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Example usage
$Address = $_SESSION["Address"];
$userName = $_SESSION["userName"]; // Retrieve or set these values as needed
$ticketid = $_SESSION["ticketid"];
$status = $_SESSION["status"];
$employee = $_SESSION["employee"];
$issue = $_SESSION["issue"];
$description = $_SESSION["description"];
$dateCreated = $_SESSION["dateCreated"];
$dateOpened = $_SESSION["dateOpened"];
$dateResolved = $_SESSION["dateResolved"];
$imageUrl = $_SESSION["imageUrl"];
$websiteUrl = $_SESSION["websiteUrl"];

sendTicketConfirmation($Address, $userName, $ticketid, $status, $employee, $issue, $description, $dateCreated, $dateOpened, $dateResolved, $imageUrl, $websiteUrl);
?>
