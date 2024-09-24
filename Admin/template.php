<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        h1 {
            color: #4CAF50;
        }
        .details {
            margin-bottom: 20px;
        }
        .details dt {
            font-weight: bold;
        }
        .details dd {
            margin: 0 0 10px 0;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
        }
        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 150px;
            height: auto;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://drive.google.com/uc?export=view&id=1tTULPtMo8vufaRhxMy-ZdpshPnFSw94M" alt="Management Information System">
        </div>

        <h1>Dear {UserName},</h1>
        <p>Your ticket has been opened and is currently being processed by the MIS Employee. Below are the details of your submission:</p>

        <div class="details">
            <dl>
                <dt>Ticket Number:</dt>
                <dd>{TicketID}</dd>
                <dt>Status:</dt>
                <dd>{Status}</dd>
                <dt>Employee:</dt>
                <dd>{Employee}</dd>
                <dt>Issue:</dt>
                <dd>{Issue}</dd>
                <dt>Description:</dt>
                <dd>{Description}</dd>
                <dt>Date Created:</dt>
                <dd>{DateCreated}</dd>
                <dt>Date Opened:</dt>
                <dd>{DateOpened}</dd>
            </dl>
        </div>

        <!-- Conditionally display the image if provided -->
        <p><strong>Uploaded Image:</strong></p>
        <p>If you uploaded an image with your ticket, you can view it <a href="{ImageUrl}">here</a>.</p>

        <p>We will get back to you shortly with further updates.</p>

        <div class="footer">
            <p>Best regards,</p>
            <p>Management Information System<br>Don Honorio Ventura State University</p>
            <p><a href="{WebsiteUrl}">Visit our website</a></p>
        </div>
    </div>
</body>
</html>
