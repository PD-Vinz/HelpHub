<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["user_id"];
    $identity = $_SESSION["user_identity"];

    if ($identity == "Student"){
        $pdoUserQuery = "SELECT * FROM student_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->execute();
    
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
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
            $formattedDate = $date->format('F j, Y'); // This will give "July 22, 1990"
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }
    } elseif ($identity == "Employee") {
        $pdoUserQuery = "SELECT * FROM employee_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->execute();
    
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
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
            $formattedDate = $date->format('F j, Y'); // This will give "July 22, 1990"
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }
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
    <link href="assets/css/custom.css" rel="stylesheet">
    <!-- GOOGLE FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <!-- TABLE STYLES -->
    <link href="assets/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        #preview {
            position: relative;
            display: inline-block;
        }
        #preview-image {
            display: block;
            max-width: 100%;
        }
        #resize-controls {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .resize-handle {
            width: 10px;
            height: 10px;
            background: red;
            position: absolute;
        }
        .resize-handle.bottom-right {
            bottom: 0;
            right: 0;
            cursor: se-resize;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            position: relative;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
        }
    </style>
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
                    <a class="active-menu" href="profile.php"><i class="fa fa-user fa-xl" style="font-size:24px;color:rgb(255, 255, 255)"></i> PROFILE </a>
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
                        <h2>PROFILE</h2>
                        <div class="container">
                            <h1 class="text-primary"></h1>
                            <hr>
                          
<form class="form-horizontal" role="form" method="post" action="update_profile.php" enctype="multipart/form-data" onsubmit='return confirmSubmit();'>
                                <!-- left column -->
                                <div class="avatar" id="avatar">
                                    <div id="preview">
                                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" id="avatar-image" class="avatar_img" id="">
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
                                            <label class="col-lg-3 control-label">NAME</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="name" type="text" value="<?php echo $Name?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">USER ID</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="id" type="text" value="<?php echo $id?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">EMAIL ADDRESS</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="emailadd" type="text" value="<?php echo $Email_Add?>" readonly>
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
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">AGE</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="age" type="text" value="<?php echo $Age?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">CAMPUS </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="campus" type="text" value="<?php echo $Campus?>" readonly >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">DEPARTMENT </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="dept" type="text" value="<?php echo $Department?>" readonly >
                                            </div>
                                        </div>
                                        <?php if ( $identity === 'Student'): ?>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">COURSE </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="course" type="text" value="<?php echo $Course?>" readonly >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">YEAR AND SECTION </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="ys" type="text" value="<?php echo $Y_S?>" readonly >
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="modal-footer">	
                                            <input type="submit" class="btn btn-primary" name="update" value="UPDATE PROFILE"  >
                                            <a href="profile.php"><button type="button" class="btn btn-primary">BACK</button></a>
                                        </div>
                                        



                                        
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <script>
function confirmSubmit() {
    return confirm("Please make sure that the data you are submitting are true. Are you sure you want to proceed?");}
</script>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php require_once ('../footer.php')?>  
                </div>
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
    <script type="text/javascript" src="post.js"></script>
</body>
</html>
