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
    
    
    <link href="assets/js/DataTables/datatables.min.css" rel="stylesheet">
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
        <!-- NAV SIDE  -->
         <?php include 'nav.php'; ?> 

        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
              
                    <div class="col-md-9">
                     <h2>Response Templates</h2>   
                     
                    </div>
				

        <div class="card-tools col-md-3">
                        <a href="action\new-response-template.php" class="btn btn-flat btn-primary" style="float: right; margin-top:15px;">
                            <span class="fas fa-plus"></span> Create New
                        </a>
        </div>
                 <!-- /. ROW  -->
         
                 <div class="col-md-12">  <hr>
					<div class="panel panel-default">
	<div class="panel-heading">
		List of Responses
		
	</div>
	<div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
											<th>Action</th>
                                        </tr>
                                
									</thead>
				<tbody>
											
<?php

$pdoQuery = "SELECT * FROM templates";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoExec = $pdoResult->execute();

while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)){
    extract($row);
?>										
<tr class="odd">
	<td class=""><?php echo htmlspecialchars($template_name); ?></td>
	<td align="center" class="py-1 px-2 align-middle">
	<div class="panel-body-ticket btn-group" >
	<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
	    Action
	<span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item" href="action\edit-response-template.php?id=<?php echo htmlspecialchars($template_id); ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="action\delete-template.php?id=<?php echo htmlspecialchars($template_id); ?>" data-id="11"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
								  </div>
							</td>
						</tr>
        <?php
        }
        ?>
					</tbody>
			</table></div>
		</div>
		</div>
	
</div>
</div><?php require_once('../footer.php') ?>
            </div>
            
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
    <script src="assets/js/dataTables/dataTables.min.js"></script>
    <script>  $(document).ready(function() {
        $('#dataTables-example').DataTable({
            "order": [
                [0, 'desc']],
                "columnDefs": [
                {   
                    "width": "10%", 
                    "targets": [1],  // Target Age column
                 
                }]
            
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