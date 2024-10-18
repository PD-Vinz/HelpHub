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

if (isset($_GET['id'])){
    $phpid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    try {
        $Sql = "SELECT * FROM php_mailer_configuration WHERE id = :id";
        $Stmt = $pdoConnect->prepare($Sql);
        $Stmt->bindParam(':id', $phpid);
        $Stmt->execute();

        $Datas = $Stmt->fetch(PDO::FETCH_ASSOC);

    if ($Datas) {
        $purpose = $Datas['email_purpose'];
        $host = $Datas['host'];
        $un = $Datas['username'];
        $pass = $Datas['password'];
        $smtp = $Datas['smtpsecure'];
        $port = $Datas['port'];
        $add = $Datas['address'];
        $name = $Datas['name'];
        $stat = $Datas['status'];

        $nameParts = explode(' ', $Name);
        $firstName = $nameParts[0];
    } else {
        // Handle the case where no results are found
        echo "Bobo";
    }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit;
    }
}


}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $purpose = $_POST['purpose'];
    $host = $_POST['host'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $smtpsecure = $_POST['smtpsecure'];
    $port = $_POST['port'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $status = isset($_POST['active']) ? 'Active' : 'Inactive'; // Use 'active' or 'inactive' based on checkbox status

    try {
        // If active is checked, deactivate all other entries with the same purpose
        if ($status === 'Active') {
            $updateSql = "UPDATE php_mailer_configuration SET status = 'Inactive' WHERE email_purpose = :purpose";
            $updateStmt = $pdoConnect->prepare($updateSql);
            $updateStmt->bindParam(':purpose', $purpose);
            $updateStmt->execute();
        }

        // Prepare an update statement
$sql = "UPDATE php_mailer_configuration 
SET email_purpose = :purpose, 
    host = :host, 
    username = :username, 
    password = :password, 
    smtpsecure = :smtpsecure, 
    port = :port, 
    address = :email, 
    name = :name, 
    status = :status 
WHERE id = :id";

$stmt = $pdoConnect->prepare($sql);

// Bind parameters
$stmt->bindParam(':purpose', $purpose);
$stmt->bindParam(':host', $host);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':smtpsecure', $smtpsecure);
$stmt->bindParam(':port', $port);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':status', $status); // Bind the active status
$stmt->bindParam(':id', $phpid); // Bind the ID for updating the specific record

// Execute the update statement
$stmt->execute();


        // Redirect or display success message
        header("Location: ../mailer-configuration.php"); // Redirect to a success page or similar
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // Display error message
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
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
     <!-- MORRIS CHART STYLES-->
    <link href="../assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="../assets/css/custom.css" rel="stylesheet" />
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
                     <h2>Edit Mailer Configuration</h2>   

                    </div>
                </div>
                 <!-- /. ROW  -->
                 <hr />
                 <div class="panel panel-default">
	<div class="panel-body">
		<div class="container-fluid col-md-12">
			<div id="msg"></div>
			<form method="post" id="manage-user" enctype="multipart/form-data">
            <div class="container-fluid col-md-6">
                <div class="form-group col-6">
					<label for="purpose">Purpose</label>
                    <select name="purpose" id="purpose" class="custom-select form-control" required>
                        <option value="OTP" <?php echo ($purpose == 'OTP') ? 'selected' : ''; ?>>OTP</option>
						<option value="Notification" <?php echo ($purpose == 'Notification') ? 'selected' : ''; ?>>Notification</option>
                        <option value="Security" <?php echo ($purpose == 'Security') ? 'selected' : ''; ?>>Security</option>
					</select>
				</div>	
				<div class="form-group col-6">
					<label for="namhoste">Host</label>
					<input type="text" name="host" id="host" value="<?php echo htmlspecialchars($host); ?>" class="form-control" required autocomplete="off" placeholder="ex. smtp.sample.com">
				</div>
				<div class="form-group col-6">
					<label for="username">Username</label>
					<input type="text" name="username" id="username" value="<?php echo htmlspecialchars($un); ?>" class="form-control" required autocomplete="off">
				</div>
                <div class="form-group col-6">
					<label for="password">Password</label>
					<input type="text" name="password" id="password" value="<?php echo htmlspecialchars($pass); ?>" class="form-control" required autocomplete="off">
				</div>
                <div class="form-group col-6">
					<label for="smtpsecure">SMTPSecure</label>
                    <select name="smtpsecure" id="smtpsecure" class="custom-select form-control" required>
                        <option value="PHPMailer::ENCRYPTION_STARTTLS" <?php echo ($smtp == 'PHPMailer::ENCRYPTION_STARTTLS') ? 'selected' : ''; ?>>PHPMailer::ENCRYPTION_STARTTLS</option>
						<option value="PHPMailer::ENCRYPTION_SMTPS" <?php echo ($smtp == 'PHPMailer::ENCRYPTION_SMTPS') ? 'selected' : ''; ?>>PHPMailer::ENCRYPTION_SMTPS</option>
					</select>
				</div>
                <div class="form-group col-6">
					<label for="port">Port</label>
					<input type="number" name="port" id="port" class="form-control" required autocomplete="off" readonly>
				</div>
                <div class="form-group col-6">
					<label for="email">Email Address</label>
					<input type="email" name="email" id="email"  value="<?php echo htmlspecialchars($add); ?>" class="form-control" required autocomplete="off" placeholder="ex. HelpHub@email.com">
				</div>
                <div class="form-group col-6">
					<label for="name">Name</label>
					<input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" class="form-control" required autocomplete="off" placeholder="ex. HelpHub">
				</div>
                <div class="form-group col-6">
                    <?php if ($stat === 'Inactive'): ?>
                    <input type="checkbox" id="active" name="active" value="Active">
                    <?php elseif ($stat === 'Active'): ?>
                    <input type="checkbox" id="active" name="active" value="Active" <?php echo ($stat == 'Active') ? 'checked' : ''; ?>>
                    <?php endif; ?>
                    <label for="active" style="color: #2b2b2b;">Set Active</label>
				</div>
            </div>
            <div class="col-md-6">
                <div class="form-group col-6">
                    <label for="" class="control-label">Test Configuration</label>
                    <br>
                    <button type="button" class="btn btn-warning" id="test-smtp">Test Configuration</button>
                    <!-- Result message div -->
                    <div id="test-result" style="display: none; margin-top: 20px;"></div>
                </div>
            </div>
		</div>
	</div>
	<div class="modal-footer">
			<div class="col-md-12">
				<div class="row">
                    <button type="button" class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#configModal">Update Configuration</button>
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
<script>
document.getElementById('test-smtp').addEventListener('click', function() {
    // Get form data
    var formData = new FormData(document.getElementById('manage-user'));
    
    // Send the request via AJAX
    fetch('test-smtp.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Show the result message
        var resultDiv = document.getElementById('test-result');
        resultDiv.style.display = 'block';
        resultDiv.innerHTML = data.message;
        
        if (data.success) {
            resultDiv.style.color = 'green'; // Success message color
        } else {
            resultDiv.style.color = 'red'; // Error message color
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>




<?php include_once("../extra/warning-modal.html");?>

<style>
    .modal {
    z-index: 1050; /* Ensure this is higher than other elements */
}
</style>

<!-- Modal -->
<div class="modal fade" id="configModal" tabindex="1" role="dialog" aria-labelledby="configModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="configModalLabel">Add Configuration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to add this configuration?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAdd">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('confirmAdd').addEventListener('click', function() {
        // Submit the form when the user confirms
        document.getElementById('manage-user').submit();
    });
</script>


</body>
</html>
