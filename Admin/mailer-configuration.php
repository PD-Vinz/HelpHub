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
    .img-avatar{
        width:45px;
        height:45px;
        object-fit:cover;
        object-position:center center;
        border-radius:100%;
        margin: 0;
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
              
                    <div class="col-md-9">
                     <h2>Mailer Configuration</h2>
                     <!--<h5>This page shows all the DHVSU Employee Accounts (except MIS Employees).</h5>   -->           
                    </div>
                    <div class="card-tools col-md-3">
			<a href="action\add-mailer.php" class="btn btn-flat btn-primary" style="float: right; margin-top:15px;"><span class="fas fa-plus"></span>  Create New</a>
	
                </div>
                 <!-- /. ROW  -->
            
                <div class="col-md-12"><hr>
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        List of All the Email Address and SMTP configuration for the Email Notifications and OTP.
                        </div>
                        <div class="panel-body-ticket">
                            <div class="table-responsive">

<?php
$pdoQuery = "SELECT * FROM employee_user ORDER BY user_id ASC";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoExec = $pdoResult->execute();

?>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Purpose</th>
                                            <th>Host</th>
                                            <th>Username</th>
                                            <th>Password</th>
                                            <th>Port</th>
                                            <th>Email Address</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
											
<?php

$pdoQuery = "SELECT * FROM php_mailer_configuration";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoExec = $pdoResult->execute();

while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)){
    extract($row);

    $statusClass = ($row['status'] === 'Active') ? 'success' : '';
?>										
<tr class="odd <?php echo $statusClass?>">
	<td class=""><?php echo htmlspecialchars($email_purpose); ?></td>
    <td class=""><?php echo htmlspecialchars($host); ?></td>
    <td class=""><?php echo htmlspecialchars($username); ?></td>
    <td>
    <input type="password" id="password-<?php echo $row['id']; ?>" value="<?php echo htmlspecialchars($password); ?>" readonly style="border:none; background:transparent; width:100px;">
    <button type="button" id="toggleBtn-<?php echo $row['id']; ?>" onclick="togglePassword(<?php echo $row['id']; ?>)" style="border:none; background:none; cursor:pointer;">
        <i class="fas fa-eye" id="eyeIcon-<?php echo $row['id']; ?>"></i>
    </button>
    </td>
    <td class=""><?php echo htmlspecialchars($port); ?></td>
    <td class=""><?php echo htmlspecialchars($address); ?></td>
    <td class=""><?php echo htmlspecialchars($name); ?></td>
    <td class=""><?php echo htmlspecialchars($status); ?></td>
	<td align="center" class="py-1 px-2 align-middle">
	<div class="panel-body-ticket btn-group" >
	<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
	    Action
	<span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item" href="action\edit-response-template.php?id=<?php echo htmlspecialchars($id); ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="action\delete-template.php?id=<?php echo htmlspecialchars($id); ?>" data-id="11"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
								  </div>
							</td>
						</tr>
        <?php
        }
        ?>
					</tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    </div>
                    </div> <?php require_once('../footer.php') ?> 
                    </div>
                   
                    <!--End Advanced Tables -->
                            
                        </div>
                    </div>
                </div>
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
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    <script>
    function togglePassword(id) {
        var passwordField = document.getElementById('password-' + id);
        var eyeIcon = document.getElementById('eyeIcon-' + id);

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

    
<?php include_once("extra/warning-modal.html");?>    

</body>
</html>

