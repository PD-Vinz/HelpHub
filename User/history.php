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

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USER</title>
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
                        <a href="#"><i class="fa fa-ticket" style="font-size:36px;color:rgb(255, 255, 255)"></i> TICKET <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="create-ticket.php"><i class="fa fa-plus"></i>CREATE NEW TICKET</a>
                            </li>
                            <li>
                                <a href="ticket-pending.php"><i class="fa fa-refresh"></i>PENDING TICKET</a>
                            </li>
                            <li>
                                <a href="ticket-inprocess.php"><i class="fa fa-spinner"></i> IN PROCESS</a>
                            </li>
                            <li>
                                <a href="ticket-returned.php"><i class="fa fa-undo"></i> RETURNED TICKET</a>
                            </li>
                            <li>
                                <a href="ticket-finished.php"><i class="fa fa-check"></i> COMPLETE TICKET</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="active-menu" href="history.php"><i class="bx bx-history" style="font-size:36px"></i> HISTORY </a>
                    </li>
                    <li>
                        <a href="downloadableform.php"><i class="fa fa-download" style="font-size:36px"></i> DOWNLOADABLE FORM </a>
                    </li>
                    <li>
                        <a href="about.php"><i class="fa fa-question-circle" style="font-size:36px"></i> ABOUT </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>HISTORY</h2>
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
$pdoQuery = "SELECT * FROM tb_tickets WHERE user_number = :usernumber";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->bindParam(':usernumber', $id, PDO::PARAM_INT);
$pdoExec = $pdoResult->execute();

while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $screenshotBase64 = base64_encode($screenshot);
?>
                                                    <tr class="odd gradeX">
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
                                                                            <a href="survey.php?id=<?php echo $ticket_id; ?>&taken=after"><button type="button" class="btn btn-primary">TAKE SURVEY</button></a>
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
                </div>
                <!-- /. PAGE WRAPPER -->
            </div>
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
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });
    </script>

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
