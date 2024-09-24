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

$pdoQuery = "SELECT * FROM tb_tickets WHERE ticket_id = :TID";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->bindParam(':TID', $_GET["id"], PDO::PARAM_STR);
$pdoExec = $pdoResult->execute();

while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
extract($row);

$screenshotBase64 = base64_encode($screenshot);

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
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

   <style>
        img {
            max-width: 100%;
            max-height: 300px;
            width: auto;
            height: auto;
            border-radius: 5px;
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
                        <h2>Transfer Ticket</h2>   
                    </div>
                </div>              
                 <!-- /. ROW  -->
                 <hr />
                  <div class="row">
                                <div class="col-md-12">
                                    
<form role="form" method="post" action="ticket-resolution.php?id=<?php echo $_GET['id']?>&user=<?php echo $_GET['user']?>&form=transfer">
                                       
                                      
<div class="form-group">
    <div class="col-md-2">
        <label>Transfer Ticket to:‎ ‎ ‎ ‎ ‎ </label>
    </div>
    <div class="col-md-10">
        <div class="btn-toolbar">
            <div class="btn-group" style="width: 70%;">
                <select name="position" id="position" class="form-control" required onchange="fetchEmployees()">
                    <option value="">Select</option>
                    <!-- Options will be populated from the database -->
                </select>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<div class="form-group">
    <div class="col-md-2">
        <label>Select Employee:‎ ‎ ‎ ‎ ‎ </label>
    </div>
    <div class="col-md-10">
        <div class="btn-toolbar">
            <div class="btn-group" style="width: 70%;">
                <select name="employee" id="employee" class="form-control" required>
                    <option value="">Select</option>
                    <!-- Options will be populated based on position selection -->
                </select>
            </div>
        </div>
    </div>
</div>
<br />
<br />
<br />
<div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>User ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($user_number); ?>" readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label>ISSUE/PROBLEM  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($issue); ?>" readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label>DESCRIPTION ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" style="height:148px; resize:none; overflow:auto;" readonly><?php echo htmlspecialchars($description); ?></textarea>
                                            <!--<input class="form-control" value="<?php // echo htmlspecialchars($description); ?>" disabled style=""/> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>SCREENSHOT ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <a href="view_image.php?id=<?php echo htmlspecialchars($ticket_id); ?>" target="_blank">
                                                <img src="data:image/jpeg;base64,<?php echo $screenshotBase64; ?>" alt="Screenshot" class="img-fluid">
                                            </a>
                                        </div>
                                    </div>
                  </div>
                  <?php } ?>                                 
                                     
                                        
                                        <div class="col-md-9">
                                        </div>

                                        <div class="form-group row">
                                        <hr />
                
                <a href="#" data-dismiss="modal" class="btn" onclick="history.back()">Back</a>
                <a data-toggle="modal" href="#myModalTransfer" class="btn btn-primary">Transfer</a>
                
                
                <div class="modal fade" id="myModalTransfer">
                    <div class="modal-dialog modal-dialog3">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">Close Ticket</h4>
                            </div>
                            <div class="modal-body">
                                Confirm Closing ticket
                            </div>
                            <div class="modal-footer">
                                <button data-dismiss="modal" class="btn">Cancel</button>
                                <button class="btn btn-primary">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
</form> 
<script>
function populateDropdown(fileName, dropdownId) {
    // Add a random query parameter to the file name to prevent caching
    const url = fileName + '?v=' + new Date().getTime();
    
    // Fetch the text file
    fetch(url)
        .then(response => response.text())
        .then(data => {
            // Split the text data by lines
            const options = data.split('\n');

            // Get the dropdown element
            const dropdown = document.getElementById(dropdownId);

            // Iterate over each line and create an option element
            options.forEach(option => {
                if (option.trim() !== '') {  // Ignore empty lines
                    const opt = document.createElement('option');
                    opt.value = option.trim();
                    opt.textContent = option.trim();
                    dropdown.appendChild(opt);
                }
            });
        })
        .catch(error => console.error(`Error fetching the text file (${fileName}):`, error));
}

//populateDropdown('admin-system-configuration/position.txt', 'position');

    </script>
    <script>
        // Function to fetch and populate positions dropdown automatically
function fetchPositions() {
    var positionSelect = document.getElementById('position');
    
    // Clear previous options (just in case)
    positionSelect.innerHTML = '<option value="">Select</option>';

    // AJAX request to fetch distinct positions
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch/getPositions.php', true);
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            var data = JSON.parse(xhr.responseText);
            console.log(data);  // Debug to check the fetched data

            // Populate the dropdown with unique positions
            if (Array.isArray(data) && data.length) {
                data.forEach(function (position) {
                    var option = document.createElement('option');
                    option.value = position.position;
                    option.textContent = position.position;
                    positionSelect.appendChild(option);
                });
            }
        } else {
            console.error('Request failed with status: ' + xhr.status);
        }
    };
    xhr.send();
}

// Automatically fetch positions when the page loads
window.onload = function() {
    fetchPositions();
};

    </script>        
    <script>
function fetchEmployees() {
    var positionId = document.getElementById('position').value;
    var employeeSelect = document.getElementById('employee');
    
    // Clear previous options
    employeeSelect.innerHTML = '<option value="">Select</option>';

    if (positionId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch/getEmployees.php?position_id=' + encodeURIComponent(positionId), true);
        xhr.onload = function () {
    if (xhr.status >= 200 && xhr.status < 300) {
        var data = JSON.parse(xhr.responseText);
        console.log(data);  // Add this line to check the data returned by the server
        if (Array.isArray(data) && data.length) {
            data.forEach(function (employee) {
                var option = document.createElement('option');
                option.value = employee.f_name;
                option.textContent = employee.f_name;
                employeeSelect.appendChild(option);
            });
        }
    } else {
        console.error('Request failed with status: ' + xhr.status);
    }
};
        xhr.send();
    }
}
    </script>    
         
                                </div>
                                
                                
                            </div>
                           
                            
      
                   

<br>
<br>
                 
        </div>
    
           </div>   
                 <!-- /. ROW  -->
                         
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
     <!-- MORRIS CHART SCRIPTS -->
     
     <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
   
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
   
</body>
</html>




