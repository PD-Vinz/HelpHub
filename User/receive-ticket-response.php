<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>USER</title>
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
            font-size: 16px;"> Last access : 30 May 2014 &nbsp; 
            <div class="btn-group nav-link">
              <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="ml-3">LOREM IPSUN</span>
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
                    <i class="text-center">
                        <img src="assets/img/find_user.png" class="user-image img-responsive" />
                    </i>




                    <li>
                        <a href="dashboard.php"><i class="bx bxs-dashboard fa" style="font-size:36px;color:rgb(255, 255, 255)"></i> DASHBOARD </a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="bx bx-user" style="font-size:36px;color:rgb(255, 255, 255)"></i> PROFILE </a>
                    </li>
                    <li>
                            <a class="active-menu" href="create-ticket.php"><i class="fa fa-plus" style="font-size: 36px; color: rgb(255, 255, 255)"></i> CREATE TICKET </a>
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
                        <h2>RECEIVED TICKET</h2>
                    </div>
                </div>

                <div class="container-center">
                    <div class="modal-header">
                        <img src="assets/pic/head.png" alt="Technical support for DHVSU students">  
                    <div class="container-survey">
                    <!-- Make this message be editable by the admin-->
                    <div class="message">
                        <p>Hi, Good Day!</p>
                        <p>We appreciate you addressing your problem with us. Please wait for a response from our MIS Employee.</p>
                        <p>If you encounter any further issues, feel free to submit another ticket. We would be happy to assist you.</p>
                        <p>Thank you & God bless</p>
                        <p>DHVSU-MiS Technical Support<br>HeyJude</p>
                        <p>P.S. To further improve our services, we would like to invite you to answer our short Customer Satisfaction Survey (<a href="survey.php">bit.ly/MISCSSF2024</a>) when you are able. Thank you.</p>
                    </div>
                    <div class="modal-footer">	
                        <a href="dashboard.php"> <button onclick="handleOkay()">Home</button></a>
                    </div>
                </div>
                <!-- /. ROW  -->
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
        <?php require_once ('../footer.php')?>
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
        function handleOkay() {
            alert("Are you sure you want to exit?");
        }
    </script>
</body>

</html>
