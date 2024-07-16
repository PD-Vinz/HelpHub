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
                        <h2>SURVEY FORM</h2>
                    </div>
                </div>

                <div class="container-center">
                <div class="modal-header">
                    <img src="assets/pic/head.png" alt="Technical support for DHVSU students">  
                <div class="container-survey">
                    <h1>Thank you for choosing our services. We highly value your feedback as it helps us improve and better serve you in the future. Please take a moment to share your thoughts with us.</h1>
                    <form action="#" method="post">
                        <div class="question">
                            <p><strong>PLEASE ANSWER THE FOLLOWING QUESTION:</strong></p>
                            <label>Overall Satisfaction</label>
                            <ul>
                                <li><input type="radio" name="overall_satisfaction" value="very_satisfied"> Very Satisfied</li>
                                <li><input type="radio" name="overall_satisfaction" value="satisfied"> Satisfied</li>
                                <li><input type="radio" name="overall_satisfaction" value="neutral"> Neutral</li>
                                <li><input type="radio" name="overall_satisfaction" value="disappointed"> Disappointed</li>
                                <li><input type="radio" name="overall_satisfaction" value="very_disappointed"> Very Disappointed</li>
                            </ul>
                        </div>
                        <div class="question">
                            <label>How would you rate our service?</label>
                            <ul>
                                <li><input type="radio" name="service_rating" value="very_satisfied"> Very Satisfied</li>
                                <li><input type="radio" name="service_rating" value="satisfied"> Satisfied</li>
                                <li><input type="radio" name="service_rating" value="neutral"> Neutral</li>
                                <li><input type="radio" name="service_rating" value="disappointed"> Disappointed</li>
                                <li><input type="radio" name="service_rating" value="very_disappointed"> Very Disappointed</li>
                            </ul>
                        </div>
                        <div class="question">
                            <label>Did our service meet your expectations?</label>
                            <ul>
                                <li><input type="radio" name="service_expectations" value="exceeded"> Yes, exceeded expectations</li>
                                <li><input type="radio" name="service_expectations" value="met"> Yes, met expectations</li>
                                <li><input type="radio" name="service_expectations" value="fell_short"> No, fell short of expectations</li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <a href="#"><button type="button" class="btn btn-primary">BACK</button></a>
                            <a href="survey-extension.php"><button type="button" class="btn btn-primary">NEXT</button></a>
                        </div>
                    </form>
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
    <!-- MORRIS CHART SCRIPTS -->
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>

</html>
