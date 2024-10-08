<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 150px;
            height: auto;
        }
        .content {
            line-height: 1.7;
            color: #555;
        }
        .content h1 {
            color: #5a67d8;
            text-align: center;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .content p {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .otp-code {
            display: block;
            font-size: 28px;
            color: #ffffff;
            background-color: #5a67d8;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            letter-spacing: 3px;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #888;
        }
        .footer a {
            color: #5a67d8;
            text-decoration: none;
            font-weight: bold;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://drive.google.com/uc?export=view&id=1tTULPtMo8vufaRhxMy-ZdpshPnFSw94M" alt="Management Information System">
        </div>
        <div class="content">
            <h1>Your OTP Code</h1>
            <p>Hello,</p>
            <p>To complete your action, use the One-Time Password (OTP) below:</p>
            <div class="otp-code">$otp</div>
            <p>This OTP is valid for 10 minutes. Please do not share this code with anyone.</p>
            <p>If you did not request this, please disregard this email.</p>
        </div>
        <div class="footer">
            <p>Best regards,<br>Management Information System<br>Don Honorio Ventura State University</p>
            <p><a href="https://dhvsuhelphub.com/">Visit our website</a></p>
        </div>
    </div>
</body>
</html>
