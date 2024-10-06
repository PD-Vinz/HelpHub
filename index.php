<?php
session_start();

include_once("connection/conn.php");
$pdoConnect = connection();
// Redirect to admin index if admin session exists
if (isset($_SESSION["admin_number"])) {
    header("Location: Admin/index.php");
    exit(); // Prevent further execution after redirection
}

// Redirect to student dashboard if student session exists
if (isset($_SESSION["user_id"])) {
    header("Location: User/dashboard.php");
    exit(); // Prevent further execution after redirection
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
// for displaying system details //end
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
        $pdoUserQuery = "SELECT * FROM student_user WHERE user_id = :username AND password = :pass";
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

        $pdoUserQuery2 = "SELECT password FROM employee_user WHERE user_id = :username AND password = :pass";
        $pdoResult2 = $pdoConnect->prepare($pdoUserQuery2);
        $pdoResult2->bindParam(':username', $username);
        $pdoResult2->bindParam(':pass', $pass);
        $pdoResult2->execute();

        // Check if the user exists
        if ($pdoResult2->rowCount() > 0) {
            $_SESSION["user_id"] = $username;
            $_SESSION["user_identity"] = "Employee";
            header("Location: User/dashboard.php");
            exit(); // Prevent further execution after redirection
        }

        // Check in mis_employees table
        $pdoAdminQuery = "SELECT * FROM mis_employees WHERE admin_number = :username AND password = :pass";
        $pdoResult3 = $pdoConnect->prepare($pdoAdminQuery);
        $pdoResult3->bindParam(':username', $username);
        $pdoResult3->bindParam(':pass', $pass);
        $pdoResult3->execute();

        if ($pdoResult3->rowCount() > 0) {
            $_SESSION["admin_number"] = $username;
            include_once("priority_check.php");

                // Run priority check only once a day
                if (!isset($_SESSION['priority_last_run']) || (time() - $_SESSION['priority_last_run']) >= 86400) {
                    include_once("priority_check.php");
                    $_SESSION['priority_last_run'] = time(); // Update last run time
                }
                
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
<html lang="en" oncontextmenu="return false;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DHVSU MIS - HelpHub</title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*">
   
    <link  rel="stylesheet" href="index.css">
</head>
<body>
    <img class="logo" src="img/MIS logo.png" alt="Image">

    <div class="login">
    <form method="post">
        <h3 style="text-shadow: 0.3px 0.3px #18181a;">Log In</h3>
        <hr>
        <br>
        
        <div class="form-group">
            <input type="text" name="username" required placeholder="Username" autocomplete="off">
        </div>
        <div class="form-group">
            <input type="password" name="password" id="myInput" required placeholder="Password" autocomplete="off">
        </div>
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

        <input type="submit" name="login" value="Log In"  ><br>
    
        
    </form>
    <a href="forgot-password/forgot-password.php" class="forgot">Forgot Password?</a>
    </div> 

    <script src="script.js"></script>
</body>
</html>