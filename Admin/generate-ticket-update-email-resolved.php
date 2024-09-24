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
    $mail = new PHPMailer(true); // Create a new PHPMailer instance

    try {
        // Server settings
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = 'smtp-relay.brevo.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = '7acc39001@smtp-brevo.com';                 // SMTP username
        $mail->Password   = 'acd2zESVIwCT0Yyv';                    // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
        $mail->Port       = 587;                                    // TCP port to connect to

        // Recipients
        $mail->setFrom('auth.helphub@gmail.com', 'DHVSU HelpHub');
        $mail->addAddress($recipientEmail);                         // Add a recipient

        // Load the HTML template and replace placeholders
        $htmlContent = file_get_contents('template-resolve.php'); // Ensure this path is correct
        $htmlContent = str_replace(
            ['{UserName}', '{TicketID}', '{Status}', '{Issue}', '{Employee}', '{Description}', '{DateCreated}', '{DateOpened}', '{DateResolved}', '{ImageUrl}', '{WebsiteUrl}'],
            [$userName, $ticketid, $status, $issue, $employee, $description, $dateCreated, $dateOpened, $dateResolved, $imageUrl, $websiteUrl],
            $htmlContent
        );

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'Your issue has been resolved and your ticket is now closed.';
        $mail->Body    = $htmlContent;

        $mail->AltBody = "Your ticket details:\nIssue: $issue\nDescription: $description\nDate Created: $dateCreated"; // Plain text version for non-HTML mail clients

        $mail->send();

        $USER_TYPE = $_SESSION["USER_TYPE"];

        // Check USER_TYPE and redirect accordingly
        if ($USER_TYPE == 'Student') {
            header("Location: ticket-opened.php?id=1"); // Redirect after sending email
            exit; // Prevent further execution after redirection
        } elseif ($USER_TYPE == 'Employee') {
            header("Location: ticket-opened.php?id=2"); // Redirect after sending email
            exit; // Prevent further execution after redirection
        } else {
            // Handle unexpected USER_TYPE
            echo "Unexpected user type.";
        }
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
