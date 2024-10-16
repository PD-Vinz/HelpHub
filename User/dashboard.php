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

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE user_number = :number AND status = 'Resolved'";
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
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*"> 
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
      	/* Basic styling for the "Back to Top" button */
#astroid-backtotop {
    display: none; /* Hide button by default */
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    background-color: #007bff;
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 50px; /* Center icon vertically */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    font-size: 24px;
    cursor: pointer;
    z-index: 1000; /* Make sure button is above other content */
    transition: opacity 0.3s ease;
  }
  
  #astroid-backtotop:hover {
    background-color: #0056b3;
  }
  
  /* Show the button when scrolling */
  body.scroll-active #astroid-backtotop {
    display: inline;
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
            <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;"> Last access : <?php echo date('d F Y')?> &nbsp; 
            <div class="btn-group nav-link">
              <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">

            <span class="ml-3"><?php echo $Name?></span>
            <span class="fa fa-caret-down">
            <span class="sr-only">Toggle Dropdown</span>

          </button>
          <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="profile.php"><span class="fa fa-user"></span> MY ACCOUNT</a>
            <hr style="margin-top: 5px; margin-bottom: 5px;">
            <a class="dropdown-item" href="new-password.php"><span class="fa fa-gear"></span> SETTINGS</a>
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
                        <h3 style="color:white;"><?php echo $Name?></h3>
                    </li>
                    </li>   
                    <li>
                    <a class="active-menu" href="dashboard.php"><i class="fa fa-dashboard fa-xl" style="font-size:24px;color:rgb(255, 255, 255)"></i>  DASHBOARD </a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="fa fa-user fa-xl" style="font-size:24px;color:rgb(255, 255, 255)"></i> PROFILE </a>
                        </li>

                        <li>
                            <a href="create-ticket.php"><i class="fa fa-plus fa-xl" style="font-size: 24px; color: rgb(255, 255, 255)"></i> CREATE TICKET </a>
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
            <div class="col-md-12">   <div class="col-md-12">

                    <div class="col-md-12">
                     <h2>DASHBOARD</h2>   
                     <hr> 
                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-2 col-sm-5 col-xs-3">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-blue set-icon">
                                <i class="fa fa-ticket"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $allTickets?></p>
                                <p class="text-muted">ALL TICKET</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-5 col-xs-3">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-black set-icon">
                                <i class="fa fa-reply"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $returnedTickets?></p>
                                <p class="text-muted">RETURNED TICKET</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-5 col-xs-3">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-violet set-icon">
                                <i class="fa fa-bell"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $dueTickets?></p>
                                <p class="text-muted">DUE DATE PASSED</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-5 col-xs-3">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-yellow set-icon">
                                <i class="fa fa-hourglass-half"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $pendingTickets?></p>
                                <p class="text-muted">PENDING TICKET</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-5 col-xs-3">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-red set-icon">
                                <i class="fa fa-check"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $completedTickets?></p>
                                <p class="text-muted">COMPLETE TICKET</p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
// Assuming you have a way to track the user's role
$role = $identity// 'admin' or 'user'
// Pass the role to the iframe URL or JavaScript
?>
                 <iframe class="panel panel-default" src="../FullCalendar-BS3-PHP-MySQL-master/index.php" style="width: 100%; min-height:700px; border-radius: 10px; margin-bottom:8px"></iframe>

                <!-- /. ROW  -->
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <?php require_once ('../footer.php')?>
            </div>
            </div>


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
    
    <script>
        //window.history.pushState({}, document.title, "/");
    </script>
    
    <script>
        // Smooth scroll to top
document.getElementById('astroid-backtotop').addEventListener('click', function(event) {
  event.preventDefault(); // Prevent default anchor behavior
  window.scrollTo({
      top: 0,
      behavior: 'smooth' // Smooth scrolling
  });
});

// Show button when scrolled down
window.addEventListener('scroll', function() {
  if (window.scrollY > 100) {
      document.body.classList.add('scroll-active');
  } else {
      document.body.classList.remove('scroll-active');
  }
});
    </script>
</body>

</html>
