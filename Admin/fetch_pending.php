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
    </style>
    <?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
include_once("../connection/conn.php");
$pdoConnect = connection();

// Start session if needed
session_start();

// Verify if admin is logged in (if required)
if (!isset($_SESSION["admin_number"])) {
    echo "Session expired. Please log in again.";
    exit();
}

// Fetch ticket data with status 'Pending'
try {
    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Pending'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
echo '  <thead>
                                        <tr>
                                            <th style="width:10%">Ticket ID</th>
                                            <th style="width:15%">Date Submitted</th>
                                            <th>Name</th>
                                            <th>Issue(s)</th>
                                            <th style="width:25%">Descriptions</th>
                                            <th style="width:8%">Details</th>
                                        </tr>
                                    </thead>';
    // Check if there are pending tickets
    if ($pdoResult->rowCount() > 0) {
        // Loop through and output each ticket row
        while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
            $screenshotBase64 = base64_encode($row['screenshot']);
            
            echo "<tr class='odd gradeX'>";
            echo "<td>" . htmlspecialchars($row['ticket_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['issue']) . "</td>";
            
            // Shorten the description if needed
            $description = $row['description'];
            $max_length = 25;
            if (strlen($description) > $max_length) {
                echo "<td>" . htmlspecialchars(substr($description, 0, $max_length)) . '...' . "</td>";
            } else {
                echo "<td>" . htmlspecialchars($description) . "</td>";
            }

            // Details button and modal
            echo "<td>
                <div class='panel-body-ticket'>
                    <button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModal{$row['ticket_id']}'>
                        View Details
                    </button>
                </div>
            </td>";
            echo "</tr>";
            
            // Modal content for each ticket
            echo "<div class='modal fade' id='myModal{$row['ticket_id']}' >
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                            <h4 class='modal-title'>Pending Ticket</h4>
                        </div>
                        <div class='modal-body'>
                            <div class='row'>
                                <div class='col-md-6'>
                                    <h3>Ticket Details</h3>
                                    <form role='form'>
                                        <div class='form-group'>
                                            <label>Ticket ID</label>
                                            <input class='form-control' value='" . htmlspecialchars($row['ticket_id']) . "' disabled/>
                                        </div>
                                        <div class='form-group'>
                                            <label>Issue/Problem</label>
                                            <input class='form-control' value='" . htmlspecialchars($row['issue']) . "' disabled/>
                                        </div>
                                        <div class='form-group'>
                                            <label>Description</label>
                                            <textarea class='form-control' disabled style='height:148px; resize:none; overflow:auto;'>" . htmlspecialchars($row['description']) . "</textarea>
                                        </div>
                                        <div class='form-group'>
                                            <label>Screenshot</label>
                                            <a href='view_image.php?id=" . htmlspecialchars($row['ticket_id']) . "' target='_blank'>
                                                <img src='data:image/jpeg;base64,{$screenshotBase64}' alt='Screenshot' class='img-fluid'>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                                
                                <div class='col-md-6'>
                                    <h3>User Information</h3>
                                    <form role='form'>
                                        <div class='form-group'>
                                            <label>Full Name</label>
                                            <input class='form-control' value='" . htmlspecialchars($row['full_name']) . "' disabled/>
                                        </div>
                                        <div class='form-group'>
                                            <label>User ID</label>
                                            <input class='form-control' value='" . htmlspecialchars($row['user_number']) . "' disabled/>
                                        </div>
                                        <div class='form-group'>
                                            <label>Gender</label>
                                            <input class='form-control' value='" . htmlspecialchars($row['sex']) . "' disabled/>
                                        </div>
                                        <div class='form-group'>
                                            <label>Age</label>
                                            <input class='form-control' value='" . htmlspecialchars($row['age']) . "' disabled/>
                                        </div>
                                        <div class='form-group'>
                                            <label>College</label>
                                            <input class='form-control' value='" . htmlspecialchars($row['department']) . "' disabled/>
                                        </div>
                                        <div class='form-group'>
                                            <label>Course</label>
                                            <input class='form-control' value='" . htmlspecialchars($row['course']) . "' disabled/>
                                        </div>
                                        <div class='form-group'>
                                            <label>Year & Section</label>
                                            <input class='form-control' value='" . htmlspecialchars($row['year_section']) . "' disabled/>
                                        </div>
                                        <div class='form-group'>
                                            <label>Campus</label>
                                            <input class='form-control' value='" . htmlspecialchars($row['campus']) . "' disabled/>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <a href='#' data-dismiss='modal' class='btn'>Back</a>
                            <a data-toggle='modal' href='#myModalTransfer{$row['ticket_id']}' class='btn btn-primary'>Transfer</a>
                            <a data-toggle='modal' href='#myModalReturn{$row['ticket_id']}' class='btn btn-primary'>Return</a>
                            <a data-toggle='modal' href='#myModalClose{$row['ticket_id']}' class='btn btn-primary'>Resolve</a>
                        </div>
                    </div>
                </div>
            </div>";
        }
    } else {
        echo "<tr><td colspan='6'>No pending tickets found.</td></tr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- DATA TABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    