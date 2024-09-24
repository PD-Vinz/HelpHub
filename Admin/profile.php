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
        $user_ID = $Data['admin_number'];
        $Email_Add = $Data['email_address'];
        $Name = $Data['f_name'];
        $lname = $Data['l_name'];
        $P_P = $Data['profile_picture'];
        $Sex = $Data['sex'];
        $Age = $Data['age'];
        $Bday = $Data['birthday'];
        $U_T = $Data['user_type'];

    

        $P_PBase64 = base64_encode($P_P);
        $date = new DateTime($Bday);
        $formattedDate = $date->format('F j, Y'); // This will give "July 22, 1990"
    } else {
        // Handle the case where no results are found
        echo "No Admin found with the given student number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DHVSU MIS - HelpHub</title>
  
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
</head>

<body>
    <div id="wrapper">
    <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>PROFILE</h2>

                        <div class="container">
                            <h1 class="text-primary"></h1>
                            <hr>
                            <div class="row">
                                <nav aria-label="breadcrumb" class="main-breadcrumb">
                                    <ol class="breadcrumb">
                                      <li class="breadcruMB"><a href="index.php">HOME</a></li>
                                      <li class="breadcrumb-item active" aria-current="page">PROFILE</li>
                                    </ol>
                                  </nav>
                                <!-- left column -->
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="avatar img-circle img-thumbnail" alt="avatar">
                                        <h3><?php echo $Name,  " ", $lname?></h3>
                                        <h5 style="text-transform: uppercase;"><?php echo $U_T?></h5>
                                    </div>
                                </div>
        
                                <!-- edit form column -->
                                <div class="col-md-9 personal-info">
                                    <div> <h3>PERSONAL INFORMATION</h3>
                                    </div>
                                    <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">USER ID</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $user_ID?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">EMAIL ADDRESS</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $Email_Add?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">GENDER</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $Sex?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">BIRTHDAY</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $formattedDate?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">AGE</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $Age?>" disabled>
                                            </div>
                                        </div>
                                       
                        
                                        <div class="modal-footer">	
                                            <a href="edit-profile.php"><button type="button" class="btn btn-primary">UPDATE INFORMATION</button></a>
                                        </div>
                                        



                                        
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        
                        <hr>
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Employee Report</h2>

                        <div class="container">
                            <h1 class="text-primary"></h1>
                            <hr>
                            <div class="row">
                                
                            <div class="row">                     
                      
               <div class="col-md-4 col-sm-4 col-xs-4">                     
           <div class="panel panel-default">
               <div class="panel-heading">
                   <a href="action/data-report.php?employee=<?php echo $Name,  " ", $lname?>">Total Tickets</a>
               </div>
               <div class="panel-body">
                   <div id="morris-donut-chart"></div>
               </div>
           </div>            
       </div>
       <div class="col-md-4 col-sm-4 col-xs-4">                     
           <div class="panel panel-default">
               <div class="panel-heading">
                    Student Tickets
               </div>
               <div class="panel-body">
                   <div id="morris-donut-chart2"></div>
               </div>
           </div>            
       </div>
       <div class="col-md-4 col-sm-4 col-xs-4">                     
           <div class="panel panel-default">
               <div class="panel-heading">
                    Employee Tickets
               </div>
               <div class="panel-body">
                   <div id="morris-donut-chart3"></div>
               </div>
           </div>            
       </div>
      
      
  </div>
                                        
                                       
                        
                                        <div class="modal-footer">	
                                        <a href="report.php" target="_blank">
                                            <button type="button" class="btn btn-primary">View Full Report</button>
                                        </a>
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
        
                        
                        
                        <!-- /. ROW -->
                    </div>
                </div>
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
    <!-- MORRIS CHART SCRIPTS -->
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>

    <script>
    $(document).ready(function() {
        // Function to create a donut chart
        function createDonutChart(elementId, dataUrl) {
            $.getJSON(dataUrl, function(data) {
                if (data.error) {
                    console.error('Error fetching data for ' + elementId + ':', data.error);
                    $('#' + elementId).html('<p>No data available for this chart.</p>'); // Display a message if no data
                } else {
                    console.log('Data for ' + elementId + ':', data); // Log data for debugging
                    Morris.Donut({
                        element: elementId,
                        data: data,
                        resize: true // Ensure the chart resizes correctly
                    });
                }
            }).fail(function(jqxhr, textStatus, error) {
                console.error('Request Failed for ' + elementId + ': ' + textStatus + ', ' + error);
                $('#' + elementId).html('<p>Failed to load data for this chart.</p>'); // Display a message if the request fails
            });
        }

        var jsVariable = "<?php echo $Name,  " ", $lname?>";

        // Example JavaScript condition to check the value of the PHP variable
        if (jsVariable === "Super Admin") {
            createDonutChart('morris-donut-chart', 'action/data-report.php?chart=all&employee=<?php echo urlencode($Name . " " . $lname); ?>');
            createDonutChart('morris-donut-chart2', 'action/data-report.php?chart=student&employee=<?php echo urlencode($Name . " " . $lname); ?>');
            createDonutChart('morris-donut-chart3', 'action/data-report.php?chart=employee&employee=<?php echo urlencode($Name . " " . $lname); ?>');
        } else {
            createDonutChart('morris-donut-chart', 'action/data-report.php?chart=all&employee=<?php echo urlencode($Name); ?>');
            createDonutChart('morris-donut-chart2', 'action/data-report.php?chart=student&employee=<?php echo urlencode($Name); ?>');
            createDonutChart('morris-donut-chart3', 'action/data-report.php?chart=employee&employee=<?php echo urlencode($Name); ?>');
        }

        // Create charts with dynamic data
        
    });
</script>



 <!-- DATA TABLE SCRIPTS -->
<script> </script>
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
    <script>
      $.widget.bridge('uibutton', $.ui.button)
    </script>
    <?php require_once('../footer.php') ?>
</body>
</html>
