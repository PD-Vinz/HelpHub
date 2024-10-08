<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

if (!isset($_SESSION["first-time"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["first-time"];

    $pdoUserQuery = "SELECT * FROM student_user WHERE user_id = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();
    $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

    if ($Data) {
        $Address = $Data['email_address'];
        $_SESSION["address"] = $Address;
        $user = "Student";
        $_SESSION["user"] = "Student";
    }

    $pdoUserQuery = "SELECT * FROM employee_user WHERE user_id = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();
    $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

    if ($Data) {
        $Address = $Data['email_address'];
        $_SESSION["address"] = $Address;
        $user = "Employee";
        $_SESSION["user"] = "Employee";
    }

    $pdoUserQuery = "SELECT * FROM mis_employees WHERE admin_number = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();
    $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

    if ($Data) {
        $Address = $Data['email_address'];
        $_SESSION["address"] = $Address;
        $user = "MIS Employee";
        $_SESSION["user"] = "MIS Employee";
    }

    if (!isset($Address) && !isset($user)) {
        header("Location: ../index.php?failed=true");
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HelpHub</title>
    <link rel="icon" href="../img/logo.png" type="image/png">

    <style>
        @import url(https://fonts.googleapis.com/css?family=Poppins);
*   {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
    }

body {
    align-items: center;
    background-image: url(../img/background.png);
    background-size: 100% 100%;
    background-position: center;
    background-repeat: no-repeat;
    height: 95vh; /* Set the height to cover the entire viewport */
    margin: 0;
    overflow-y: hidden;
}

.logo {
    margin-top: 40px;
    max-width: 300px;
    height: auto;
    display: block;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 600px) {
    .logo {
        width: 100%;
    }
}

@media (min-width: 601px) and (max-width: 1200px) {
    .logo {
        width: 75%;
    }
}

@media (min-width: 1201px) {
    .logo {
        width: 50%;
    }
}

h3 {
    color: #ffffff;
    font-size: 30px;
    margin-bottom: 10px;
}

h5 {
    color: #FFCC00;
}

h6 {
    margin-bottom: 5px;
}

.login {
    text-align: center;
    margin: 20px auto;
    padding: 30px;
    border-radius: 50px;
    background-color: #9C0507;
    display: block;
    margin-left: auto;
    margin-right: auto;
    max-width: 500px;
    height: auto;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 20px;
}

input[type="text"],
input[type="number"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 5px;
    border: 1px solid #ccc;
    border-radius: 20px;
    box-sizing: border-box;
    cursor: text;
}

input[type="checkbox"] {
    margin-bottom: 5px;
}

input[type="submit"] {
    width: 75%;
    background-color: #E4A001;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-size: 16px;
    margin-bottom: 10px;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Additional media query for very small screens */
@media (max-width: 420px) {
    h3 {
        font-size: 24px;
    }

    .login {

        margin-left: 10px;
        margin-right: 10px;
    }


}


.forgot {
    color: #0866ff;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;                                  
}

/*
    input[type="text"],
    input[type="number"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box; /* Ensure that padding and border are included in the width 
    }

    input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    input[type="submit"]:focus {
        outline: none; /* Remove outline when button is focused 
    }
*/
    </style>
</head>
<body>
    <img class="logo" src="../img/MIS logo.png" alt="Image">

    <div class="login">
    <form action="generate-otp.php" method="post">
        <h3>First-time Log In</h3>

        <p>We detected that this is your first-time log in with this account.
            To enhance your accounts security, we need you to change your password.
            We'll be sending an OTP code on your email which is to verify it is your account.
            Click the Verify Account button to continue.
        </p>
<br>
        <h5>Email: <?php echo htmlspecialchars($Address);?></h5>
        <!--<h5>User Type: <?php //echo htmlspecialchars($user);?></h5>-->
        <br>

        <input type="submit" Name="Verify_Account" value="Verify"  ><br>
    
        
    </form>
    <a onclick="history.back()" class="forgot">Back</a>
    </div> 
    <script src="../script.js"></script>
</body>
</html>