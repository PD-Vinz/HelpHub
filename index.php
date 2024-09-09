<?php
session_start();

include_once("connection/conn.php");
$pdoConnect = connection();

// Redirect to student dashboard if student session exists
if (isset($_SESSION["user_id"])) {
    header("Location: User/dashboard.php");
    exit(); // Prevent further execution after redirection
}

// Redirect to admin index if admin session exists
if (isset($_SESSION["admin_number"])) {
    header("Location: Admin/index.php");
    exit(); // Prevent further execution after redirection
}

if (isset($_POST['login'])) {
    try {
        $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $username = $_POST["username"];
        $pass = $_POST["password"];

        // Check in student_user table
        $pdoUserQuery = "SELECT * FROM tb_user WHERE user_id = :username AND password = :pass";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':username', $username);
        $pdoResult->bindParam(':pass', $pass);
        $pdoResult->execute();

        if ($pdoResult->rowCount() > 0) {
            $_SESSION["user_id"] = $username;
            $_SESSION["user_identity"] = "Student";
            header("Location: User/dashboard.php");
            exit(); // Prevent further execution after redirection
        }

        $pdoUserQuery = "SELECT * FROM employee_user WHERE user_id = :username AND password = :pass";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':username', $username);
        $pdoResult->bindParam(':pass', $pass);
        $pdoResult->execute();

        if ($pdoResult->rowCount() > 0) {
            $_SESSION["user_id"] = $username;
            $_SESSION["user_identity"] = "Employee";
            header("Location: User/dashboard.php");
            exit(); // Prevent further execution after redirection
        }

        // Check in mis_employees table
        $pdoAdminQuery = "SELECT * FROM mis_employees WHERE admin_number = :username AND password = :pass";
        $pdoResult = $pdoConnect->prepare($pdoAdminQuery);
        $pdoResult->bindParam(':username', $username);
        $pdoResult->bindParam(':pass', $pass);
        $pdoResult->execute();

        if ($pdoResult->rowCount() > 0) {
            $_SESSION["admin_number"] = $username;
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
    <title>DHVSU MIS - HelpHub</title>
    <link rel="icon" href="img/Logo.png" type="image/png">
    <link  rel="stylesheet" href="index.css">
</head>
<body>
    <img class="logo" src="img/MIS logo.png" alt="Image">

    <div class="login">
    <form method="post">
        <h3>Log In</h3>
        <hr/>
        <br>
        <div class="form-group">
            <input type="text" name="username" required placeholder="Username">
        </div>
        <div class="form-group">
            <input type="password" name="password" id="myInput" required placeholder="Password" autocomplete="off">
        </div>
        <div class="form-group">
            <input type="checkbox" id="savePassword" name="savePassword" onclick="myFunction()">
            <label for="savePassword">Show Password</label>
            <script>
                            function myFunction() {
                                var x = document.getElementById("myInput");
                                if (x.type === "password") {
                                    x.type = "text";
                                } else {
                                    x.type = "password";
                                }
                            }
                        </script>
        </div>

        <input type="submit" name="login" value="Log In"  ><br>
    
        
    </form>
    <a href="forgot_password.php" class="forgot">Forgot Password?</a>
    </div> 
</body>
</html>