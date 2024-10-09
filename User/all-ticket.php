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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*"> 
    <link href="assets/js/DataTables/datatables.min.css" rel="stylesheet">

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
    /* Styles for the loading screen */
#loading-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
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
                        <!--<img src="data:image/jpeg;base64,<?php //echo $P_PBase64?>" class="user-image img-responsive" />-->
                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="user-image img-responsive" />
                        <h3 style="color:white;"><?php echo $Name?></h3>
                    </li>
                    <li>
                        <a href="dashboard.php"><i class="fa fa-dashboard fa-xl" style="font: size 24px;color:rgb(255, 255, 255)"></i> DASHBOARD </a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="fa fa-user fa-xl" style="font: size 24px;color:rgb(255, 255, 255)"></i> PROFILE </a>
                    </li>
                    <li>
                            <a href="create-ticket.php"><i class="fa fa-plus fa-xl" style="font-size: 24px; color: rgb(255, 255, 255)"></i> CREATE TICKET </a>
                    </li>
                    <li>
                        <a class="active-menu" href="all-ticket.php"><i class="fa fa-ticket fa-xl" style="font-size:24px"></i> ALL TICKET </a>
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

                        <h2>ALL TICKET</h2>
                        <!-- /. ROW -->
                        <div id="content" class="row">
                            <div class="col-md-12">
                                <!-- Advanced Tables -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        LAST ACTIVITY
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                <thead>
                                                    <tr class="btn-primary">
                                                        <th>TICKET NUMBER</th>
                                                        <th>DATE</th>
                                                        <th>PROBLEM</th>
                                                        <th>MIS STAFF</th>
                                                        <th>STATUS</th>
                                                        <th>DURATION</th>
                                                        <th>ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
// Database query to select tickets for a specific user
$pdoQuery = "SELECT * FROM tb_tickets WHERE user_number = :usernumber";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->bindParam(':usernumber', $id, PDO::PARAM_INT);
$pdoExec = $pdoResult->execute();

// Loop through each result row
while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
    // Determine the CSS class based on the ticket status
    $statusClass = ($row['status'] === 'Resolved') ? 'success' :
                  (($row['status'] === 'Pending') ? 'danger' :
                  (($row['status'] === 'Transferred') ? 'info' :
                  (($row['status'] === 'Processing') ? 'warning' : '')));

    // Extract variables from the row for convenience
    extract($row);

    // Render the table row
    echo "<tr class='odd gradeX $statusClass'>";
    echo "<td>" . htmlspecialchars($ticket_id) . "</td>";
    echo "<td>" . htmlspecialchars($created_date) . "</td>";
    echo "<td>" . htmlspecialchars($issue) . "</td>";
    echo "<td>" . htmlspecialchars($employee) . "</td>";
    echo "<td>" . htmlspecialchars($status) . "</td>";
    echo "<td>" . htmlspecialchars($duration) . "</td>";

    // Render the modal trigger and ticket-view link
    echo "<td>
            <!-- Link to ticket-view.php page -->
            <a href='ticket-view.php?ticket_id=" . htmlspecialchars($ticket_id) . "' class='btn btn-primary btn-xs'>
                VIEW TICKET
            </a>
        </div>
    </td>";

    echo "</tr>";

    // Modal content for viewing ticket details
    echo "<div class='modal fade' id='myModal" . htmlspecialchars($ticket_id) . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel" . htmlspecialchars($ticket_id) . "' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='myModalLabel" . htmlspecialchars($ticket_id) . "'>Ticket #" . htmlspecialchars($ticket_id) . "</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <p>Ticket Details for #" . htmlspecialchars($ticket_id) . "</p>
                    <p>Issue: " . htmlspecialchars($issue) . "</p>
                    <p>Employee: " . htmlspecialchars($employee) . "</p>
                    <p>Status: " . htmlspecialchars($status) . "</p>
                    <p>Created Date: " . htmlspecialchars($created_date) . "</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                </div>
            </div>
        </div>
    </div>";
}
?>

                             </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <!-- /. Advanced Tables -->
                            </div>   
                        </div>
                        <!-- /. ROW -->
                    </div> 
                    <!-- /. PAGE INNER -->
                     </div></div></div>
                <!-- /. PAGE WRAPPER -->
            
       </div><?php require_once ('../footer.php')?>
       <!-- /. WRAPPER -->
       </div>
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
 <?php require_once ('../footer.php')?>
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

    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
