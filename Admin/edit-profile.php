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
    $query = $pdoConnect->prepare("SELECT system_name, short_name, system_logo, system_cover FROM settings WHERE id = :id");
    $query->execute(['id' => 1]);
    $Datas = $query->fetch(PDO::FETCH_ASSOC);
    $sysName = $Datas['system_name'] ?? '';
    $shortName = $Datas['short_name'] ?? '';
    $systemLogo = $Datas['system_logo'];
    $systemCover = $Datas['system_cover'];
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $sysName?></title>
    <link rel="icon" href="../img/logo.png" type="image/png">
  
    <!-- BOOTSTRAP STYLES -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
  <!-- FONTAWESOME STYLES-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- CUSTOM STYLES -->
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
                        <h2>Edit profile</h2>          <hr>
                        <div class="container">
                            <h1 class="text-primary"></h1>
                  
                            <div class="row">
                               
<form class="form-horizontal" role="form" method="post" action="action\update_profile.php" enctype="multipart/form-data" onsubmit='return confirmSubmit();'>
                                <!-- left column -->

                                
                                <div class="avatar" id="avatar">
                                    <div id="preview">
                                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" id="avatar-image" class="avatar_img" id="" alt="No Image">
                                    </div>
                                    <div class="avatar_upload">
                                        <label class="upload_label">Choose
                                            <input type="file" id="upload" name="image" accept="image/*">
                                        </label>
                                    </div>
                                  </div>


                                  <div class="nickname">
                                    <span id="name" tabindex="4" data-key="1" contenteditable="true" onkeyup="changeAvatarName(event, this.dataset.key, this.textContent)" onblur="changeAvatarName('blur', this.dataset.key, this.textContent)" hidden></span>
                                  </div>
                                <!-- edit form column -->
                                <div class="col-md-9 personal-info">
                                    <div> <h3>PERSONAL INFORMATION</h3>
                                    </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">FIRST NAME</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="fname" type="text" value="<?php echo $Name?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">LAST NAME</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="lname" type="text" value="<?php echo $lname?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">EMAIL ADDRESS</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="emailadd" type="text" value="<?php echo $Email_Add?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">GENDER</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="sex" type="text" value="<?php echo $Sex?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">BIRTHDAY</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="bday" type="date" value="<?php echo $Bday?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">	
                                            <input type="submit" class="btn btn-primary" name="update" value="UPDATE PROFILE"  >
                                            <button type="button" class="btn btn-primary" onclick="history.back()">BACK</button>
                                        </div>
                                        



                                        
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <script>
function confirmSubmit() {
    return confirm("Please confirm that the data you are submitting are true and correct.");}
</script>                                    
                                </div>
                           
                        </div>
                        <hr>
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
