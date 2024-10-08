<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

if (!isset($_SESSION["address"]) && !isset($_SESSION["user"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["forgot_id"];
    $Address = $_SESSION["address"];
    $User = $_SESSION["user"];
}

if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_generated'])) {
    header("Location: forgot-password.php");
}

if (isset($_GET['generated']) && ($_GET['generated'] == "yes")) {
    $errorMessage = "OTP has already sent to your email. Please check your inbox or spam.";
        echo "<script type='text/javascript'>
                window.onload = function() {
                alert('$errorMessage');
                window.location.href = 'otp.php';
            };
            </script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['otpcode'])) {

        $otpValidityDuration = 600; // OTP is valid for 5 minutes (300 seconds)
        // Check if OTP is expired
        if (isset($_SESSION['otp_time'])) {
            $timeElapsed = time() - $_SESSION['otp_time'];
            if ($timeElapsed > $otpValidityDuration) {
                // OTP has expired
                unset($_SESSION['otp']); // Invalidate the OTP
                unset($_SESSION['otp_generated']);
                unset($_SESSION['otp_time']);
    $errorMessage = "Your OTP has expired. Please request a new one.";
        echo "<script type='text/javascript'>
            window.onload = function() {
                alert('$errorMessage');
                window.location.href = 'forgot-password.php';
            };
            </script>";
                exit;
            }
        }

        // OTP verification
        $inputOtp = $_POST['otp'];
        if (isset($_SESSION['otp']) && $inputOtp == $_SESSION['otp']) {
            header("Location: new-password.php");
            // Proceed with the next steps (e.g., allow access or redirect)
            unset($_SESSION['otp']);
            unset($_SESSION['otp_generated']); // Clear OTP after successful verification
            exit;
        } else {
            echo 'Invalid OTP. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HelpHub</title>
    <link  rel="stylesheet" href="index.css">
    <link rel="icon" href="../img/logo.png" type="image/png">
</head>
<body>
    <img class="logo" src="../img/MIS logo.png" alt="Image">

    <div class="login">
    <form method="post">
        <h3>OTP Verification</h3>

        <h4>We've sent a verification code to <h5><?php echo htmlspecialchars($Address);?></h5></h4>

        <h6>Resend Code?<a href="generate-otp.php?regenerate=true">Click Here</a></h6>

        <div class="form-group">
            <input type="text" name="otp" required pattern="\d{6}" placeholder="Enter Code" maxlength="6">
        </div>     

        <input type="submit" name="otpcode" value="Verify"  ><br>
    
        
    </form>
    <a href="forgot-password.php" class="forgot">Back</a>
    </div> 

    <script src="script.js"></script>
</body>
</html>