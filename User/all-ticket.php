<?php
include 'loading.php';

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
    <link href="assets/js/DataTables/datatables.min.css?v=<?php echo time(); ?>" rel="stylesheet">

    <!-- BOOTSTRAP STYLES -->
    <link href="assets/css/bootstrap.css?v=<?php echo time(); ?>" rel="stylesheet">
    <!-- FONTAWESOME STYLES-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css?v=<?php echo time(); ?>">
    <!-- CUSTOM STYLES -->
    <link href="assets/css/custom.css?v=<?php echo time(); ?>" rel="stylesheet">
    <!-- GOOGLE FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <!-- TABLE STYLES -->
    <link href="assets/css/dataTables.bootstrap.css?v=<?php echo time(); ?>" rel="stylesheet">
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
                        <!--<img src="data:image/jpeg;base64,<?php //echo $P_PBase64?>" class="user-image img-responsive" />-->
                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="user-image img-responsive" />
                        <h3 style="color:white;"><?php echo $Name?></h3>
                    </li>
                    <li>
                        <a href="dashboard.php"><i class="fa fa-dashboard fa-xl" style="font-size: 24px;color:rgb(255, 255, 255)"></i> DASHBOARD </a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="fa fa-user fa-xl" style="font-size: 24px;color:rgb(255, 255, 255)"></i> PROFILE </a>
                    </li>
                    <li>
                            <a href="create-ticket.php"><i class="fa fa-plus fa-xl" style="font-size: 24px; color: rgb(255, 255, 255)"></i> CREATE TICKET </a>
                    </li>
                    <li>
                        <a class="active-menu" href="all-ticket.php"><i class="fa fa-ticket fa-xl" style="font-size: 24px; color: rgb(255, 255, 255)"></i> ALL TICKET </a>
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
    // Extract variables from the row for convenience
    extract($row);

    // Determine the CSS class based on the ticket status
    $statusClass = ($row['status'] === 'Resolved') ? 'success' :
                  (($row['status'] === 'Pending') ? 'danger' :
                  (($row['status'] === 'Returned') ? 'info' :
                  (($row['status'] === 'Processing') ? 'warning' : '')));

    ?>
    <tr class="odd gradeX <?php echo $statusClass?>">
        <td class="center"><?php echo htmlspecialchars($ticket_id); ?></td>
        <td class="center"><?php echo htmlspecialchars($created_date); ?></td>
        <td class="center"><?php echo htmlspecialchars($issue); ?></td>
        <td class="center"><?php echo htmlspecialchars($employee); ?></td>
        <td class="center"><?php echo htmlspecialchars($status); ?></td>
        <td><?php echo htmlspecialchars($duration); ?></td>
        <td>
<?php

if ($status == 'Resolved') {
// Display the button that triggers the modal
echo '<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal' . htmlspecialchars($ticket_id) . '">
VIEW TICKET
</button>';

} else {
// Display the link with the button
echo '<a href="ticket-view.php?ticket_id=' . htmlspecialchars($ticket_id) . '">
<button class="btn btn-primary btn-xs">
VIEW TICKET
</button>
</a>';
}
?>

    <div class="modal fade" id="myModal<?php echo $ticket_id; ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <img src="assets/pic/head.png" alt="Technical support for DHVSU students">  
                </div>
                <div class="modal-body" style="background-color: white;"> 
                    <h4 class="modal-title">TICKET STATUS</h4>
                    <div class="letter">
                        <main>
                            <style>
                                p {
                                    line-height: 1.5; 
                                    margin-bottom: 20px; 
                                }
                        
                                h1 {
                                    line-height: 1.2; 
                                    margin-bottom: 10px; 
                                }
                            </style>
                            <?php echo nl2br(htmlspecialchars($resolution)); ?>
                        </main>
                    
                        <div class="modal-footer">
                            <a href="survey.php?id=<?php echo $ticket_id; ?>"><button type="button" class="btn btn-primary">TAKE SURVEY</button></a>
                            <a href="ticket-view.php?ticket_id=<?php echo $ticket_id; ?>"><button class='btn btn-primary'>VIEW TICKET</button></a>                                                                    </div>
                </div>
            </div>
        </div>
    </div>


        </td>
    </tr>
<?php
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

    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
