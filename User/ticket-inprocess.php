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

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*"> 
    <link href="assets/js/DataTables/datatables.min.css" rel="stylesheet">

    <!-- BOOTSTRAP STYLES -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
  <!-- FONTAWESOME STYLES-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- MORRIS CHART STYLES -->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- CUSTOM STYLES -->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<style>
    /* Styles for the loading screen */
#loading-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.9);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    z-index: 9999;
}

.spinner {
    border: 16px solid #FFD700; /* Light grey */
    border-top: 16px solid #800000; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}

/* Animation for the spinner */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
   </style>
</head>

<body>
    <div id="loading-screen">
        <div class="spinner"></div>
        <p>Loading...</p>
    </div>
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
                        <a href="dashboard.php"><i class="bx bxs-dashboard" style="font-size:36px;color:rgb(255, 255, 255)"></i> DASHBOARD </a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="bx bx-user" style="font-size:36px;color:rgb(255, 255, 255)"></i> PROFILE </a>
                    </li>
                    <li>
                            <a href="create-ticket.php">
                            <i class="fa fa-plus" style="font-size: 36px; color: rgb(255, 255, 255)"></i> CREATE TICKET </a>
                            </li>
                            <li>
                        <a href="all-ticket.php"><i class="fa fa-ticket" style="font-size:36px"></i> ALL TICKET </a>
                    </li>
                </ul>
            </div>
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12"> <div class="col-md-12">
                    <div class="col-md-12">
                        <h2>IN PROCESS</h2>   
                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- Advanced Tables -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                LAST ACTIVITY
                            </div>
                            <div class="panel-body-ticket">
                                <div class="table-responsive">
<?php
    $status = "Processing";

    $pdoQuery = "SELECT * FROM tb_tickets WHERE status = :status && user_number = :usernumber";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->bindParam(':status', $status);
    $pdoResult->bindParam(':usernumber', $id, PDO::PARAM_STR);
    $pdoExec = $pdoResult->execute();

?> 
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr class="btn-primary">
                                                <th>TICKET ID</th>
                                                <th>DATE SUBMITTED</th>
                                                <th>MIS STAFF</th>
                                                <th>ISSUE</th>
                                                <th>DESCRIPTION</th>
                                                <th>STATUS</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
        <?php
        while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $screenshotBase64 = base64_encode($screenshot);
        ?>
                                            <tr class="odd gradeX">
                                                <td class="center"><?php echo htmlspecialchars($ticket_id); ?></td>                                           
                                                <td class="center"><?php echo htmlspecialchars($created_date); ?></td>
                                                <td class="center"><?php echo htmlspecialchars($employee); ?></td>
                                                <td class="center"><?php echo htmlspecialchars($issue); ?></td>
                                                <td class="center"><?php 
    $max_length = 25;
    if (strlen($description) > $max_length) {
        echo htmlspecialchars(substr($description, 0, $max_length)) . '...';
    } else {
        echo htmlspecialchars($description);
    }
    ?></td>
                                                <td><?php echo htmlspecialchars($status); ?></td>
                                                <td>
                                                        <a href="ticket-view.php?ticket_id=<?php echo $ticket_id; ?>">
                                                            <button class='btn btn-primary btn-xs'>
                                                            VIEW TICKET
                                                            </button>
                                                        </a>
<!--
                                                    <div class="panel-body-ticket">
                                                        <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal">VIEW TICKET</button>
                                                        <div class="modal fade" id="myModal">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                        <img src="assets/pic/head.png" alt="Technical support for DHVSU students">  
                                                                    </div>
                                                                    <div class="modal-body" style="background-color: white;"> 
                                                                        <h4 class="modal-title">TICKET STATUS</h4>
                                                                    <div class="panel-body-ticket">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-striped table-bordered table-hover">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="center">TIME</th>
                                                                                        <th class="center">DESCRIPTION</th>
                                                                                        <th class="center">STATUS</th>
                                                                                        <th class="center">PRIORITY</th>
                                                                                        <th class="center">MIS STAFF</th>
                                                                                        <th class="center">DURATION</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                
                                                                            </table>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <a href="#" data-dismiss="modal" class="btn">Back</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
-->
                                                </td>
                                            </tr>
                                            <!-- Add more rows if needed -->
        <?php
        }
        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /. PAGE INNER  -->
            <?php require_once ('../footer.php')?>
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

    <!-- Loading Screen -->    
<script>
    document.addEventListener('DOMContentLoaded', () => {
    // Simulate data fetching
    fetchData().then(() => {
        // Hide loading screen and show content
        document.getElementById('loading-screen').style.display = 'none';
        document.getElementById('content').style.display = 'block';
    });
});

function fetchData() {
    return new Promise((resolve) => {
        // Simulate a delay for data fetching (e.g., 2 seconds)
        setTimeout(() => {
            resolve();
        }, 500);
    });
}

</script>
</body>
</html>
