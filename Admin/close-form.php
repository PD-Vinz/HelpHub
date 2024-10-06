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
        .form-controlb {
            width: 100%;
            resize: none;
        }
        .modal-dialog3 {
            max-width: 500px;
        }

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
                     <h2>Close Ticket #<?php echo htmlspecialchars($ticket_id); ?></h2>   
                        <!---<h5>Welcome <?php //echo $Name?> , Love to see you back. </h5>-->
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
                  <div class="row">
                  <div class="col-md-12">
                    <h3>Ticket Details</h3>
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
<div class="col-md-12">
    <hr />
    <form role="form" method="post" action="ticket-resolution.php?id=<?php echo $_GET['id']?>&user=<?php echo $_GET['user']?>&form=close">
        <div class="form-group row">
            <label class="col-md-2 col-form-label">Resolution:</label>
            <div class="col-md-8">
                <select id="fileSelector" class="form-control">
                    <option value="">Select</option>

                </select>
                <br>
                <textarea name="resolution" id="detailsTextarea" class="form-controlb" rows="5" required></textarea>
                <br>
                
                <!--<button type="button" id="loadFileButton" class="btn btn-secondary">Load File</button>-->
                <!--<input type="file" id="fileInput" accept=".txt" class="form-control-file">-->
                <br>
            </div>
        </div>
        <div class="form-group row">

                <div class="modal-footer">	
                <a href="#" data-dismiss="modal" class="btn" onclick="history.back()">Back</a>
                <a data-toggle="modal" href="#myModalTransfer" class="btn btn-primary">Close</a>
                </div>
                
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
// Function to populate the dropdown with data from the database
function populateDropdown() {
    fetch('action/list_data.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(entryList => {
            const dropdown = document.getElementById('fileSelector');
            entryList.forEach(entry => {
                const option = document.createElement('option');
                option.value = entry.template_id;  // Set option value as the entry ID
                option.textContent = entry.template_name;  // Set visible text as the entry name
                dropdown.appendChild(option);
            });
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}

// Call the function to populate the dropdown on page load
window.onload = populateDropdown;

// Add event listener for dropdown selection
document.getElementById('fileSelector').addEventListener('change', function() {
    const selectedId = this.value;

    if (selectedId) {
        // Fetch the content from the database for the selected entry
        fetch(`action/get_data.php?id=${selectedId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                document.getElementById('detailsTextarea').value = data;
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
                document.getElementById('detailsTextarea').value = "Failed to load content. Please try again.";
            });
    } else {
        // Clear the textarea if no entry is selected
        document.getElementById('detailsTextarea').value = '';
    }
});

</script>
<!--                                    
                                    <form role="form">
                                       

                                        <br>
                                            <br>
                                                <br>
                                     
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label>Details:‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                        </div>
                                        <div class="col-md-8">
                                        <textarea class="form-controlb" rows="5"></textarea>
                                            <br><br></div>
                                    </div>
                                    <div class="col-md-9">

                                    </div>
                <div class="col-md-2">
                    <a data-toggle="modal" href="#myModalTransfer" class="btn btn-primary">Close</a>
                        <div class="modal fade" id="myModalTransfer">
                            <div class="modal-dialog3">
                            <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Close Ticket</h4>

                            </div>
                            <div class="container"></div>
                            <div class="modal-body">Confirm Closing ticket</div>
                            <div class="modal-footer">	<a href="#" data-dismiss="modal" class="btn">Cancel</a>
                            <a href="#" data-toggle="modal"  class="btn btn-primary">Confirm</a>
                            
                            </div>
                            </div>
                            </div>
                        </div>
                </div>
                                    </form>
-->   
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




