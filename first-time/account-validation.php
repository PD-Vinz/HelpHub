<?php
include_once("connection/conn.php");
$pdoConnect = connection();

if (isset($_POST['otpcode'])) {
    try {
        $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check in student_user table
//        $pdoLedgerQuery = "SELECT * FROM student_user WHERE student_number = :username";
//        $pdoResult = $pdoConnect->prepare($pdoLedgerQuery);
//        $pdoResult->bindParam(':username', $username);
//        $pdoResult->execute();

        
            header("Location: newpass.php");
            exit(); // Prevent further execution after redirection
        

        // If no match found in both tables
//        $errorMessage = "Wrong Username or Password";
//        echo "<script type='text/javascript'>
//            window.onload = function() {
//                alert('$errorMessage');
//                window.location.href = 'index.php';
//            };
//        </script>";
    } catch (PDOException $error) {
        $message = '<label>Error: ' . $error->getMessage() . '</label>';
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
    <link rel="icon" href="img/logo.png" type="image/png">
</head>
<body>
    <img class="logo" src="img/MIS logo.png" alt="Image">

    <div class="login">
    <form method="post">
        <h3>First-time Log In</h3>

        <h4>We've sent a verification code to <h5><?php echo htmlspecialchars($Address);?></h5></h4>

        <h6>Resend Code?<a href="forgot_password.php">Click Here</a></h6>

        <div class="form-group">
            <input type="text" name="code" required pattern="\d{6}" placeholder="Enter Code" maxlength="6">
        </div>     

        <input type="submit" name="otpcode" value="Verify"  ><br>
    
        
    </form>
    <a href="forgot_password.php" class="forgot">Back</a>
    </div> 
</body>
</html>