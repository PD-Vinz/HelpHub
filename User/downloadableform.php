﻿<?php
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

}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> USER </title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
  <!-- FONTAWESOME STYLES-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
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
                <a class="navbar-brand" href="dashboard.php">USER</a>
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
            <a class="dropdown-item" href="logout.php"><span class="fa fa-sign-out"></span> LOG OUT </a>          </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="user-image img-responsive" />
                    </li>


                    <li>
                        <a href="dashboard.php"><i class="bx bxs-dashboard fa" style="font-size:36px;color:rgb(255, 255, 255)"></i> DASHBOARD </a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="bx bx-user" style="font-size:36px;color:rgb(255, 255, 255)"></i> PROFILE </a>
                    </li>
                    <li>
                            <a href="create-ticket.php">
                            <i class="fa fa-plus" style="font-size: 36px; color: rgb(255, 255, 255)"></i> CREATE TICKET </a>
                    </li>
                    <li>
                        <a class="active-menu" href="all-ticket.php"><i class="fa fa-ticket" style="font-size:36px"></i> ALL TICKET </a>
                    </li>
                </ul>
            </div>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>DOWNLOADABLE FORMS</h2>
                    </div>
                </div>
                <div class="container d-flex justify-content-center mt-50 mb-50">
                    <div class="row">
                        <div class="col-md-12 text-right mb-3">
                            <button class="btn btn-primary" id="download"> DOWNLOAD FILE</button>
                        </div>
                        <div class="col-md-12">
                            <div class="card" id="invoice">
                                <div class="card-header bg-transparent header-elements-inline">
                                    <h6 class="card-title text-primary">FORM</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-4 pull-left">
                                            </div>
                                            <div class="panel-body-ticket">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                        <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>NAME OF STUDENT (Last Name, First Name, Middle Name)</th>
                                                                <th>BIRTHDAY (mm-dd-yyyy)</th>
                                                                <th>COMPLETE ADDRESS</th>
                                                                <th>CONTACT NUMBER</th>
                                                                <th>GUARDIAN’S COMPLETE NAME (Last Name, First Name, Middle Initial)</th>
                                                                <th>GUARDIAN’S ADDRESS</th>
                                                                <th>GUARDIAN’S CONTACT NUMBER</th>
                                                                <th>STUDENT’S SIGNATURE</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="odd gradeX">
                                                                <td class="center">1.</td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                            </tr>
                                                            <!-- Repeat for other rows if needed -->
                                                            </tr>
                                                            <tr class="odd gradeX">
                                                                <td class="center">2.</td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <!-- Repeat for other rows if needed -->
                                                            </tr>
                                                            <tr class="odd gradeX">
                                                                <td class="center">3.</td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                               <!-- Repeat for other rows if needed -->
                                                            </tr>
                                                            <tr class="odd gradeX">
                                                                <td class="center">4.</td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <!-- Repeat for other rows if needed -->
                                                            </tr>
                                                            <tr class="odd gradeX">
                                                                <td class="center">5.</td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <!-- Repeat for other rows if needed -->
                                                            </tr>
                                                            <tr class="odd gradeX">
                                                                <td class="center">6.</td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <!-- Repeat for other rows if needed -->
                                                            </tr>
                                                            <tr class="odd gradeX">
                                                                <td class="center">7.</td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <!-- Repeat for other rows if needed -->
                                                            </tr>
                                                            <tr class="odd gradeX">
                                                                <td class="center">8.</td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <!-- Repeat for other rows if needed -->
                                                            </tr>
                                                            <tr class="odd gradeX">
                                                                <td class="center">9.</td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <td class="center"></td>
                                                                <!-- Repeat for other rows if needed -->
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /. ROW  -->
            </div>
            <!-- /. PAGE INNER  -->
            <?php require_once ('../footer.php')?>
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

</body>

</html>
