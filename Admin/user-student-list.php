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
              
                    <div class="col-md-4">
                     <h2>Student List</h2>
                        
                    </div>
    <div class="card-tools col-md-8">
    <div class="col-md-4">
			<a href="add-user-student.php" class="btn btn-flat btn-primary" style="float: right; margin-top:15px;"><span class="fas fa-plus"></span>  Create New </a>
		</div>
    <div class="col-md-4">
			<a href="action\add-user.php" class="btn btn-flat btn-primary" style="float: right; margin-top:15px;"><span class="fas fa-plus"></span>  Upload CSV file</a>
		</div>
    <div class="col-md-4">
            <a class="btn btn-flat btn-primary" id="openModal" style="float: right; margin-top:15px;"><span class="fas fa-plus"></span>  Update Year & Section </a>
		</div>
    </div>

                 <!-- /. ROW  -->
                 
                <div class="col-md-12"> <hr>   
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Student's Account
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">

<?php
$user = "Student";

$pdoQuery = "SELECT * FROM student_user WHERE user_type = :user ORDER BY user_id ASC";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->bindParam(':user', $user, PDO::PARAM_STR);
$pdoExec = $pdoResult->execute();

?>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>User Id</th>
                                           
                                            <th>Full Name</th>
                                            <th>Campus</th>
                                            <th>Year & Section</th>
                                            <th>Gender</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            <?php
                while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $P_PBase64 = base64_encode($profile_picture);
                    $date = new DateTime($birthday);
                    $formattedDate = $date->format('F j, Y');

                    if ($name === 'Super Admin') {
                        continue;
                    }
            ?>
                    <tr class='odd gradeX'>
                    <td><?php echo htmlspecialchars($user_id); ?></td>
                 
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo htmlspecialchars($campus); ?></td>
                    <td><?php echo htmlspecialchars($year_section); ?></td>
                    <td><?php echo htmlspecialchars($sex); ?></td>    
                    <td align="center" class="py-1 px-2 align-middle">
	<div class="panel-body-ticket btn-group" >
	<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
	    Action
	<span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item" data-toggle='modal' data-target='#myModal<?php echo $user_id; ?>' style="cursor:pointer"><span class="fa fa-edit text-primary"></span> View</a>
				                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="11" onclick="Delete()"><span class="fa fa-ban text-danger"></span> Disable Account</a>
    <script>
        function Delete() {
            alert("The Delete function is currently not usable.");
        }
    </script>
				                  </div>
								  </div>
							</td>

<div class="modal fade" id="myModal<?php echo $user_id; ?>" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">User Information</h4>

            </div>
            <div class="container"></div>
            <div class="modal-body">
                <div class="row">

                                <div class="col-md-3">
                                    <div class="text-center">
                                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="avatar img-circle img-thumbnail" alt="avatar">
                                        <h3><?php echo $name?></h3>
                                        <h5 style="text-transform: uppercase;"><?php echo $user_type?></h5>
                                    </div>
                                </div>
                                <div class="col-md-9 personal-info">
                                    <div> <h3>PERSONAL INFORMATION</h3>
                                    </div>
                                    <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">STUDENT ID</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $user_id?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">DHVSU EMAIL</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $email_address?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">GENDER</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $sex?>" disabled>
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
                                                <input class="form-control" type="text" value="<?php echo $age?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">CAMPUS </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $campus?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">DEPARTMENT </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $department?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">COURSE </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $course?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">YEAR AND SECTION </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $year_section?>" disabled>
                                            </div>
                                        </div>
                                </div>
                                
                            
                </div>
            </div>
                                <div class="modal-footer">
                                <a href="edit-user-student.php?id=<?php echo $user_id; ?>" class="btn btn-primary">Edit Account Information</a>
                                </div>
        </div>
    </div>
</div>
        
                          </div>
        <?php
        }
        ?>
                                        

                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    </div>
                    </div><?php require_once('../footer.php') ?> 
                    </div>
                    
                    <!--End Advanced Tables -->
                            
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
    
    
   
</body>

<!-- Updated Modal HTML -->
<div id="yearSectionModal" class="modal-custom">
    <div class="modal-content-custom">
        <span class="close-custom">&times;</span>
        <h2>Choose what to do.</h2>
        <p>Reminder: This will be applied to all of the Student's account.</p>
        <button class="modalButton-custom" id="addYearButton">Add 1</button>
        <button class="modalButton-custom" id="deductYearButton">Deduct 1</button>
    </div>
</div>

<style>
/* Updated Modal Styles */
.modal-custom {
    display: none;
    position: fixed;
    z-index: 2; /* Set higher than the other modal if necessary */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content-custom {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    text-align: center;
}

.close-custom {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-custom:hover,
.close-custom:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.modalButton-custom {
    margin: 10px;
    padding: 10px 20px;
}
</style>

<script>
// Get modal element
const yearSectionModal = document.getElementById("yearSectionModal");

// Get open modal button
const openModalButtonCustom = document.getElementById("openModal");

// Get close button for this modal
const closeButtonCustom = document.getElementsByClassName("close-custom")[0];

// Get buttons
const addYearButton = document.getElementById("addYearButton");
const deductYearButton = document.getElementById("deductYearButton");

// Listen for open click
openModalButtonCustom.onclick = function() {
    yearSectionModal.style.display = "block";
}

// Listen for close click
closeButtonCustom.onclick = function() {
    yearSectionModal.style.display = "none";
}

// Function to handle AJAX request
function makeCustomAjaxCall(url) {
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json(); // Assuming the response is JSON
        })
        .then(data => {
            console.log(data); // Handle the data received from the server
            alert("AJAX call was successful!");
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}

// Listen for button clicks
addYearButton.onclick = function() {
    makeCustomAjaxCall("action/add-year-student.php"); // Change to your desired URL
}

deductYearButton.onclick = function() {
    makeCustomAjaxCall("action/minus-year-student.php"); // Change to your desired URL
}

// Listen for outside click
window.onclick = function(event) {
    if (event.target === yearSectionModal) {
        yearSectionModal.style.display = "none";
    }
}
</script>

</html>

