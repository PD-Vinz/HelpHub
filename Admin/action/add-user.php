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
    content: 'Upload CSV file';
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



h3{
    margin-top: 5px;
    margin-bottom: 10px;
}

/* Style for the confirm button container */
.ConfirmUpload {
    position: relative;
    right: 0;
    text-align: left; /* Aligns the button to the right */
    max-width: 300px; /* Adjust the width as needed */
}

/* Style for the submit button */
.ConfirmUpload input[type="submit"] {
    padding: 7px 20px;
    font-size: 14px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.ConfirmUpload input[type="submit"]:hover {
    background-color: #0056b3;
}

</style>
</head>
<body>
    <div id="wrapper">
        <!-- NAV SIDE  -->
<nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">MIS Office</a> 
    </div>
<div style="color: white; padding: 15px 50px 5px 50px; float: right;"> Last access : <?php echo date('d F Y')?> &nbsp; 
<div class="btn-group nav-link">
          <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
         
            <span class="ml-3"><?php echo $Name?></span>
            <span class="fa fa-caret-down">
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="../profile.php"><span class="fa fa-user"></span> My Account</a>
            <hr style="margin-top: 5px; margin-bottom: 5px;">
            <a class="dropdown-item" href="../logout.php"><span class="fas fa-sign-out-alt"></span> Logout</a>
          </div>
      </div>
</div>
</nav>   
   <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
        <li class="text-center">
            <img src="../assets/img/find_user.png" class="user-image img-responsive"/>
            </li>
        
            
            <li>
                <a class="active-menu"  href="../index.php"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
            </li>
            <li>
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">
        <i class="fa fa-list fa-3x"></i> Student Tickets <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level ticket-dropdown-menu">
              <!--fix the icons-->
              
              <li>
                  <a href="../ticketdash.php?id=1"> &nbsp;&nbsp;<i class="fa fa-ticket " aria-hidden="true"></i>All Tickets</a>
                  </li>
                  <li>
                      <a href="../ticket-pending.php?id=1">&nbsp;&nbsp;<i class="fa fa-hourglass-half " aria-hidden="true"></i>Pending Tickets</a>
                  </li>
                  <li>
                      <a href="../ticket-opened.php?id=1">&nbsp;&nbsp;<i class="fa fa-envelope-open" aria-hidden="true"></i>Opened Tickets</a>
                  </li>
                  <li>
                    <a href="../ticket-closed.php?id=1">&nbsp;&nbsp;<i class="fa-solid fa-check-to-slot"></i>Closed Tickets</a>
                </li>
                <li>
                  <a href="../ticket-returned.php?id=1">&nbsp;&nbsp;<i class="fa fa-undo" aria-hidden="true"></i>Returned Tickets</a>
                </li>
                
              </ul>
            </li>
            <li>
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">
        <i class="fa fa-list fa-3x"></i> Employeee Tickets <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level ticket-dropdown-menu">
              <!--fix the icons-->
              
              <li>
                  <a href="../ticketdash.php?id=2"> &nbsp;&nbsp;<i class="fa fa-ticket " aria-hidden="true"></i>All Tickets</a>
                  </li>
                  <li>
                      <a href="../ticket-pending.php?id=2">&nbsp;&nbsp;<i class="fa fa-hourglass-half " aria-hidden="true"></i>Pending Tickets</a>
                  </li>
                  <li>
                      <a href="../ticket-opened.php?id=2">&nbsp;&nbsp;<i class="fa fa-envelope-open" aria-hidden="true"></i>Opened Tickets</a>
                  </li>
                  <li>
                    <a href="../ticket-closed.php?id=2">&nbsp;&nbsp;<i class="fa-solid fa-check-to-slot"></i>Closed Tickets</a>
                </li>
                <li>
                  <a href="../ticket-returned.php?id=2">&nbsp;&nbsp;<i class="fa fa-undo" aria-hidden="true"></i>Returned Tickets</a>
                </li>
                
              </ul>
            </li>
            <li>
            <a href="../history-log.php"><i class="fa-regular fa-clock fa-3x"></i> Log History</a>

            </li>

            <?php if (isset($U_T) && $U_T === 'Administrator'): ?>
            <li>
                <a href="../feedback-analysis.php" ><i class="fa-regular fa-comment-dots fa-3x"></i>Feedbacks</a>
            </li>
            <li>
                <a href="../employee.php"><i class="fa-solid fa-user-tie fa-3x"></i> Employees</a>
            </li>
            <li>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">
        <i class="fa-regular fa-user fa-3x"></i> User list <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level ticket-dropdown-menu">
              <!--fix the icons-->
              <li>
                  <a href="../user-student-list.php"> &nbsp;&nbsp;<i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>Student's Accounts</a>
                  </li>
                  <li>
                      <a href="../user-employee-list.php">&nbsp;&nbsp;<i class="fa-solid fa-briefcase" aria-hidden="true"></i>Employee's Account</a>
                  </li>
              </ul>
            </li>
            <li>
                <a href="../settings.php"><i class="fa fa-gear fa-3x"></i>System Settings</a>
            </li>
            <?php endif; ?>
        </ul>
       
    </div>
    
</nav>  

<script>
// Get elements
const dropdown = document.querySelector('.ticket-dropdown-menu');
const dropdownToggle = document.querySelector('.dropdown-toggle');
const sidebar = document.querySelector('.sidebar-collapse'); 

// Function to toggle the dropdown
function handleTicketDropdownToggle(event) {
  event.preventDefault();
  dropdown.classList.toggle('show');
  sessionStorage.setItem(
    "ticketDropdownState",
    dropdown.classList.contains("show") ? "open" : "closed"
  );
}

// Event listener for dropdown toggle
document.addEventListener("DOMContentLoaded", function () {
  if (dropdownToggle) {
    dropdownToggle.addEventListener("click", handleTicketDropdownToggle);
  }
});

// Event listener for clicks on the sidebar (after it has fully loaded)
window.addEventListener("load", function () { // Attach listener on 'load'
  sidebar.addEventListener("click", function (event) {
    const target = event.target;

    // Check if the click was outside the dropdown AND not on the toggle button itself
    // AND not on a link within the dropdown
    if (
      !dropdown.contains(target) &&
      target !== dropdownToggle &&
      target.tagName !== "A"
    ) {
      dropdown.classList.remove("show");
      sessionStorage.setItem("ticketDropdownState", "closed");
    }
  });

  // Restore dropdown state after sidebar event listener is attached
  var dropdownState = sessionStorage.getItem("ticketDropdownState");
  if (dropdownState === "open" && dropdown) {
    dropdown.classList.add("show");
  }
});


</script>


        <!-- /. NAV SIDE  -->
            <div id="page-wrapper" >
                <div id="page-inner">
                    <div class="row">
                        <div class="col-md-9">
                            <h2>Upload New Student Account/s</h2>
                            <h5>This page allows the administrator to add new user accounts into the system.</h5>             
                        </div>
                        <div class="card-tools col-md-3">
                        <div class="custom-file">
                            <form id="uploadForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                                <input type="file" name="file" class="btn btn-flat btn-primary" id="file" accept=".csv" required>
                            </form>
                        </div>
                        </div>
                </div>
                <hr />

<script>
        document.getElementById('file').addEventListener('change', function() {
            document.getElementById('uploadForm').submit();
        });
    </script>

        <?php
            if (isset($_FILES['file'])) {
                $file = $_FILES['file'];
            
                // Check for errors
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    die("Error uploading file.");
                }
            
                // Validate file type
                $fileType = mime_content_type($file['tmp_name']);
                if ($fileType !== 'text/plain' && $fileType !== 'text/csv') {
                    die("Invalid file type. Please upload a CSV file.");
                }
            
                // Move the file to a temporary location
                $filePath = 'uploads/' . basename($file['name']);
                if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                    die("Error moving uploaded file.");
                }
            
                // Open and read the CSV file
                if (($handle = fopen($filePath, "r")) !== FALSE) {
                        ?>
                        <form action='add-user-upload.php' method='post'>
                        <div class="card-tools col-md-2">
                        <h3>Preview Data</h3>
                        </div>
                        <div class="card-tools col-md-2">
                            <div class="ConfirmUpload">
                                <input type='submit' value='Confirm and Upload'>   
                            </div>
                        </div>
                        <table class='table table-striped table-bordered table-hover' id='dataTables-example'>
<?php
                        // Store the CSV data in a hidden field to pass it to the next step
        $csvData = [];

        $row = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            echo "<tr>";
            foreach ($data as $cell) {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
            echo "</tr>";

            // Store each row of the CSV data
            $csvData[] = $data;
            $row++;
        }
        echo "</table>";
        fclose($handle);

        // Serialize the data to send it in a hidden input field
        $serializedData = serialize($csvData);
        echo "<input type='hidden' name='csv_data' value='" . htmlspecialchars($serializedData) . "'>";
        echo "<input type='hidden' name='file_path' value='" . htmlspecialchars($filePath) . "'>";
        echo "</form>";
    } else {
        echo "Error opening file.";
    }
} else {
    echo "No file uploaded.";
}
?>
    


                </div>
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

