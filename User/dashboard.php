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
    

try {

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE user_number = :number";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();
    $allTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE user_number = :number AND status = 'Pending'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();
    $pendingTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE user_number = :number AND status = 'Returned'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();
    $returnedTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE user_number = :number AND status = 'Completed'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();
    $completedTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE user_number = :number AND status = 'Due'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();
    $dueTickets = $pdoResult->rowCount();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MIS HelpHub</title>
    <link rel="icon" href="../img/Logo.png" type="image/png">
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
  <!-- FONTAWESOME STYLES-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- MORRIS CHART STYLES-->
    <link href="assets/css/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
            <a class="dropdown-item" href="logout.php"><span class="fa fa-sign-out"></span> LOG OUT </a>
          </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <!--<img src="data:image/jpeg;base64,<?php //echo $P_PBase64?>" class="user-image img-responsive" />-->
                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="user-image img-responsive" />
                    </li>



                    <li>
                        <a class="active-menu" href="dashboard.php"><i class="bx bxs-dashboard fa" style="font-size:36px;color:rgb(255, 255, 255)"></i> DASHBOARD </a>
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
                        <a href="history.php"><i class="bx bx-history" style="font-size:36px"></i> HISTORY </a>
                    </li>
                    <li>
                        <a href="downloadableform.php"><i class="fa fa-download" style="font-size:36px"></i> DOWNLOADABLE FORMS </a>
                    </li>
                    <li>
                      <a href="about.php"><i class="fa fa-question-circle" style="font-size:36px"></i> ABOUT </a>
                  </li>
                </ul>
            </div>
        </nav>

        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>DASHBOARD</h2>
                        <h5>Welcome back, <?php echo $firstName?>!</h5>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr />
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-red set-icon">
                                <i class="fa fa-ticket"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $allTickets?></p>
                                <p class="text-muted">ALL TICKET</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-green set-icon">
                                <i class="fa fa-undo"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $returnedTickets?></p>
                                <p class="text-muted">RETURNED TICKET</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-blue set-icon">
                                <i class="fa fa-bell-o"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $dueTickets?></p>
                                <p class="text-muted">DUE DATE PASSED</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-brown set-icon">
                                <i class="fa fa-spinner"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $pendingTickets?></p>
                                <p class="text-muted">PENDING TICKET</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-black set-icon">
                                <i class="fa fa-check-circle-o"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $completedTickets?></p>
                                <p class="text-muted">COMPLETE TICKET</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="page-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>CALENDAR</h2>
                        </div>
                    </div>
                 <!-- /. Calendar  -->   
                 <div class="col-md-20">
                  <div class="wrapper">
     <div class="container-calendar">
       <div id="right">
          <h3 id="monthAndYear"></h3>
         <div class="button-container-calendar">
           <button id="previous"
               onclick="previous()">
             ‹
           </button>
          
           <button id="next"
               onclick="next()">
             ›
           </button>
         </div>
         <table class="table-calendar"
           id="calendar"
           data-lang="en">
           <thead id="thead-month"></thead>
           <!-- Table body for displaying the calendar -->
           <tbody id="calendar-body"></tbody>
         </table>
         <div class="footer-container-calendar">
           <label for="month">Jump To: </label>
           <!-- Dropdowns to select a specific month and year -->
           <select id="month" onchange="jump()">
             <option value=0>Jan</option>
             <option value=1>Feb</option>
             <option value=2>Mar</option>
             <option value=3>Apr</option>
             <option value=4>May</option>
             <option value=5>Jun</option>
             <option value=6>Jul</option>
             <option value=7>Aug</option>
             <option value=8>Sep</option>
             <option value=9>Oct</option>
             <option value=10>Nov</option>
             <option value=11>Dec</option>
           </select>
           <!-- Dropdown to select a specific year -->
           <select id="year" onchange="jump()"></select>
         </div>
       </div>
     </div>
   </div>
   <!-- Include the JavaScript file for the calendar functionality -->
   <script src="assets/js/script.js"></script>
   
                <!-- /. ROW  -->
            </div>
            <!-- /. PAGE INNER  -->
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
    <!-- MORRIS CHART SCRIPTS -->
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>

    <script>
      // Function to toggle dropdown state in session storage
      function toggleDropdownState() {
        var dropdownState = sessionStorage.getItem('ticketDropdownState');
        var newState = dropdownState === 'open' ? 'closed' : 'open';
        sessionStorage.setItem('ticketDropdownState', newState);
      }
    
      // Function to handle dropdown toggle and closing
      function handleTicketDropdownToggle(event) {
        var dropdown = document.querySelector('.ticket-dropdown-menu');
        var isOpen = dropdown.classList.contains('show');
        toggleDropdownState(); // Update session storage
    
        if (!isOpen) {
            dropdown.classList.add('show'); // Visually open the dropdown
        } else {
            // Check if a link inside the dropdown was clicked
            var linkClicked = event.target.closest('a'); 
    
            if (!linkClicked) { // Only close if it wasn't a link click
                dropdown.classList.remove('show'); // Visually close the dropdown
            } 
        }
    }
    
      // Function to restore dropdown state on page load
      function restoreTicketDropdownState() {
        var dropdownState = sessionStorage.getItem('ticketDropdownState');
        var dropdown = document.querySelector('.ticket-dropdown-menu');
    
        if (dropdownState === 'open') {
          dropdown.classList.add('show');
        } else {
          dropdown.classList.remove('show'); // Ensure dropdown is closed
        }
      }
      
      // Attach event listener to handle dropdown toggle
      document.addEventListener('DOMContentLoaded', function() {
        var dropdownToggle = document.querySelector('.dropdown-toggle');
        dropdownToggle.addEventListener('click', handleTicketDropdownToggle);
      });
      
    
      // Restore dropdown state when the page loads
      window.addEventListener('load', function() {
        restoreTicketDropdownState(); 
      });
    </script>
    
    
</body>

</html>
