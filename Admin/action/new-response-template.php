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
    <title>DHVSU MIS - HelpHub</title>
  
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

.custom-file {
    top: 30px;
    
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
    content: 'Upload TXT file';
    display: inline-block;
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: 1px solid #007bff;
    border-radius: 5px;
    cursor: pointer;
}

.custom-file:hover::before {
    background-color: #0056b3;
}

.name{
    width: 100%;
    margin-bottom: 10px;

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
                        <div class="col-md-9">
                            <h2>Upload New Response Template</h2>
                            <h5>This page allows the administrator to add new response templates into the system.</h5>             
                        </div>
                        <div class="card-tools col-md-3">
                        <div class="custom-file">
                            <form id="uploadForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                                <input type="file" name="file" class="btn btn-flat btn-primary" id="fileInput" accept=".txt">
                            </form>
                        </div>
                        </div>
                    </div>
                <hr />
                <form method="post" action="new-response-template-upload.php">
                <input name="Name" id="temp-name" class="name" type="text" placeholder="Template Name" autocomplete="off" required>
                <textarea name="Content" id="detailsTextarea" class="form-controlb" rows="5" required></textarea>

                    <div class="modal-footer">	
                                <a href="#" data-dismiss="modal" class="btn" onclick="history.back()">Back</a>
                                <a data-toggle="modal" href="#myModalTransfer" class="btn btn-primary">Add Template</a>
                    </div>
                    <div class="modal fade" id="myModalTransfer">
                    <div class="modal-dialog modal-dialog3">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">Add New Template</h4>
                            </div>
                            <div class="modal-body">
                                Confirm Adding New Template
                            </div>
                            <div class="modal-footer">
                                <button data-dismiss="modal" class="btn">Cancel</button>
                                <button class="btn btn-primary">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                </div>

    <script>
        // File input event listener
        document.getElementById('fileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file && file.type === "text/plain") {
                const reader = new FileReader();

                // Get the file name without the extension
                const fileNameWithoutExtension = file.name.split('.').slice(0, -1).join('.');

                // When the file is successfully read
                reader.onload = function(e) {
                    const content = e.target.result;

                    // Set the content of the textarea to the file content
                    document.getElementById('temp-name').value = fileNameWithoutExtension;
                    document.getElementById('detailsTextarea').value = content;
                };

                // Read the file as text
                reader.readAsText(file);
            } else {
                alert('Please upload a valid .txt file.');
            }
        });
    </script>
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
    <!-- DATA TABLE SCRIPTS -->
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
      <!-- CUSTOM SCRIPTS -->
    <script src="../assets/js/custom.js"></script>
    
    
   
</body>
</html>

