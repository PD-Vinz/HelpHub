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

//        if ($username == $pass) {
//            $_SESSION["first-time"] = $username;
//            header("Location: first-time/verify.php");
//            exit(); // Prevent further execution after redirection
//        }
 // Check in mis_employees table
 $pdoAdminQuery = "SELECT * FROM mis_employees WHERE admin_number = :username AND password = :pass";
 $pdoResult3 = $pdoConnect->prepare($pdoAdminQuery);
 $pdoResult3->bindParam(':username', $username);
 $pdoResult3->bindParam(':pass', $pass);
 $pdoResult3->execute();

 if ($pdoResult3->rowCount() > 0) {
     $_SESSION["admin_number"] = $username;
     header("Location: Admin/index.php");
     exit(); // Prevent further execution after redirection
 }
        // Check in student_user table

        $pdoUserQuery1 = "SELECT * FROM student_user WHERE user_id = :username AND password = :pass";
        $pdoResult1 = $pdoConnect->prepare($pdoUserQuery1);
        $pdoResult1->bindParam(':username', $username);
        $pdoResult1->bindParam(':pass', $pass);
        $pdoResult1->execute();
        if ($pdoResult1->rowCount() > 0) {
            $_SESSION["user_id"] = $username;
            $_SESSION["user_identity"] = "Student";
            header("Location: User/dashboard.php");
            exit(); // Prevent further execution after redirection
        }

        $pdoUserQuery2 = "SELECT * FROM employee_user WHERE user_id = :username AND password = :pass";
        $pdoResult2 = $pdoConnect->prepare($pdoUserQuery2);
        $pdoResult2->bindParam(':username', $username);
        $pdoResult2->bindParam(':pass', $pass);
        $pdoResult2->execute();

        if ($pdoResult2->rowCount() > 0) {
            $_SESSION["user_id"] = $username;
            $_SESSION["user_identity"] = "Employee";
            header("Location: User/dashboard.php");
            exit(); // Prevent further execution after redirection
        }

       
        // If no match found in both tables
        $errorMessage = "Wrong Username or Password";
        echo $errorMessage;
            
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
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*">
   
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
    <a href="forgot_password.php" class="forgot">Forgot Password?</a>
    </div> 
</body>
</html>