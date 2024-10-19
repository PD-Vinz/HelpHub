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

    if (isset($_GET["id"]) && $_GET["id"] == 1) {
        $ticket_user = "Student";
    } elseif (isset($_GET["id"]) && $_GET["id"] == 2) {
        $ticket_user = "Employee";
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

    $pdoCountQuery = "SELECT * FROM tb_tickets";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $allTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Pending'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $pendingTickets = $pdoResult->rowCount();

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
  
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />

    <link href="assets/js/DataTables/datatables.min.css" rel="stylesheet">
     <!-- FONTAWESOME STYLES-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
     <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

   <Style>
        img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 5px;
        }
   </Style>
</head>
<body>
    <div id="wrapper">
        <!-- NAV SIDE  -->
         <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner" >
                
                    <div class="col-md-12">
                     <h2>Closed Tickets</h2>   
                     <hr>
                    </div>
              
             
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                       
                        <div class="panel-body">
                            <div class="table-responsive">
                            <?php
$status = ["Resolved", "Transferred"];

$pdoQuery = "SELECT * FROM tb_tickets WHERE status IN (:status1, :status2) AND user_type = :user ORDER BY `finished_date` DESC";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->bindParam(':status1', $status[0]);
$pdoResult->bindParam(':status2', $status[1]);
$pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
$pdoExec = $pdoResult->execute();
?>


                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Status</th>
                                            <th>Date Completed</th>
                                            <th>Ticket ID</th>
                                            <th>Issue</th>
                                            
                                            <th>Duration</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            <?php   
                while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $screenshotBase64 = base64_encode($screenshot);
            ?>
                    <tr class='odd gradeX'>
                    <td><?php echo htmlspecialchars($employee); ?></td>
                    <td><?php echo htmlspecialchars($status); ?></td>
                    <td><?php echo htmlspecialchars($finished_date); ?></td>
                    <td><?php echo htmlspecialchars($ticket_id); ?></td>
                    <td><?php echo htmlspecialchars($issue); ?></td>
                    
                    <td><?php echo htmlspecialchars($duration); ?></td>
                    <td><div class='panel-body-ticket'>
                                            
                              <button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModal<?php echo $ticket_id; ?>'>
                                View Details
                              </button></td>
                    </tr>
            

                                        <!--<tr class="gradeU">
                                            <td>Bojji</td>
                                            <td>May 1, 2024 - 12:13 pm</td>
                                            <td>Closed</td>
                                            <td>123441</td>
                                            <td>DHVSU Email</td>
                                           <td>lorem ipsum yada yada yadyayayd a dyad  daydyay da d y</td>
                                            <td><div class="panel-body-ticket">
                                            
                              <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal">
                                View Details
                              </button>-->
<div class="modal fade" id="myModal<?php echo $ticket_id; ?>">
    <div class="modal-dialog2">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">Closed Ticket</h4>

            </div>
            <div class="container"></div>
            <div class="modal-body">
                                          <div class="row">
                                 <div class="col-md-4">
                                    <h3>Closing Summary</h3>
                                    <form role="form">
                                       
                                      
                                        <div class="form-group">
                                            <label>Employee Name </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($employee); ?>" disabled/>
                                             
                                        </div>
                                      
                                        <div class="form-group">
                                            <label>Opened‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($opened_date); ?>" disabled/>
                                          
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Closed‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($finished_date); ?>" disabled/>
                                          
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Duration‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            
                                            <input class="form-control" value="<?php echo htmlspecialchars($duration); ?>" disabled/>
                                             
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Resolution‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" disabled style="height: 378px; resize:none; overflow:auto;"><?php echo htmlspecialchars($resolution); ?></textarea>
                                             
                                        </div>
                                        
                                    </form>      
                                </div>    <div class="col-md-4">
                                    <h3>Ticket Details</h3>
                                    
                                    <form role="form">
                                    <div class="form-group">
                                            <label>Ticket ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($ticket_id); ?>" disabled/>
                                             
                                        </div>
                                    <div class="form-group">
                                            <label>Issue/Problem  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($issue); ?>" disabled/>
                                             
                                        </div>
                                        <div class="form-group">
                                            <label>Description ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" disabled style="height:148px; resize:none; overflow:auto;"><?php echo htmlspecialchars($description); ?></textarea>
                                             
                                        </div>
                                        <div class="form-group">
                                            <label>Screenshot ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            
                                            <a href="view_image.php?id=<?php echo htmlspecialchars($ticket_id); ?>" target="_blank">
                                                <img  src="data:image/jpeg;base64,<?php echo $screenshotBase64; ?>" alt="Screenshot" class="img-fluid">
                                            </a>    
                                            
                                             
                                        </div>
                                    </form>
                                </div>        
                                <div class="col-md-4">
                                    <h3>User Information</h3>
                                    <form role="form">
                                       
                                      
                                        <div class="form-group">
                                            <label>Full Name‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($full_name); ?>" disabled//>
                                             
                                        </div>
                                      
                                        <div class="form-group">
                                            <label>User ID‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($user_number); ?>" disabled/>
                                          
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>College‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($department); ?>" disabled/>
                                          
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Course‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($course); ?>" disabled/>
                                             
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Year & Section‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($year_section); ?>" disabled/>
                                             
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Campus ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php  echo htmlspecialchars($campus) ?>" disabled/>
                                             
                                        </div>

                                        <div class="form-group">
                                            <label>Gender ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($sex) ?>" disabled/>
                                             
                                        </div>

                                        <div class="form-group">
                                            <label>Age ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($age) ?>" disabled/>
                                             
                                        </div>
                                    </form>      
                                </div>
                               
                                
                            </div>
                            
                            
            </div>
        </div>
        
    </div>
</div>
                              </div>
<!--                              
<div class="modal fade" id="myModal4">
    <div class="modal-dialog3">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">Open Ticket</h4>

            </div>
            <div class="container"></div>
            <div class="modal-body">Confirm opening ticket</div>
            <div class="modal-footer">	<a href="#" data-dismiss="modal" class="btn">Cancel</a>
	<a href="#" class="btn btn-primary">Confirm</a>

            </div>
        </div>
    </div>
</div>
                          </div></td>
                          
                                        </tr>
                -->
            <?php        
            }
            ?>
                                    </tbody>
                                </table>
                            </div>
                            
                       
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
                
                             
                      

    </div><?php include '../footer.php' ?>
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
<script>
//$(document).ready(function() {
//  Morris.Donut({
//    element: 'morris-donut-chart',
//    data: [
//      { label: "18- below", value: 12 },
//      { label: "18-24", value: 30 },
//      { label: "24+", value: 20 }
//    ]
//  });
//});</script>
 <script>
// $(document).ready(function() {
//  Morris.Donut({
//    element: 'morris-donut-chart2',
//    data: [
//      { label: "Male", value: 12 },
//      { label: "Female", value: 30 },
//      { label: "Others", value: 20 }
//    ]
//  });
//}); 
  </script>

  <script>
//  $(document).ready(function() {
//  Morris.Donut({
//    element: 'morris-donut-chart3',
//    data: [
//      { label: "Main", value: 12 },
//      { label: "Porac", value: 30 },
//      { label: "Others", value: 20 }
//    ]
//  });
//});</script>

<script>
    $(document).ready(function() {
      // Function to create a donut chart
      function createDonutChart(elementId, dataUrl) {
        $.getJSON(dataUrl, function(data) {
          if (data.error) {
            console.error('Error fetching data:', data.error);
          } else {
            Morris.Donut({
              element: elementId,
              data: data
            });
          }
        }).fail(function(jqxhr, textStatus, error) {
          console.error('Request Failed: ' + textStatus + ', ' + error);
        });
      }

      // Create charts with dynamic data
      createDonutChart('morris-donut-chart', 'action/data-resolved.php?chart=age-groups&id=<?php echo $_GET['id']?>');
      createDonutChart('morris-donut-chart2', 'action/data-resolved.php?chart=genders&id=<?php echo $_GET['id']?>');
      createDonutChart('morris-donut-chart3', 'action/data-resolved.php?chart=locations&id=<?php echo $_GET['id']?>');
    });
</script>


 <!-- DATA TABLE SCRIPTS -->
<script> </script>
    <!-- DATA TABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            "order": [
                [0, 'asc']],

            "columnDefs": [
                {   
                    "width": "10%", 
                    "targets": [1],  // Target Age column
                    "visible": true // Hide Age column
                },   
                {   
                    "width": "15%", 
                    "targets": [2],  // Target Age column
                    "visible": true // Hide Age column
                },
                {   
                    "width": "15%", 
                    "targets": [3,4],  // Target Age column
                    "visible": true // Hide Age column
                }, 
                {   
                    "width": "35%", 
                    "targets": [5],  // Target Age column
                    "visible": true // Hide Age column
                    
                },
                {   
                    "width": "5%", 
                    "targets": [6],  // Target Age column
                    "visible": true, // Hide Age column
                    "className": "text-center"                },
            ]
        });

        
    });
</script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>

