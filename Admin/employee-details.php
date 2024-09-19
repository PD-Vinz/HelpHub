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
        <!-- NAV SIDE  -->
         <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>User Profile</h2>   
                        <h5>Welcome Jhon Deo , Love to see you back. </h5>
                    </div>
                </div>
                 <!-- /. ROW  -->
                 <hr />
                 <div class="card card-outline card-primary">
	<div class="card-body">
		<div class="container-fluid col-md-6">
			<div id="msg"></div>

<?php
$pdoQuery = $pdoConnect->prepare("SELECT * FROM mis_employees WHERE admin_number = :id");
$pdoQuery->execute(array(':id' => $_GET["id"]));
$pdoResult = $pdoQuery->fetchAll();
$profile_picture = $pdoResult[0]['profile_picture'];
$P_PBase64 = base64_encode($profile_picture);
$pdoConnect = null;
?>
			<form method="post" action="action/mis-employee-update.php" id="manage-user" enctype="multipart/form-data">	
				<!--<input type="hidden" name="userid" value="<?php //echo $pdoResult[0]['admin_number']; ?>">-->
                <div class="form-group col-6">
					<label for="username">User ID</label>
					<input type="text" name="username" id="username" class="form-control" value="<?php echo $pdoResult[0]['admin_number'];  ?>" readonly>
				</div>
				<div class="form-group col-6">
					<label for="name">First Name</label>
					<input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo $pdoResult[0]['f_name'];  ?>" required>
				</div>
				<div class="form-group col-6">
					<label for="name">Last Name</label>
					<input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo $pdoResult[0]['l_name'];  ?>" required>
				</div>
                <div class="form-group col-6">
					<label for="name">Birthday</label>
					<input type="date" name="birthday" id="birthday" class="form-control" value="<?php echo $pdoResult[0]['birthday'];?>">
				</div>
                <div class="form-group col-6">
					<label for="name">Age</label>
					<input type="text" name="age" id="age" class="form-control" value="<?php echo $pdoResult[0]['age'];  ?>" required autocomplete="off">
				</div>
                <div class="form-group col-6">
					<label for="name">Sex</label>
					<select name="sex" id="type" class="custom-select form-control" required>
						<option value="Male" <?php echo ($pdoResult[0]['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
						<option value="Female" <?php echo ($pdoResult[0]['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
					</select>
				</div>
                <div class="form-group col-6">
					<label for="password">Email Address</label>
					<input type="email" name="email" id="email" class="form-control" value="<?php echo $pdoResult[0]['email_address'];  ?>" autocomplete="off" >
				</div>
				<div class="form-group col-6">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off" >
					<small class="text-info"><i>Leave this blank if you dont want to change the password.</i></small>
                 
				</div>
                <div class="form-group col-6">
					<label for="position">Position</label>
					<select name="position" id="position" class="custom-select form-control" required>
						<option value="Director" <?php echo ($pdoResult[0]['user_type'] == 'Director') ? 'selected' : ''; ?>>Director</option>
						<option value="Staff" <?php echo ($pdoResult[0]['user_type'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
					</select>
				</div>
				<div class="form-group col-6">
					<label for="type">User Type</label>
					<select name="type" id="type" class="custom-select form-control" required>
						<option value="Administrator" <?php echo ($pdoResult[0]['user_type'] == 'Administrator') ? 'selected' : ''; ?>>Administrator</option>
						<option value="Staff" <?php echo ($pdoResult[0]['user_type'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
					</select>
				</div>
				<div class="form-group col-6">
					<label for="" class="control-label">Avatar</label>
					<div class="custom-file">
		              <input type="file" class="form-control" id="customFile" name="image" onchange="displayImg(this,$(this))">
		             
		            </div>
				</div>
				<div class="form-group col-6 d-flex justify-content-center">
					<img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" alt="" id="cimg" class="img-fluid img-thumbnail">
				</div>
			
		</div>
	</div>
	<div class="card-footer">
			<div class="col-md-12">
				<div class="row">
					<button class="btn btn-sm btn-primary mr-2" form="manage-user">Save</button>
					<a class="btn btn-sm btn-secondary" href="employee.php">Cancel</a>
				</div>
			</div>
		</div>
    </form>
</div>
<style>
    .img-thumbnail {
    padding: 0.25rem;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.075);
    max-width: 100%;
    height: auto;
}
	.img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
    
.rounded-circle {
    border-radius: 50% !important;
}

.custom-file-input {
    position: relative;
    z-index: 2;
    width: 100%;
    height: calc(2.25rem + 2px);
    margin: 0;
    opacity: 0;
}
.button, input {
    overflow: visible;
}
.input, button, select, optgroup, textarea {
    margin: 0;
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
}
*, *::before, *::after {
    box-sizing: border-box;
}

.input[type="file" i] {
    appearance: none;
    background-color: initial;
    cursor: default;
    align-items: baseline;
    color: inherit;
    text-overflow: ellipsis;
    text-align: start !important;
    padding: initial;
    border: initial;
    white-space: pre;
    overflow: hidden !important;
}

.input {
    
    text-rendering: auto;
    color: fieldtext;
    letter-spacing: normal;
    word-spacing: normal;
    line-height: normal;
    text-transform: none;
    text-indent: 0px;
    text-shadow: none;
    display: inline-block;
    text-align: start;
    appearance: auto;
    -webkit-rtl-ordering: logical;
    cursor: text;
    background-color: field;
    margin: 0em;
    padding: 1px 0px;
    border-width: 2px;
    border-style: inset;
    border-color: light-dark(rgb(118, 118, 118), rgb(133, 133, 133));
    border-image: initial;
    padding-block: 1px;
    padding-inline: 2px;
}
.custom-select {
  display: inline-block;
  width: 100%;
  /*height: calc(2.25rem + 2px);*/
  padding: 0.375rem 1.75rem 0.375rem 0.75rem;
  /*font-size: 1rem;*/
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  vertical-align: middle;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

.custom-control-label::before, .custom-file-label, .custom-select {
  transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.custom-file-label {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    z-index: 1;
    height: calc(2.25rem + 2px);
    padding: 0.375rem 0.75rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    box-shadow: none;
}

.card {
  position: relative;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-direction: column;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 0 solid rgba(0, 0, 0, 0.125);
  border-radius: 0.25rem;
}
</style>
<script>
	$(function(){
		$('.select2').select2({
			width:'resolve'
		})
	})
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	

</script>
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
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
   
</body>
</html>
