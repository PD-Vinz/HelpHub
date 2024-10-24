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
            $Name = $Data['name'];
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
            $Name = $Data['name'];
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

if(isset($_GET['error']) && $_GET['error'] == 1) {
    // Set your error message here
    $errorMessage = "Cannot proceed with your request. Please check your submission carefully.";
    echo "<script type='text/javascript'>
        window.onload = function() {
            alert('$errorMessage');
            window.location.href = 'reports.php';
        };
    </script>";
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*"> 
    <link href="assets/js/DataTables/datatables.min.css?v=<?php echo time(); ?>" rel="stylesheet">
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

   <style>
        
        img {
            max-width: 100%;
            max-height: 385px;
            width: auto;
            height: auto;
            border-radius: 5px;
        }

    .back-button {
        background-color: #3498db; /* Primary color */
        color: #fff; /* Text color */
        border: none; /* Remove border */
        padding: 10px 20px; /* Padding for size */
        font-size: 16px; /* Font size */
        font-weight: bold; /* Font weight */
        border-radius: 5px; /* Rounded corners */
        cursor: pointer; /* Pointer cursor on hover */
        transition: background-color 0.3s, transform 0.2s; /* Smooth transition */
    }

    .back-button:hover {
        background-color: #2980b9; /* Darker color on hover */
        transform: translateY(-2px); /* Slight lift effect */
    }

    .back-button:active {
        background-color: #1e6fa1; /* Even darker on active */
        transform: translateY(1px); /* Pressed effect */
    }

    .back-button:focus {
        outline: none; /* Remove focus outline */
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.3); /* Focus shadow */
    }
    </style>
</head>
<?php include 'loading.php'; ?>
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
            <?php if (!isset($_SESSION["Super-Admin"])): ?>
                <?php if ($identity == "Student"): ?>
                <a class="dropdown-item" href="logout.php" onclick="window.open('https://forms.gle/Bf2yoFEiYE8k56Pb6', '_blank');"><span class="fa fa-sign-out"></span> LOG OUT </a>
                <?php elseif ($identity == "Employee"): ?>
                <a class="dropdown-item" href="logout.php" onclick="window.open('https://forms.gle/kUJQW5YTbBfKKMw37', '_blank');"><span class="fa fa-sign-out"></span> LOG OUT </a>
                <?php endif; ?>
            <?php elseif (isset($_SESSION["Super-Admin"]) && $_SESSION["Super-Admin"] === 'Log In Success'): ?>
                <a class="dropdown-item" href="../index.php"><span class="fas fa-sign-out-alt"></span> Log Out</a>
            <?php endif; ?>
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
                            <a class="active-menu" href="create-ticket.php"><i class="fa fa-plus fa-xl" style="font-size: 24px; color: rgb(255, 255, 255)"></i> CREATE TICKET </a>
                            </li>
                            <li>
                        <a href="all-ticket.php"><i class="fa fa-ticket fa-xl" style="font-size: 24px; color: rgb(255, 255, 255)"></i> ALL TICKET </a>
                    </li>

                </ul>
               
            </div>
            
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <button class="back-button" onclick="history.back()"><i class="fas fa-arrow-left"></i> BACK</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                     <h2>TICKET #<?php echo $_GET["ticket_id"]?></h2>   
                    </div>
                </div>
                 <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-12">
                    <!-- Advanced Tables -->
                                       
<?php
$pdoQuery = "SELECT * FROM tb_tickets WHERE ticket_id = :TID";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->bindParam(':TID', $_GET["ticket_id"], PDO::PARAM_STR);
$pdoExec = $pdoResult->execute();

while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
extract($row);

$screenshotBase64 = base64_encode($screenshot);
?>

                                    <h3>TICKET DETAILS</h3>
                    <div class="col-md-6 col-sm-6 col-xs-6">               

                                        <div class="form-group">
                                            <label>TICKET ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($ticket_id); ?>" disabled/>
                                            
                                        </div>
                                        <div class="form-group">
                                            <label>ISSUE/PROBLEM  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($issue); ?>" disabled/>
                                            
                                        </div>
                                        <div class="form-group">
                                            <label>DESCRIPTION ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" disabled style="height:148px; resize:none; overflow:auto;"><?php echo htmlspecialchars($description); ?></textarea>
                                            <!--<input class="form-control" value="<?php // echo htmlspecialchars($description); ?>" disabled style=""/> -->
                                            
                                        </div>
                                        <div class="form-group">
                                            <label>SCREENSHOT ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <a href="view_image.php?id=<?php echo htmlspecialchars($ticket_id); ?>" target="_blank">
                                                <img src="data:image/jpeg;base64,<?php echo $screenshotBase64; ?>" alt="Screenshot" class="img-fluid">
                                            </a>
<br><br>

                                            
                                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                                        <label>EMPLOYEE NAME </label>
                                            <input class="form-control" value="<?php echo !empty($employee) ? htmlspecialchars($employee) : 'No Data Available'; ?>" disabled/>
                                            
                                        </div>
                                      
                                        <div class="form-group">
                                            <label>OPENED ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo !empty($opened_date) ? htmlspecialchars($opened_date) : 'No Data Available'; ?>" disabled/>
                                         
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>CLOSED ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo !empty($finished_date) ? htmlspecialchars($finished_date) : 'No Data Available'; ?>" disabled/>
                                         <br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>DURATION ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            
                                            <input class="form-control" value="<?php echo !empty($duration) ? htmlspecialchars($duration) : 'No Data Available'; ?>" disabled/>
                                            <br>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>RESOLUTION ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" disabled style="height: 378px; resize:none; overflow:auto;"><?php echo !empty($resolution) ? htmlspecialchars($resolution) : 'No Data Available'; ?></textarea>
                                            <br><br>
                                        </div>

                                
                    </div>
<!--
                    <div class="col-12">
                                        <div class="form-group">
                                            <label>RESOLUTION ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" disabled style="height: 378px; resize:none; overflow:auto;"><?php echo htmlspecialchars($resolution); ?></textarea>
                                            <br><br>
                                        </div>
                    </div>
-->
                    <?php } ?> 
                    <div class="col-12"> 
                        <h3>TICKET PROGRESS</h3>
                        <table class="table table-bordered table-striped table-hover" id="dataTables-example">
                            <thead>
                                <tr class="btn-primary">
                                <th>DATE & TIME</th>
                                <th>DESCRIPTION</th>
                                <th>STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
$pdoQuery = "SELECT * FROM ticket_logs WHERE ticket_id = :TID";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->bindParam(':TID', $_GET["ticket_id"], PDO::PARAM_STR);
$pdoExec = $pdoResult->execute();

while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
extract($row);
echo "<tr>";
echo "<td>$date_time</td>";
echo "<td>$description</td>";
echo "<td>$status</td>";
echo "</tr>";
}
?>
                            </tbody>
                    </div>
            
                    
        </div>
</div>           
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
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
    <script src="assets/js/dataTables/dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });
    </script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
