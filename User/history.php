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
            <a class="dropdown-item" href="http://localhost/sms//classes/Login.php?f=logout"><span class="fa fa-sign-out"></span> LOG OUT </a>
          </div>
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
                                </a>
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
                 <div class="row">
                    <div class="col-md-12">
                        <!-- Advanced Tables -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                 LAST ACTIVITY
                            </div>
                            <div class="panel-body-ticket">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                            <th>ALL TICKET</th>
                                            <th>TICKET NUMBER</th>
                                            <th>PROBLEM</th>
                                            <th>MIS STAFF</th>
                                            <th>STATUS</th>
                                            <th>DURATION</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="odd gradeX">
                                            <td class="center">MARCH 16, 2021</td>                                           
                                            <td class="center">111</td>
                                            <td class="center">DHVSU SMS</td>
                                            <td class="center">LOREM IPSUN</td>
                                            <td class="center">CLOSED</td>
                                            <td >4 HOURS</td>
                                            <td><div class="panel-body-ticket">
                                                
                                  <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal">
                                    VIEW TICKET
                                  </button>
                                  <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">TICKET STATUS</h4>
                </div>
                
                <div class="panel-body-ticket">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
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


                            <tr class="odd gradeX">
                                <td class="center">08:22 AM</td>
                                <td class="center">YOU MADE A TICKET</td>
                                <td class="center">PENDING</td>
                                <td class="center">LOW</td>
                                <td class="center">NONE</td>
                                <td class="center">--</td>
                            </tr>
                            <!-- Additional rows -->
                            <tr class="odd gradeX">
                                <td class="center">08:26 AM</td>
                                <td class="center">TICKET RECEIVED</td>
                                <td class="center">PENDING</td>
                                <td class="center">LOW</td>
                                <td class="center">NONE</td>
                                <td class="center">--</td>
                            </tr>
                            <!-- Additional rows -->
                            <tr class="odd gradeX">
                                <td class="center">09:34 AM</td>
                                <td class="center">YOUR TICKET IS PROCESSING</td>
                                <td class="center">PROCESSING</td>
                                <td class="center">LOW</td>
                                <td class="center">LOREN IPSUN</td>
                                <td class="center">4 HOURS</td>
                            </tr>
                            <!-- Additional rows -->
                                   
                            <tr class="odd gradeX">
                                <td class="center">10:23 AM</td>
                                <td class="center">YOUR TICKET IS PROCESSING</td>
                                <td class="center">PROCESSING</td>
                                <td class="center">LOW</td>
                                <td class="center">LOREN IPSUN</td>
                                <td class="center">4 HOURS</td>
                            </tr>
                            <!-- Additional rows -->  
                                        
                            <tr class="odd gradeX">
                                <td class="center">10:57 AM</td>
                                <td class="center">YOUR TICKET IS PROCESSING</td>
                                <td class="center">CLOSED</td>
                                <td class="center">LOW</td>
                                <td class="center">LOREN IPSUN</td>
                                <td class="center">4 HOURS</td>
                            </tr>
                            <!-- Additional rows -->
                        </table>
                    </div> 
                                            <div class="modal-footer">	<a href="#" data-dismiss="modal" class="btn">Back</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<tr class="odd gradeX">
    <td class="center">MAY 15, 2022</td>
    <td class="center">305</td>
    <td class="center">DHVSU PORTAL</td>
    <td class="center">LOREM IPSUN</td>
    <td class="center">CLOSED</td>
    <td>2 HOURS</td>
    <td>
        <div class="panel-body-ticket">
            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal1">VIEW TICKET</button>
            <div class="modal fade" id="myModal1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">TICKET STATUS</h4>
                        </div>
                        <div class="panel-body-ticket">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example1">
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
                                    <tr class="odd gradeX">
                                        <td class="center">09:20 AM</td>
                                        <td class="center">YOU MADE A TICKET</td>
                                        <td class="center">PENDING</td>
                                        <td class="center">LOW</td>
                                        <td class="center">NONE</td>
                                        <td class="center">--</td>
                                    </tr>
                                    <!-- Additional rows -->
                                    <tr class="odd gradeX">
                                        <td class="center">09:25 AM</td>
                                        <td class="center">TICKET RECEIVED</td>
                                        <td class="center">PENDING</td>
                                        <td class="center">LOW</td>
                                        <td class="center">NONE</td>
                                        <td class="center">--</td>
                                    </tr>
                                    <!-- Additional rows -->
                                    <tr class="odd gradeX">
                                        <td class="center">09:46 AM</td>
                                        <td class="center">YOUR TICKET IS PROCESSING</td>
                                        <td class="center">PROCESSING</td>
                                        <td class="center">LOW</td>
                                        <td class="center">LOREN IPSUN</td>
                                        <td class="center">2 HOURS</td>
                                    </tr>
                                    <!-- Additional rows -->
                                    <tr class="odd gradeX">
                                        <td class="center">10:19 AM</td>
                                        <td class="center">YOUR TICKET IS PROCESSING</td>
                                        <td class="center">PROCESSING</td>
                                        <td class="center">LOW</td>
                                        <td class="center">LOREN IPSUN</td>
                                        <td class="center">2 HOURS</td>
                                    </tr>
                                    <!-- Additional rows -->
                                    <tr class="odd gradeX">
                                        <td class="center">10:43 AM</td>
                                        <td class="center">YOUR TICKET IS PROCESSING</td>
                                        <td class="center">CLOSED</td>
                                        <td class="center">LOW</td>
                                        <td class="center">LOREN IPSUN</td>
                                        <td class="center">2 HOURS</td>
                                    </tr>
                                    <!-- Additional rows -->
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
    </td>
</tr>


                <!-- /. ROW -->
            </div>
            <!-- /. PAGE INNER -->
        </div>
        <!-- /. PAGE WRAPPER -->
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
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });
    </script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
