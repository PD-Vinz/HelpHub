<?php
include_once("connection/conn.php");
$pdoConnect = connection();

if (isset($_POST['login'])) {
    try {
        $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $username = $_POST["username"];

        // Check in student_user table
        $pdoLedgerQuery = "SELECT * FROM student_user WHERE student_number = :username";
        $pdoResult = $pdoConnect->prepare($pdoLedgerQuery);
        $pdoResult->bindParam(':username', $username);
        $pdoResult->execute();

        if ($pdoResult->rowCount() > 0) {
            header("Location: User/dashboard.html");
            exit(); // Prevent further execution after redirection
        }

        // Check in mis_employees table
        $pdoLedgerQuery = "SELECT * FROM mis_employees WHERE employee_number = :username";
        $pdoResult = $pdoConnect->prepare($pdoLedgerQuery);
        $pdoResult->bindParam(':username', $username);
        $pdoResult->execute();

        if ($pdoResult->rowCount() > 0) {
            header("Location: Admin/index.php");
            exit(); // Prevent further execution after redirection
        }

        // If no match found in both tables
        $errorMessage = "Wrong Username or Password";
        echo "<script type='text/javascript'>
            window.onload = function() {
                alert('$errorMessage');
                window.location.href = 'index.php';
            };
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
    <link rel="icon" href="img/logo.png" type="image/png">
</head>
<body>
    <img class="logo" src="img/MIS logo.png" alt="Image">

    <div class="login">
    <form method="post">
        <h3>Enter New Password</h3>
        <div class="form-group">
            <input type="password" name="newpass" required placeholder="New Password">
        </div>
        <div class="form-group">
            <input type="password" name="renewpass" required placeholder="Re-enter New Password">
        </div>

        <input type="submit" name="password" value="Submit"  ><br>
    
        
    </form>
    </div> 
</body>
</html>