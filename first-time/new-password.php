<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

if (!isset($_SESSION["address"]) && !isset($_SESSION["user"]) && !isset($_SESSION["first-time"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["first-time"];
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
            
            // Determine the correct table and ID for the user
            if ($User == "Student") {
                $pdoUpdateQuery = "UPDATE student_user SET password = :pass WHERE user_id = :id";
            } elseif ($User == "Employee") {
                $pdoUpdateQuery = "UPDATE employee_user SET password = :pass WHERE user_id = :id";
            } elseif ($User == "MIS Employee") {
                $pdoUpdateQuery = "UPDATE mis_employees SET password = :pass WHERE admin_number = :id";
            }
            
            $pdoResult = $pdoConnect->prepare($pdoUpdateQuery);
            $pdoResult->bindParam(':id', $id, PDO::PARAM_STR);
            $pdoResult->bindParam(':pass', $hashPassword, PDO::PARAM_STR);
            
            if (!$pdoResult->execute()) {
                // Handle the error in case the update fails
                throw new PDOException("Failed to execute the update query");
            } else {
                header("Location: fill-up-info.php");
                exit(); // Exit to prevent further execution
            }
        } else {
            $errorMessage = "Passwords do not match.";
            exit(); // Exit to prevent further execution
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
    <link  rel="stylesheet" href="../index.css">
    <link rel="icon" href="../img/logo.png" type="image/png">
</head>
<body>
    <img class="logo" src="../img/MIS logo.png" alt="Image">

    <div class="login">
    <form method="post" name="password" id="passnew" onsubmit="return validatePassword(event)">
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
                onmousedown="showPassword()" onmouseup="hidePassword()" onmouseleave="hidePassword()">
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
        <input type="hidden" name="password">
        <input type="submit" name="password" value="Submit"  ><br>
        
    </form>

<script>
    function validatePassword(event) {
        // Get the values of the passwords and the user ID
        var userId = document.getElementById("userId").innerText;
        var newPassword = document.getElementById("newpass").value;
        var reNewPassword = document.getElementById("renewpass").value;

        // Check if the new password is the same as the user ID
        if (newPassword === userId || reNewPassword === userId) {
            var notificationMessage = "Your password cannot be the same as the User ID.";
            showNotification(notificationMessage); // Show the notification
            return false; // Prevent form submission
        }

        // Check if the password length is at least 6 characters
        if (newPassword.length < 6) {
            var notificationMessage = "Password must be at least 6 characters long.";
            showNotification(notificationMessage); // Show the notification
            return false; // Prevent form submission
        }

        // Check if both password fields match
        if (newPassword !== reNewPassword) {
            var notificationMessage = "The passwords do not match.";
            showNotification(notificationMessage); // Show the notification
            return false; // Prevent form submission
        }

        event.preventDefault(); // Prevent form from submitting immediately
        showConfirmNotification(); // Show custom confirmation dialog

        // If everything is okay, allow the form to submit
        return true;
    }
</script>
<script src="script.js"></script>
    </div> 
</body>
</html>
<!-- Notification Code -->
<?php include_once("notification.php");?>
<!-- Confirm Notification Code -->
<?php include_once("confirm-notification.php");?>