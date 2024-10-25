<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["admin_number"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["admin_number"];

    $pdoUserQuery = "SELECT * FROM mis_employees WHERE admin_number = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();

    $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

    if ($Data) {
        $Email_Add = $Data['email_address'];
        $Name = $Data['f_name'];
        $lname = $Data['l_name'];
        $P_P = $Data['profile_picture'];
        $Sex = $Data['sex'];
        $Age = $Data['age'];
        $Bday = $Data['birthday'];
        $U_T = $Data['user_type'];

    

        $P_PBase64 = base64_encode($P_P);
        $date = new DateTime($Bday);
        $formattedDate = $date->format('F j, Y'); // This will give "July 22, 1990"
    } else {
        // Handle the case where no results are found
        echo "No Admin found with the given student number.";
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
 
}

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
            $updateQuery = "UPDATE mis_employees SET password = :newPassword WHERE admin_number = :id";
            $updateStmt = $pdoConnect->prepare($updateQuery);
            $updateStmt->bindParam(':newPassword', $hashedPassword);
            $updateStmt->bindParam(':id', $id);
            $updateStmt->execute();

            echo "<script type='text/javascript'>
            window.onload = function() {
                alert('Password updated successfully!');
                window.location.href = 'change-password.php';
            };
        </script>";
        exit;
        } else {
            echo "<script type='text/javascript'>
            window.onload = function() {
                alert('New passwords do not match.');
                window.location.href = 'change-password.php';
            };
        </script>";
        exit;
        }
    } else {
        echo "<script type='text/javascript'>
            window.onload = function() {
                alert('Current password is incorrect.');
                window.location.href = 'change-password.php';
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
    <link href="assets/css/bootstrap.css" rel="stylesheet">
  <!-- FONTAWESOME STYLES-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- CUSTOM STYLES -->
    <link href="assets/css/custom1.css" rel="stylesheet">
    <link href="../admin/assets/css/custom.css" rel="stylesheet">
    <!-- GOOGLE FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <!-- TABLE STYLES -->
    <link href="assets/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

 
</head>

<body>
    <div id="wrapper">
    <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE -->
        <div id="page-wrapper">
            <div id="page-inner">
                
                    <div class="col-md-12">
                        <h2>Change Password</h2>          <hr>
                        <div class="container">
                            <h1 class="text-primary"></h1>
                  
                            <div class="row">
                               

<form class="form-horizontal" role="form" method="post" onsubmit='return confirmSubmit();'>
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
                                            onmousedown="showPassword()" onmouseup="hidePassword()" onmouseleave="hidePassword()">
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
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    }

    function hidePassword() {
        document.getElementById("old").type = "password";
        document.getElementById("new").type = "password";
        document.getElementById("renew").type = "password";
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
</script>
                                        </div>
                                        
                                        <div class="modal-footer">	
                                            <!--<a href="edit-profile-picture.php"><button type="button" class="btn btn-primary">CHANGE PROFILE</button></a>-->
                                            <button type="submit" class="btn btn-primary">SAVE PASSWORD</button>
                                            <button type="button" class="btn btn-primary" onclick="history.back()">Back</button>
                                        </div>
                                        



                                        
                                                
                                    </form>
                                    <script>
function confirmSubmit() {
    return confirm("Please confirm that the data you are submitting are true and correct.");}
</script>                                    
                                </div>
                           
                        </div>
                        <hr>
                        <?php include '../footer.php' ?> 
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
    <script src="../user/assets/js/custom.js"></script>
    <script type="text/javascript" src="post.js"></script>
</body>
</html>
