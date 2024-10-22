<?php

session_start();

include_once("../connection/conn.php");
$pdoConnect = connection();

if (!isset($_SESSION["Super-Admin"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} elseif ($_SESSION["Super-Admin"] !== "Log In Success") {
    header("Location: ../index.php");
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

  if (isset($_POST['admin'])){
    $_SESSION["admin_number"] = "1000000000";
    header("Location: ../Admin/index.php");
    exit(); // Prevent further execution after redirection
  }
  if (isset($_POST['student'])){
    $_SESSION["user_id"] = "1000000000";
    $_SESSION["user_identity"] = "Student";
    header("Location: ../User/dashboard.php");
    exit(); // Prevent further execution after redirection
  }
  if (isset($_POST['employee'])){
    $_SESSION["user_id"] = "1000000000";
    $_SESSION["user_identity"] = "Employee";
    header("Location: ../User/dashboard.php");
    exit(); // Prevent further execution after redirection
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HelpHub Super Admin</title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;

            background-attachment: fixed;
            background-image: url(../img/background.png);
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            height: 95vh; /* Set the height to cover the entire viewport */
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 150px;
            height: auto;
        }
        .header h1 {
            font-size: 28px;
            color: #5a67d8;
        }
        h3 {
            color: #555;
            margin-bottom: 30px;
            font-size: 22px;
        }
        .buttons-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 30px;
        }
        .left, .right {
            flex-basis: 48%;
        }
        .right {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        button {
            width: 100%;
            background-color: #5a67d8;
            color: #ffffff;
            font-size: 20px;
            font-weight: bold;
            padding: 15px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        button i {
            margin-right: 10px;
            font-size: 24px;
        }
        .left button {
            height: calc(88%); /* To match the combined height of both user buttons */
        }
        button:hover {
            background-color: #434ebc;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #888;
        }
        .footer button {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
        }
        .footer button:hover {
            text-decoration: underline;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="../img/logo.png" alt="HelpHub Logo">
            <h1>HelpHub Super Admin Log In</h1>
        </div>
<form method="post" class="buttons-container">
            <div class="left">
                <button type="submit" name="admin">
                    <i class="fas fa-user-shield"></i> Admin
                </button>
            </div>
            <div class="right">
                <button type="submit" name="student">
                    <i class="fas fa-user-graduate"></i> Student User
                </button>
                <button type="submit" name="employee">
                    <i class="fas fa-user-tie"></i> Employee User
                </button>
            </div>
</form>
    
    <div class="footer">
        <p>
            <button onclick="window.open('#', '_blank');">Open Hosting Site</button>
        </p>
        <p>
            <button onclick="window.open('http://localhost/phpmyadmin/index.php?route=/database/structure&db=helphub', '_blank');">Open Database</button>
        </p>
        <p>
            <a href="logout.php"><button>Log Out</button></a>
        </p>
    </div>
</div>
</body>
</html>
