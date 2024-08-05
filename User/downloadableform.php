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
            <a class="dropdown-item" href="logout.php"><span class="fa fa-sign-out"></span> LOG OUT </a>          </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="assets/img/find_user.png" class="user-image img-responsive" />
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
                                </a>
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
                        <a class="active-menu" href="downloadableform.php"><i class="fa fa-download" style="font-size:36px"></i> DOWNLOADABLE FORMS </a>
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
