<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

if (isset($_POST['forgotpass'])) {
    try {
        $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $email = $_POST["email"];

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Check in student_user table
//        $pdoLedgerQuery = "SELECT * FROM student_user WHERE student_number = :username";
//        $pdoResult = $pdoConnect->prepare($pdoLedgerQuery);
//        $pdoResult->bindParam(':username', $username);
//        $pdoResult->execute();

        // Build the query string
        $queryParams = http_build_query([
            'email' => $email,
        ]);

        header("Location: otp.php?$queryParams");
        exit; // Prevent further execution after redirection
        

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
        <h3>Forgot Password?</h3>

        <div class="form-group">
            <input type="email" name="email" required placeholder="Enter Email">
        </div>

        <input type="submit" name="forgotpass" value="Send"  ><br>
    
        
    </form>
    <a href="index.php" class="forgot">Back</a>
    </div> 
</body>
</html>