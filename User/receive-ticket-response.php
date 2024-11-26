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
            $Name = $Data['first_name'];
            $LastName = $Data['last_name'];
            $MiddleName = $Data['middle_name'];
            $MiddleInitial = $Data['middle_initial'];
            $ExtensionName = $Data['ext_name'];

// Check if there's a middle name or initial
if (!empty($MiddleInitial)) {
    $Name .= " " . $MiddleInitial  . ".";
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
            $Department = $Data['department'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
    
            $nameParts = explode(' ', $Name);
            $firstName = $nameParts[0];
    
            $P_PBase64 = base64_encode($P_P);
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

            $Department = $Data['department'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
    
            $nameParts = explode(' ', $Name);
            $firstName = $nameParts[0];
    
            $P_PBase64 = base64_encode($P_P);
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
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*"> 
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css?v=<?php echo time(); ?>" rel="stylesheet" />
  <!-- FONTAWESOME STYLES-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css?v=<?php echo time(); ?>" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css?v=<?php echo time(); ?>" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<?php include 'loading.php'; ?>
<body>
    <div id="wrapper">
    <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12"> <div class="col-md-12">
                    <div class="col-md-12">
                        <h2>RECEIVED TICKET</h2>
                    </div>
                </div>

                <div class="container-center">
                    <div class="modal-header">
                        <img src="assets/pic/head.jpg" alt="Technical support for DHVSU students">  
                    <div class="container-survey">
                    <!-- Make this message be editable by the admin-->
                    <div class="message">
                        <p>Hi, Good Day!</p>
                        <p>We appreciate you addressing your concern with us. Please wait for a response from our MIS Employee.</p>
                        <p>If you encounter any further issues, feel free to submit another ticket. We would be happy to assist you.</p>
                        <p>Thank you & God bless</p><br>
                        <p>DHVSU-MiS Technical Support<br></p>
                    </div>
                    <div class="modal-footer">	
                        <a href="dashboard.php"> <button class="btn btn-primary">Home</button></a>
                        <!--<a href="survey.php?id=<?php echo $_GET['id']?>&taken=before"> <button>Take Survey</button></a>-->
                    </div>
                </div>
                <!-- /. ROW  -->
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
       
</div>
            </div>
        </div><?php require_once ('../footer.php')?>
    </div>
    <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- MORRIS CHART SCRIPTS -->
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>

</html>
