<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

if (isset($_POST['forgotpass'])) {
    try {
        $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $userid = $_POST["userid"];

        $pdoUserQuery = "SELECT * FROM student_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $userid);
        $pdoResult->execute();
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $_SESSION["forgot_id"] = $userid;
            $Address = $Data['email_address'];
            $_SESSION["address"] = $Address;
            $user = "Student";
            $_SESSION["user"] = "Student";
            header("Location: generate-otp.php");
            exit; // Prevent further execution after redirection
        }
    
        $pdoUserQuery = "SELECT * FROM employee_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $userid);
        $pdoResult->execute();
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $_SESSION["forgot_id"] = $userid;
            $Address = $Data['email_address'];
            $_SESSION["address"] = $Address;
            $user = "Employee";
            $_SESSION["user"] = "Employee";
            header("Location: generate-otp.php");
            exit; // Prevent further execution after redirection
        }
    
        $pdoUserQuery = "SELECT * FROM mis_employees WHERE admin_number = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $userid);
        $pdoResult->execute();
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $_SESSION["forgot_id"] = $userid;
            $Address = $Data['email_address'];
            $_SESSION["address"] = $Address;
            $user = "MIS Employee";
            $_SESSION["user"] = "MIS Employee";
            header("Location: generate-otp.php");
            exit; // Prevent further execution after redirection
        }

        // If no match found in both tables
        $errorMessage = "User ID not found.";
        echo "<script type='text/javascript'>
                alert('$errorMessage');
        </script>";
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
    <link rel="icon" href="../img/logo.png" type="image/png">

</head>
<body>
    <img class="logo" src="../img/MIS logo.png" alt="Image">

    <div class="login">
    <form method="post">
        <h3>Forgot Password?</h3>

        <div class="form-group">
            <input type="number" name="userid" required placeholder="Enter User ID">
        </div> 

        <input type="submit" name="forgotpass" value="Send"  ><br>
    
        
    </form>
    <a href="../index.php" class="forgot">Back</a>
    </div> 
    <script src="script.js"></script>
</body>
</html>