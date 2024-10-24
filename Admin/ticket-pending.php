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

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

}

if (isset($_GET['failed']) && $_GET['failed'] == "true") {
    // Set your error message here
    $errorMessage = "Cannot proceed with your request. It seems like someone already opened this ticket.";
    $id = isset($_GET['id']) ? $_GET['id'] : ''; // Retrieve the ID if it exists
    echo "<script type='text/javascript'>
        window.onload = function() {
            alert('$errorMessage');
            window.location.href = 'ticket-pending.php?id=$id'; // Correctly include the ID in the URL
        };
    </script>";
}


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*">
      <link href="assets/js/DataTables/datatables.min.css" rel="stylesheet">
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
        .modal-dialog {
            max-width: 80%; /* Adjust the modal width as needed */
        }
        .modal-content {
            overflow: hidden; /* Ensure the content doesn't overflow */
        }
        .modal-body img {
            width: 100%;
            height: auto; /* Maintain aspect ratio */
            max-height: 70vh; /* Adjust the maximum height as needed */
            object-fit: contain; /* Ensure the image is contained within the modal */
        }
        .align-left {
        text-align: left !important; /* Force left alignment */
    }
    </style>
    
</head>
<body>

    <div id="wrapper">
        <!-- NAV SIDE  -->
         <?php include 'nav.php'; ?> 
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
        <div id="page-inner" >
                    <div class="col-md-12">
                     <h2>Pending Tickets</h2>   
                     <hr>   
                       
                    </div>
            
                 <!-- /. ROW  -->
                 
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                       
                        <div class="panel-body">
                            <div class="table-responsive">

<?php
$status = "Pending";

$pdoQuery = "SELECT * FROM tb_tickets WHERE status = :status  && user_type = :user";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->bindParam(':status', $status, PDO::PARAM_STR);
$pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
$pdoExec = $pdoResult->execute();

?>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                    <tr>
                                            <th>Priority</th>
                                            <th style="width:10%">Ticket ID</th>
                                            <th style="width:15%">Date Submitted</th>
                                            <th style="width:8%">Name</th>
                                            <th style="width:8%">Issue(s)</th>
                                            <th style="width:25%">Description</th>
                                            <th style="width:8%">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            <?php
                while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $screenshotBase64 = base64_encode($screenshot);


                    $createdDate = new DateTime($row['created_date']);
                    $now = new DateTime();
                    $interval = $now->diff($createdDate);
                    $hoursElapsed = $interval->h + ($interval->days * 24);
                
                    $priorityIcon = '';
                    if (in_array($row['status'], ['Pending']) && $hoursElapsed >= 48) {
                        $priorityIcon = '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>';
                    }

            ?>
                    <tr class='odd gradeX'>
                    <td><?php echo ($priorityIcon);?></td>
                    <td><?php echo htmlspecialchars($ticket_id); ?></td>  
                    <td><?php echo htmlspecialchars($created_date); ?></td>
                    <td><?php echo htmlspecialchars($full_name); ?></td>
                    <td><?php echo htmlspecialchars($issue); ?></td>
                    <td><?php 
    $max_length = 25;
    if (strlen($description) > $max_length) {
        echo htmlspecialchars(substr($description, 0, $max_length)) . '...';
    } else {
        echo htmlspecialchars($description);
    }
    ?></td>
                    
                    <td>

                        <div class='panel-body-ticket'>
                        <button class="btn btn-primary btn-xs load-details" data-ticket_id="<?php echo $ticket_id; ?>" data-status="<?php echo $status; ?>">
                                                                View Details
                                                            </button>
                        </div>
                        
                        </td>



        <?php
        }
        ?>
                                        

                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div><?php include '../footer.php' ?>
               
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
    <!-- DATA TABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/datatables.min.js"></script>
    <script src="fetch/ticket-modal.js"></script>
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            "order": [
                [1, 'asc']],

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
                    "targets": [0],  // Target Age column
                     "className": "text-center",
                    "visible": true // Hide Age column
                },
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