<?php
include_once("../../connection/conn.php");
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
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
     <!-- MORRIS CHART STYLES-->
    <link href="../assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="../assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

   <style>
    /* Hide the "No file chosen" text*/ 
input[type="file"]::file-selector-button {
    visibility: hidden;
}   
/* Customize the button appearance (optional) */
.custom-file {
    position: relative;
    overflow: hidden;
    display: inline-block;
}

.custom-file input[type="file"] {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
}

.custom-file::before {
    content: 'Choose file';
    display: inline-block;
    /*background-color: #007bff;
    color: white;*/
    padding: 5px 10px;
    border: 1px solid #C70039 ;
    border-radius: 5px;
    cursor: pointer;
}

.custom-file:hover::before {
    background-color: #800000;
    color: white;
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
                <div class="row">
                    <div class="col-md-12">
                     <h2>Create New Mailer Configuration</h2>   

                    </div>
                </div>
                 <!-- /. ROW  -->
                 <hr />
                 <div class="panel panel-default">
	<div class="panel-body">
		<div class="container-fluid col-md-12">
			<div id="msg"></div>
			<form method="post" action="action\mis-employee-insert.php" id="manage-user" enctype="multipart/form-data">
            <div class="container-fluid col-md-6">
                <div class="form-group col-6">
					<label for="purpose">Purpose</label>
                    <select name="purpose" id="purpose" class="custom-select form-control" required>
                        <option value="OTP">OTP</option>
						<option value="Notification">Notification</option>
                        <option value="Security">Security</option>
					</select>
				</div>	
				<div class="form-group col-6">
					<label for="namhoste">Host</label>
					<input type="text" name="host" id="host" class="form-control" required autocomplete="off" placeholder="ex. smtp.sample.com">
				</div>
				<div class="form-group col-6">
					<label for="username">Username</label>
					<input type="text" name="username" id="username" class="form-control" required autocomplete="off">
				</div>
                <div class="form-group col-6">
					<label for="password">Password</label>
					<input type="text" name="password" id="password" class="form-control" required autocomplete="off">
				</div>
                <div class="form-group col-6">
					<label for="smtpsecure">SMTPSecure</label>
                    <select name="smtpsecure" id="smtpsecure" class="custom-select form-control" required>
                        <option value="PHPMailer::ENCRYPTION_STARTTLS">PHPMailer::ENCRYPTION_STARTTLS</option>
						<option value="PHPMailer::ENCRYPTION_SMTPS">PHPMailer::ENCRYPTION_SMTPS</option>
					</select>
				</div>
                <div class="form-group col-6">
					<label for="port">Port</label>
					<input type="number" name="port" id="port" class="form-control" required autocomplete="off" readonly>
				</div>
                <div class="form-group col-6">
					<label for="email">Email Address</label>
					<input type="email" name="email" id="email" class="form-control" required autocomplete="off" placeholder="ex. HelpHub@email.com">
				</div>
                <div class="form-group col-6">
					<label for="name">Name</label>
					<input type="text" name="name" id="name" class="form-control" required autocomplete="off" placeholder="ex. HelpHub">
				</div>
            </div>
            <div class="col-md-6">
				<div class="form-group col-6">
					<label for="" class="control-label">Test Configuration</label>
                    <br>

				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
			<div class="col-md-12">
				<div class="row">
					<button class="btn btn-sm btn-primary mr-2" form="manage-user">Add Configuration</button>
					<a class="btn btn-sm btn-secondary" onclick="history.back()">Cancel</a>
				</div>
			</div>
		</div>
    </form>
</div>
    </div>
    <?php require_once('../../footer.php') ?> 
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="../assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="../assets/js/custom.js"></script>

<script>
    function updateAccountOptions() {
        var smtpsecureDropdown = document.getElementById("smtpsecure");
        var portInput = document.getElementById("port");

        // Get selected encryption type
        var selectedEncryption = smtpsecureDropdown.value;

        // Update the port input based on the selected encryption type
        if (selectedEncryption === "PHPMailer::ENCRYPTION_STARTTLS") {
            portInput.value = '587'; // STARTTLS typically uses port 587
        } else if (selectedEncryption === "PHPMailer::ENCRYPTION_SMTPS") {
            portInput.value = '465'; // SMTPS typically uses port 465
        }
    }

    // Attach the event listener for the dropdown
    document.getElementById("smtpsecure").addEventListener("change", updateAccountOptions);

    // Call the function initially to set the options based on the default selected account
    updateAccountOptions();
</script>



<?php include_once("../extra/warning-modal.html");?>  
</body>
</html>
