<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["admin_number"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["admin_number"];

    $pdoUserQuery = "SELECT * FROM mis_employees WHERE admin_number = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();

    $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

    if ($Data) {
        $Name = $Data['f_name'];
        $Position = $Data['position'];
        $U_T = $Data['user_type'];

        $nameParts = explode(' ', $Name);
        $firstName = $nameParts[0];
    } else {
        // Handle the case where no results are found
        echo "No student found with the given student number.";
    }

    if ($Position !== "Director" && $Position !== "Staff" && $Position !== "Super Admin") {
        header("Location: ../Office/office.php");
        exit(); // Prevent further execution after redirection
    }

try {

    $pdoCountQuery = "SELECT * FROM tb_tickets";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $allTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Pending'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $pendingTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Processing'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $openedTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Returned'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $returnedTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Completed'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $completedTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Due'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $dueTickets = $pdoResult->rowCount();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DHVSU MIS - HelpHub</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
  
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
     <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

   <style>
    a {
        color: black;
    }
   </style>
</head>
<body>
    <div id="wrapper">
      <!-- NAV SIDE  -->
    <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE  -->
        
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>Admin Dashboard</h2>   
                        <h5>Welcome <?php echo $Name?>, Love to see you back. </h5>
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
                  <div class="row">

<!--<a href="ticket-pending.php">  -->            
            <div class="col-md-4 col-sm-6 col-xs-6">           
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-yellow set-icon">
                <i class="fa fa-hourglass-half " aria-hidden="true"></i>
                </span>

                <div class="text-box" >
                    <p class="main-text"><?php echo $pendingTickets?> Pending</p>
                    <p class="text-muted">Tickets</p>
                </div>
            </div>
            </div>
<!--</a>-->
<!--<a href="ticket-opened.php">-->
                    <div class="col-md-4 col-sm-6 col-xs-6">           
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-green set-icon">
                <i class="fa fa-envelope-open" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $openedTickets?> Opened </p>
                    <p class="text-muted">Tickets</p>
                </div>
             </div>
            </div>
<!--</a>-->
<!--<a href="ticket-closed.php">-->
                    <div class="col-md-4 col-sm-6 col-xs-6">           
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-brown set-icon">
                <i class="fa fa-check" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $completedTickets?> Closed</p>
                    <p class="text-muted">Tickets</p>
                </div>
             </div>
            </div>
            </div> 
<!--</a>            -->
                  <div class="row">
<!--<a href="ticket-pending.php">-->
                    <div class="col-md-4 col-sm-6 col-xs-6">           
			<div class="panel panel-back noti-box">
                <span class="icon-box bg-color-orange set-icon">
                <i class="fa fa-exclamation" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $dueTickets?> Overdue</p>
                    <p class="text-muted">Tickets</p>
                </div>
             </div>
		     </div>
<!--</a>-->
<!--<a href="ticket-returned.php">     -->        
                    <div class="col-md-4 col-sm-6 col-xs-6">           
			<div class="panel panel-back noti-box">
                <span class="icon-box bg-color-black set-icon">
                <i class="fa fa-reply" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $returnedTickets?> Returned</p>
                    <p class="text-muted">Tickets</p>
                </div>
             </div>
		     </div>
<!--</a>-->
<!--<a href="#">-->
                    <div class="col-md-4 col-sm-6 col-xs-6">           
			<div class="panel panel-back noti-box">
                <span class="icon-box bg-color-blue set-icon">
                <i class="fa fa-upload" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text">0 Transferred</p>
                    <p class="text-muted">Tickets</p>
                </div>
             </div>
		     </div>
<!--</a>-->
			</div>  
      
                 <!-- /. Calendar  -->   
                 <div class="col-md-8">
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
	<script src="./script.js"></script>
  
                 </div>     

<br>
<br>
<!--
                 <div class="col-md-4">                     
                    <div class="panel panel-default">
                        <div class="panel-heading">
                          <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">Yearly Report <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                            <li><a href="#">Weekly Report</a></li>
                            <li><a href="#">Monthly Report</a></li>
                            <li><a href="#">Yearly report</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                            </ul>
                          </div>
                        </div>
                        <div class="panel-body">
                        <div id="morris-line-chart"></div>
                      </div>
                    </div>            
                </div> 
-->                
        </div>
    
        
           </div>   
                 <!-- /. ROW  -->
                         
    </div>
             <!-- /. PAGE INNER  -->
            </div>
            
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
     <!-- MORRIS CHART SCRIPTS -->
     
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
    <script>Morris.Line({
                element: 'morris-line-chart',
                data: [{
                    y: '2006',
                    a: 100,
                    b: 90
                }, {
                    y: '2007',
                    a: 75,
                    b: 65
                }, {
                    y: '2008',
                    a: 50,
                    b: 40
                }, {
                    y: '2009',
                    a: 75,
                    b: 65
                }, {
                    y: '2010',
                    a: 50,
                    b: 40
                }, {
                    y: '2011',
                    a: 75,
                    b: 65
                }, {
                    y: '2012',
                    a: 100,
                    b: 90
                }],
                xkey: 'y',
                ykeys: ['a', 'b'],
                labels: ['Series A', 'Series B'],
                hideHover: 'auto',
                resize: true
            });
           </script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    <script>let profileDropdownList = document.querySelector(".profile-dropdown-list");
let btn = document.querySelector(".profile-dropdown-btn");

let classList = profileDropdownList.classList;

const toggle = () => classList.toggle("active");

window.addEventListener("click", function (e) {
  if (!btn.contains(e.target)) classList.remove("active");
});
</script>
    
   
</body>
</html>
