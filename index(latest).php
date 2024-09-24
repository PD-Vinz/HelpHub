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

if (isset($_GET["failed"])) {
    $errorMessage = "Wrong Username or Password";
        echo "<script type='text/javascript'>
            window.onload = function() {
                alert('$errorMessage');
                window.location.href = 'index.php';
            };
            </script>";
}

if (isset($_POST['login'])) {
    try {
        $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $username = $_POST["username"];
        $pass = $_POST["password"];

        if ($username == $pass) {
            $_SESSION["first-time"] = $username;
            header("Location: first-time/verify.php");
            exit(); // Prevent further execution after redirection
        }

// Check in student_user table

// Assuming $username and $pass contain user input

// Query to select the hashed password from the database
$pdoUserQuery1 = "SELECT password FROM student_user WHERE user_id = :username";
$pdoResult1 = $pdoConnect->prepare($pdoUserQuery1);
$pdoResult1->bindParam(':username', $username);
$pdoResult1->execute();

// Check if the user exists
if ($pdoResult1->rowCount() > 0) {
    // Fetch the hashed password from the database
    $user = $pdoResult1->fetch();
    $storedHash = $user['password'];

    // Verify the input password against the stored hash
    if (password_verify($pass, $storedHash)) {
        // Password is correct, proceed with login
        $_SESSION["user_id"] = $username;
        $_SESSION["user_identity"] = "Student";
        header("Location: User/dashboard.php");
        exit(); // Prevent further execution after redirection
    }
}

// Query to select the hashed password from the database
$pdoUserQuery2 = "SELECT password FROM employee_user WHERE user_id = :username";
$pdoResult2 = $pdoConnect->prepare($pdoUserQuery2);
$pdoResult2->bindParam(':username', $username);
$pdoResult2->execute();

// Check if the user exists
if ($pdoResult2->rowCount() > 0) {
    // Fetch the hashed password from the database
    $user = $pdoResult2->fetch();
    $storedHash = $user['password'];

    // Verify the input password against the stored hash
    if (password_verify($pass, $storedHash)) {
        // Password is correct, proceed with login
        $_SESSION["user_id"] = $username;
        $_SESSION["user_identity"] = "Employee";
        header("Location: User/dashboard.php");
        exit(); // Prevent further execution after redirection
    }
}

// Query to select the hashed password from the database
$pdoAdminQuery = "SELECT * FROM mis_employees WHERE admin_number = :username";
$pdoResult3 = $pdoConnect->prepare($pdoAdminQuery);
$pdoResult3->bindParam(':username', $username);
$pdoResult3->execute();

// Check if the user exists
if ($pdoResult3->rowCount() > 0) {
    // Fetch the hashed password from the database
    $user = $pdoResult3->fetch();
    $storedHash = $user['password'];

    // Verify the input password against the stored hash
    if (password_verify($pass, $storedHash)) {
        // Password is correct, proceed with login
        $_SESSION["admin_number"] = $username;
        header("Location: Admin/index.php");
        exit(); // Prevent further execution after redirection
    }
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
        <div class="form-group">
            <input type="text" name="username" required placeholder="User ID">
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
    <a href="forgot-password/forgot-password.php" class="forgot">Forgot Password?</a>
    </div> 

    <script src="script.js"></script>
</body>
</html>