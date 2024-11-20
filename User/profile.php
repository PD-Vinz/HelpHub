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
            $User_ID = $Data['user_id'];
            $Email_Add = $Data['email_address'];
            $Alt_Email_Add = $Data['alt_email_address'];

            $Name = $Data['first_name'];
            $LastName = $Data['last_name'];
            $MiddleName = $Data['middle_name'];
            $MiddleInitial = $Data['middle_initial'];
            $ExtensionName = $Data['ext_name'];

// Check if there's a middle name or initial
if (!empty($MiddleInitial)) {
    $Name .= " " . $MiddleInitial . ".";
} elseif (!empty($MiddleName)) {
    $Name .= " " . $MiddleName;
}

// Add last name if it exists
if (!empty($LastName)) {
    $Name .= " " . $LastName;
}

// Add extension name if it exists (e.g., Jr., Sr., III)
if (!empty($ExtensionName)) {
    $Name .= " " . $ExtensionName . ".";
}
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
            $Alt_Email_Add = $Data['alt_email_address'];
            
            $Name = $Data['first_name'];
            $LastName = $Data['last_name'];
            $MiddleName = $Data['middle_name'];
            $MiddleInitial = $Data['middle_initial'];
            $ExtensionName = $Data['ext_name'];

// Check if there's a middle name or initial
if (!empty($MiddleInitial)) {
    $Name .= " " . $MiddleInitial . ".";
} elseif (!empty($MiddleName)) {
    $Name .= " " . $MiddleName;
}

// Add last name if it exists
if (!empty($LastName)) {
    $Name .= " " . $LastName;
}

// Add extension name if it exists (e.g., Jr., Sr., III)
if (!empty($ExtensionName)) {
    $Name .= " " . $ExtensionName . ".";
}

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
</head>
<?php include 'loading.php'; ?>
<body>
    <div id="wrapper">
    <?php include 'nav.php'; ?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                    <div class="col-md-12">
                        <h2>PROFILE</h2>
                        <hr>
                                <!-- left column -->
                                <div class="col-md-3">


                                <div class="avatar" id="avatar">
                                    <div id="preview">
                                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" id="avatar-image" class="avatar_img" id="">
                                    </div>
                                   
                                  </div>

                                    <div class="text-center">

                                   
                                        <h3><?php echo $Name?></h3>
                                        <h5 style="text-transform: uppercase;"><?php echo $UserType?></h5>
                                    </div>
                                </div>
        
                                <!-- edit form column -->
                                <div class="col-md-9 personal-info">
                                    
                                    <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">USER ID</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $id?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">EMAIL ADDRESS</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $Email_Add?>" disabled>
                                            </div>
                                        </div>
                                        <?php if (!empty($Alt_Email_Add)): ?>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">ALT. EMAIL ADDRESS</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" name="altemailadd" type="text" value="<?php echo $Alt_Email_Add?>" readonly>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">SEX ASSIGN AT BIRTH</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $Sex?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">BIRTHDAY</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $formattedDate?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">AGE</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $Age?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">CAMPUS </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $Campus?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">DEPARTMENT </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $Department?>" disabled>
                                            </div>
                                        </div>
                                        <?php if ( $identity === 'Student'): ?>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">COURSE </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $Course?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">YEAR AND SECTION </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $Y_S?>" disabled>
                                            </div>
                                        </div>
                                        <br>
                                        <?php endif; ?>
                                      
                                        



                                        
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div><div class="col-md-12"><div class="modal-footer">	
                                            <!--<a href="edit-profile-picture.php"><button type="button" class="btn btn-primary">CHANGE PROFILE</button></a>-->
                                            <a href="edit-profile.php"><button type="button" class="btn btn-primary">UPDATE INFORMATION</button></a>
                                        </div></div>
                            </div>     <?php require_once ('../footer.php')?> 
                        </div>
                      
                </div>
      
                    </div>    </div>
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
