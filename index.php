<?php

session_start();

include_once("connection/conn.php");
$pdoConnect = connection();

if (isset($_SESSION["Super-Admin"])) {
    header("Location: Super-Admin/dashboard.php");
    exit(); // Prevent further execution after redirection
}

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

 // for displaying system details
 $query = $pdoConnect->prepare("SELECT system_name, short_name, system_logo, system_cover FROM settings WHERE id = :id");
 $query->execute(['id' => 1]);
 $Datas = $query->fetch(PDO::FETCH_ASSOC);
 $sysName = $Datas['system_name'] ?? '';
 $shortName = $Datas['short_name'] ?? '';
  $systemCover = $Datas['system_cover'];
  $S_L = $Datas['system_logo'];
  $S_LBase64 = '';
  if (!empty($S_L)) {
      $base64Image = base64_encode($S_L);
      $imageType = 'image/png'; // Default MIME type
      $S_LBase64 = 'data:' . $imageType . ';base64,' . $base64Image;
  }

if (isset($_POST['login'])) {
    try {
        $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $username = $_POST["username"];
        $pass = $_POST["password"];

//        if ($username == $pass) {
//            $_SESSION["first-time"] = $username;
//            header("Location: first-time/verify.php");
//            exit(); // Prevent further execution after redirection
//        }

// Check in student_user table

// Assuming $username and $pass contain user input

// Query to select the hashed password from the database
$pdoUserQuery1 = "SELECT password, account_status FROM student_user WHERE user_id = :username";
$pdoResult1 = $pdoConnect->prepare($pdoUserQuery1);
$pdoResult1->bindParam(':username', $username);
$pdoResult1->execute();

// Check if the user exists
if ($pdoResult1->rowCount() > 0) {
    // Fetch the hashed password from the database
    $user = $pdoResult1->fetch();
    $storedHash = $user['password'];
    $status = $user['account_status'];

    if (($status == 'Not Activated') && ($username == $pass)) {
        $_SESSION["first-time"] = $username;
        header("Location: first-time/verify.php");
        exit(); // Prevent further execution after redirection
    } else {
    // Verify the input password against the stored hash
    if (password_verify($pass, $storedHash)) {
        // Password is correct, proceed with login
        if ($status == 'Disabled') {
            $message = "Your account is currently deactivated. If you wish to activate your account, please proceed to the MIS Office";
            echo "<script type='text/javascript'>
            window.onload = function() {
                alert('$message');
                window.location.href = 'index.php';
            };
        </script>";
            exit;
        } else {
        $_SESSION["user_id"] = $username;
        $_SESSION["user_identity"] = "Student";
        header("Location: User/dashboard.php");
        exit(); // Prevent further execution after redirection
    }
    }
}
}

// Query to select the hashed password from the database
$pdoUserQuery2 = "SELECT password, account_status FROM employee_user WHERE user_id = :username";
$pdoResult2 = $pdoConnect->prepare($pdoUserQuery2);
$pdoResult2->bindParam(':username', $username);
$pdoResult2->execute();

// Check if the user exists
if ($pdoResult2->rowCount() > 0) {
    // Fetch the hashed password from the database
    $user = $pdoResult2->fetch();
    $storedHash = $user['password'];
    $status = $user['account_status'];

    if (($status == 'Not Activated') && ($username == $pass)) {
        $_SESSION["first-time"] = $username;
        header("Location: first-time/verify.php");
        exit(); // Prevent further execution after redirection
    } else {

    // Verify the input password against the stored hash
    if (password_verify($pass, $storedHash)) {
        if ($status == 'Disabled') {
            $message = "Your account is currently deactivated. If you wish to activate your account, please proceed to the MIS Office";
            echo "<script type='text/javascript'>
            window.onload = function() {
                alert('$message');
                window.location.href = 'index.php';
            };
        </script>";
            exit;
        } else {
        // Password is correct, proceed with login
        $_SESSION["user_id"] = $username;
        $_SESSION["user_identity"] = "Employee";
        header("Location: User/dashboard.php");
        exit(); // Prevent further execution after redirection
        }
    }
}
}

// Query to select the hashed password from the database
$pdoAdminQuery = "SELECT password, account_status FROM mis_employees WHERE admin_number = :username";
$pdoResult3 = $pdoConnect->prepare($pdoAdminQuery);
$pdoResult3->bindParam(':username', $username);
$pdoResult3->execute();

// Check if the user exists
if ($pdoResult3->rowCount() > 0) {
    // Fetch the hashed password from the database
    $user = $pdoResult3->fetch();
    $storedHash = $user['password'];
    $status = $user['account_status'];

    if (($status == 'Not Activated') && ($username == $pass)) {
        $_SESSION["first-time"] = $username;
        header("Location: first-time/verify.php");
        exit(); // Prevent further execution after redirection
    } else {

    // Verify the input password against the stored hash
    if (password_verify($pass, $storedHash)) {
        if ($status == 'Disabled') {
            $message = "Your account is currently deactivated. If you wish to activate your account, please proceed to the MIS Office";
            echo "<script type='text/javascript'>
            window.onload = function() {
                alert('$message');
                window.location.href = 'index.php';
            };
        </script>";
            exit;
        } else {
        // Password is correct, proceed with login
        include_once("priority_check.php");
        $_SESSION["admin_number"] = $username;
        header("Location: Admin/index.php");
        exit(); // Prevent further execution after redirection
        }
    }
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
<html lang="en" oncontextmenu="return false;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DHVSU MIS - HelpHub</title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
</head>
<body>
    <img class="logo" src="img/MIS logo.png" alt="Image">

    <div class="login">
    <form method="post">
        <h3 style="text-shadow: 0.3px 0.3px #18181a;">Log In</h3>
        <hr>
        <br>

        <div class="form-group">
            <input type="text" name="username" required placeholder="User ID" >
        </div>

<div class="form-group" style="position: relative;">
    <input type="password" name="password" id="myInput" required placeholder="Password" autocomplete="off" style="padding-right: 30px;">
    <button type="button" id="toggleBtn" onclick="togglePassword()" style="position: absolute; right: 10px; top: 45%; transform: translateY(-50%); border:none; background:none; cursor:pointer;">
        <i class="fas fa-eye" id="eyeIcon"></i>
    </button>
</div>

<script>
    function togglePassword() {
        var passwordField = document.getElementById('myInput');
        var eyeIcon = document.getElementById('eyeIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

<!--
        <div class="form-group">
            <input type="checkbox" id="savePassword" name="savePassword" onclick="myFunction()">
            <label for="savePassword" style="color: #2b2b2b;">Show Password</label>
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
-->
        <input type="submit" name="login" value="Log In"  ><br>
    
        
    </form>
    <a href="forgot-password/forgot-password.php" class="forgot">Forgot Password?</a>
    </div> 

<footer>
    <p>&copy; 2024 HelpHub. All rights reserved.</p>
    <p>
        <button class="link-button" onclick="window.open('/terms-of-service.html', '_blank');">Terms of Service</button> | 
        <button class="link-button" onclick="window.open('/privacy-policy.html', '_blank');">Privacy Policy</button>
    </p>
</footer>


    <script src="script.js"></script>
</body>
</html>