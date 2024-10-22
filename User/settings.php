<?php
include_once("../connection/conn.php");
$pdoConnect = connection();
session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
}

$id = $_SESSION["user_id"];
$identity = $_SESSION["user_identity"];

// Function to fetch user data
function fetchUserData($pdoConnect, $identity, $id) {
    $tableName = $identity == "Student" ? "student_user" : "employee_user";
    $pdoUserQuery = "SELECT * FROM $tableName WHERE user_id = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();
    return [$pdoResult->fetch(PDO::FETCH_ASSOC), $tableName]; // Return both user data and table name
}

// Fetch user data
list($Data, $tableName) = fetchUserData($pdoConnect, $identity, $id);

if (!$Data) {
    echo "No user found with the given ID.";
    exit;
}

// Extract user data
$Email_Add = $Data['email_address'];
$Name = $Data['name'];
$Campus = $Data['campus'];
$Department = $Data['department'];
$Course = $Data['course'];
$Y_S = $Data['year_section'];
$P_P = $Data['profile_picture'];
$Sex = $Data['sex'];
$Age = $Data['age'];
$Bday = $Data['birthday'];
$UserType = $Data['user_type'];

$nameParts = explode(' ', $Name);
$firstName = $nameParts[0];
$P_PBase64 = base64_encode($P_P);
$date = new DateTime($Bday);
$formattedDate = $date->format('F j, Y'); // Formatted date

// Fetch system details
$query = $pdoConnect->prepare("SELECT system_name, short_name, system_logo, system_cover FROM settings WHERE id = :id");
$query->execute(['id' => 1]);
$Datas = $query->fetch(PDO::FETCH_ASSOC);
$sysName = $Datas['system_name'] ?? '';
$shortName = $Datas['short_name'] ?? '';
$systemCover = $Datas['system_cover'];
$S_L = $Datas['system_logo'];
$S_LBase64 = !empty($S_L) ? 'data:image/png;base64,' . base64_encode($S_L) : '';

// Handle form submission for password change
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $retypeNewPassword = $_POST['retype_new_password'];

    // Verify current password and update if valid
    if (password_verify($currentPassword, $Data['password'])) {
        if ($newPassword === $retypeNewPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2I);
            // Update query with proper syntax
            $updateQuery = "UPDATE $tableName SET password = :newPassword WHERE user_id = :id";
            $updateStmt = $pdoConnect->prepare($updateQuery);
            $updateStmt->bindParam(':newPassword', $hashedPassword);
            $updateStmt->bindParam(':id', $id);
            $updateStmt->execute();

            echo "<script type='text/javascript'>
            window.onload = function() {
                alert('Password updated successfully!');
                window.location.href = 'settings.php';
            };
        </script>";
        exit;
        } else {
            echo "<script type='text/javascript'>
            window.onload = function() {
                alert('New passwords do not match.');
                window.location.href = 'settings.php';
            };
        </script>";
        exit;
        }
    } else {
        echo "<script type='text/javascript'>
            window.onload = function() {
                alert('Current password is incorrect.');
                window.location.href = 'settings.php';
            };
        </script>";
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*"> 
    <!-- BOOTSTRAP STYLES -->
    <link href="assets/css/bootstrap.css?v=<?php echo time(); ?>" rel="stylesheet">
  <!-- FONTAWESOME STYLES-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- CUSTOM STYLES -->
    <link href="assets/css/custom.css?v=<?php echo time(); ?>" rel="stylesheet">
    <!-- GOOGLE FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <!-- TABLE STYLES -->
    <link href="assets/css/dataTables.bootstrap.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><?php echo $shortName?></a>
            </div>
            <div style="color: white;
            padding: 15px 50px 5px 50px;
            float: right;
            font-size: 16px;"> Last access : <?php echo date('d F Y')?> &nbsp; 
            <div class="btn-group nav-link">
              <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="ml-3"><?php echo $Name?></span>
            <span class="fa fa-caret-down">
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="profile.php"><span class="fa fa-user"></span> MY ACCOUNT</a>
            <hr style="margin-top: 5px; margin-bottom: 5px;">
            <a class="dropdown-item" href="settings.php"><span class="fa fa-gear"></span> SETTINGS</a>
            <hr style="margin-top: 5px; margin-bottom: 5px;">
            <a class="dropdown-item" href="logout.php"><span class="fa fa-sign-out"></span> LOG OUT </a>
          </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="user-image img-responsive" />
                        <h3 style="color:white;"><?php echo $Name?></h3>
                    </li>




                    <li>
                    <a href="dashboard.php"><i class="fa fa-dashboard fa-xl" style="font-size:24px;color:rgb(255, 255, 255)"></i>  DASHBOARD </a>
                    </li>
                    <li>
                    <a href="profile.php"><i class="fa fa-user fa-xl" style="font-size:24px;color:rgb(255, 255, 255)"></i> PROFILE </a>
                        </li>

                        <li>
                            <a href="create-ticket.php"><i class="fa fa-plus fa-xl" style="font-size: 24px; color: rgb(255, 255, 255)"></i> CREATE TICKET </a>
                            </li>
                            <li>
                        <a href="all-ticket.php"><i class="fa fa-ticket fa-xl" style="font-size:24px"></i> ALL TICKET </a>
                    </li>

                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12"> <div class="col-md-12">
                    <div class="col-md-12">
                        <h2>ACCOUNT SETTINGS</h2>
                        <hr>

                                    
                                    <form class="form-horizontal" role="form" method="post">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">CURRENT PASSWORD</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="password" name="current_password" id="old" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">NEW PASSWORD</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="password" name="new_password" id="new" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">RE-TYPE NEW PASSWORD</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="password" name="retype_new_password" id="renew" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label class="col-lg-3 control-label" style="font-size:small;">SHOW PASSWORDS</label>
                                        <div class="col-lg-8">
                                        <button type="button" id="togglePassword" class="password-btn" 
                                            onmousedown="showPassword()" onmouseup="hidePassword()">
                                            <i class="fas fa-eye" id="eyeIcon"></i>
                                        </button>
                                        </div>
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
        document.getElementById("old").type = "text";
        document.getElementById("new").type = "text";
        document.getElementById("renew").type = "text";
    }

    function hidePassword() {
        document.getElementById("old").type = "password";
        document.getElementById("new").type = "password";
        document.getElementById("renew").type = "password";
    }
</script>
                                        </div>
                                        
                                        <div class="modal-footer">	
                                            <!--<a href="edit-profile-picture.php"><button type="button" class="btn btn-primary">CHANGE PROFILE</button></a>-->
                                            <button type="submit" class="btn btn-primary">SAVE PASSWORD</button>
                                        </div>
                                        



                                        
                                                
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php require_once ('../footer.php')?>
                    </div>
                </div>
            </div>
        </div>
        
                        
                        
                        <!-- /. ROW -->
                    </div>
                </div>
                <!-- /. ROW -->
            </div>
            <!-- /. PAGE INNER -->
        </div>
        <!-- /. PAGE WRAPPER -->
    </div>
    <!-- /. WRAPPER -->
    <!-- SCRIPTS - AT THE BOTTOM TO REDUCE THE LOAD TIME -->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- DATA TABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });
    </script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
