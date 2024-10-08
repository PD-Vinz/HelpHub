<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

if (!isset($_SESSION["address"]) && !isset($_SESSION["user"]) && !isset($_SESSION["first-time"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["forgot_id"];
    $Address = $_SESSION["address"];
    $User = $_SESSION["user"];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['password'])) {
        // Get the submitted password values
        $newPassword = $_POST['newpass'];
        $reNewPassword = $_POST['renewpass'];

        // Your PHP logic goes here, for example, save the password to the database
        if ($newPassword === $reNewPassword) {

            $hashPassword = password_hash($newPassword, PASSWORD_ARGON2I);
            // PHP code to run if passwords are valid (you can modify this part)

            if ($User == "Student"){

            $pdoUpdateQuery="UPDATE student_user 
                        SET password = :pass
                        WHERE user_id = :id";
            $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
            $pdoResult->bindParam(':id', $id, PDO::PARAM_STR);
            $pdoResult->bindParam(':pass', $hashPassword, PDO::PARAM_STR);
            if (!$pdoResult->execute()) {
                throw new PDOException("Failed to execute the first query");
            } else {
                $errorMessage = "Password Successfully Updated.";
                echo "<script type='text/javascript'>
                    window.onload = function() {
                        alert('$errorMessage');
                        window.location.href = '../index.php';
                    };
                    </script>";
            }

            } elseif ($User == "Employee"){
            $pdoUpdateQuery="UPDATE employee_user 
                        SET password = :pass
                        WHERE user_id = :id";
            $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
            $pdoResult->bindParam(':id', $id, PDO::PARAM_STR);
            $pdoResult->bindParam(':pass', $hashPassword, PDO::PARAM_STR);
            if (!$pdoResult->execute()) {
                throw new PDOException("Failed to execute the first query");
            } else {
                $errorMessage = "Password Successfully Updated.";
                echo "<script type='text/javascript'>
                    window.onload = function() {
                        alert('$errorMessage');
                        window.location.href = '../index.php';
                    };
                    </script>";
            }

            } elseif ($User == "MIS Employee"){
            $pdoUpdateQuery="UPDATE mis_employees 
                        SET password = :pass
                        WHERE admin_number = :id";
            $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
            $pdoResult->bindParam(':id', $id, PDO::PARAM_STR);
            $pdoResult->bindParam(':pass', $hashPassword, PDO::PARAM_STR);
            if (!$pdoResult->execute()) {
                throw new PDOException("Failed to execute the first query");
            } else {
                $errorMessage = "Password Successfully Updated.";
                echo "<script type='text/javascript'>
                    window.onload = function() {
                        alert('$errorMessage');
                        window.location.href = '../index.php';
                    };
                    </script>";
            }

            }

            // echo "<p>Password successfully updated!</p>";

            // Example: Update password in the database
            // $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            // Save $hashedPassword to the database

        } else {
        $errorMessage = "Passwords do not match.";
        echo "<script type='text/javascript'>
                alert('$errorMessage');
            </script>";
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
    <form method="post" onsubmit="return validatePassword()">
        <h3>Enter New Password</h3>

        <div class="form-group">
            <h4>User ID: <span id="userId"><?php echo $id?></span></h4>
        </div>
        <div class="form-group">
            <input type="password" id="newpass" name="newpass" required placeholder="New Password">
        </div>

        <div class="form-group">
            <input type="password" id="renewpass" name="renewpass" required placeholder="Re-enter New Password">
        </div>

        <div class="form-group">
            <button type="button" id="togglePassword" class="password-btn" 
                onmousedown="showPassword()" onmouseup="hidePassword()">
                    Show Password
            </button>
<style>
    .password-btn {
        background-color: #9C0507; /* Bootstrap-like blue */
        color: white;
        padding: -10px -15px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .password-btn:hover {
        background-color: none; /* Darker blue on hover */
        color: lightgray;
    }

    .password-btn:active {
        background-color: none; /* Even darker when pressed */
        color: black;
    }
</style>
<script>
    function showPassword() {
        document.getElementById("newpass").type = "text";
        document.getElementById("renewpass").type = "text";
    }

    function hidePassword() {
        document.getElementById("newpass").type = "password";
        document.getElementById("renewpass").type = "password";
    }
</script>
        </div>

        <input type="submit" name="password" value="Submit"  ><br>
        
    </form>

<script>
    function validatePassword() {
        // Get the values of the passwords and the user ID
        var userId = document.getElementById("userId").innerText;
        var newPassword = document.getElementById("newpass").value;
        var reNewPassword = document.getElementById("renewpass").value;

        // Check if the new password is the same as the user ID
        if (newPassword === userId || reNewPassword === userId) {
            alert("Your password cannot be the same as the User ID.");
            return false; // Prevent form submission
        }

        // Check if the password length is at least 6 characters
        if (newPassword.length < 6) {
            alert("Password must be at least 6 characters long.");
            return false; // Prevent form submission
        }

        // Check if both password fields match
        if (newPassword !== reNewPassword) {
            alert("The passwords do not match.");
            return false; // Prevent form submission
        }

        // Confirmation before form submission
        var confirmSubmission = confirm("Are you sure you want to submit the form?");
        if (!confirmSubmission) {
            return false; // Stop form submission if the user clicks 'Cancel'
        }

        // If everything is okay, allow the form to submit
        return true;
    }
</script>
    </div> 
    <script src="script.js"></script>
</body>
</html>