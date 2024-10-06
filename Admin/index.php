<?php
include_once("../connection/conn.php");
require_once('../connection/bdd.php');


$pdoConnect = connection();
$_SERVER['REQUEST_URI'];
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
        $lname = $Data['l_name'];
        $Position = $Data['position'];
        $U_T = $Data['user_type'];
        $P_P = $Data['profile_picture'];

        $P_PBase64 = base64_encode($P_P);


        $nameParts = explode(' ', $Name);
        $firstName = $nameParts[0];
    } else {
        // Handle the case where no results are found
        echo "No student found with the given student number.";
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

try {
    
	$sql = "SELECT id, title, start, end, color FROM events ";

	$req = $pdoConnect->prepare($sql);
	$req->execute();

	$events = $req->fetchAll();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Resolved'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $completedTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Due'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $dueTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Transferred'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $transferredTickets = $pdoResult->rowCount();
    
 
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
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*">   

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
     <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    
        <!-- CUSTOM STYLES-->
        <link href="../helphub/FullCalendar-BS3-PHP-MySQL-master/css/fullcalendar.css" rel="stylesheet" />

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
                    <div class="col-md-12">

                     <h2>Admin Dashboard</h2>   
                     <hr>
                    </div>
                        
                 <!-- /. ROW  -->

<!--<a href="ticket-pending.php">  -->       <div class="col-md-12">
    
<div class="col-md-2 col-sm-6 col-xs-6">        
    
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-yellow set-icon">
                <i class="fa fa-hourglass-half fa-xs" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $pendingTickets?> Pending</p>
                   <!-- <p class="text-muted">Tickets</p> -->
                </div>
             </div>
            </div>
                    <div class="col-md-2 col-sm-6 col-xs-6">           
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-green set-icon">
                <i class="fa fa-envelope-open fa-xs" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $openedTickets?> Processing </p>
                   <!-- <p class="text-muted">Tickets</p> -->
                </div>
             </div>
            </div>
                    <div class="col-md-2 col-sm-6 col-xs-6">           
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-brown set-icon">
                <i class="fa fa-check fa-xs" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $completedTickets?> Closed</p>
                   <!-- <p class="text-muted">Tickets</p> -->
                </div>
             </div>
            </div>
            
               
                    <div class="col-md-2 col-sm-6 col-xs-6">           
			<div class="panel panel-back noti-box">
                <span class="icon-box bg-color-black set-icon">
                <i class="fa fa-reply fa-xs" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $returnedTickets?> Returned</p>
                   <!-- <p class="text-muted">Tickets</p> -->
                </div>
             </div>
		     </div>
                    <div class="col-md-2 col-sm-6 col-xs-6">           
			<div class="panel panel-back noti-box">
                <span class="icon-box bg-color-blue set-icon">
                <i class="fa fa-upload fa-xs" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $transferredTickets?> Transferred</p>
                   <!-- <p class="text-muted">Tickets</p> -->
                </div>
             </div>
<!--</a>-->
			</div><hr>  </div> 
      
                 <!-- /. Calendar  -->   
                 <div class="col-md-12">
         <?php
// Assuming you have a way to track the user's role
$role = $U_T; // 'admin' or 'user'
// Pass the role to the iframe URL or JavaScript
?>
                 <iframe class="panel panel-default calendarf" src="../FullCalendar-BS3-PHP-MySQL-master/index.php" style="width: 100%; min-height:710px; height: auto; min-width:auto; border-radius: 10px; margin-bottom:8px"></iframe>

	<!-- Include the JavaScript file for the calendar functionality -->
	
  
                      
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
           </div> <?php include '../footer.php' ?>  </div>
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
    <script src="../helphub/FullCalendar-BS3-PHP-MySQL-master/js/fullcalendar.min.js"></script>

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
