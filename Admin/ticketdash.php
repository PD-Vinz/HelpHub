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

    if (isset($_GET["id"]) && $_GET["id"] == 1) {
        $ticket_user = "Student";
    } elseif (isset($_GET["id"]) && $_GET["id"] == 2) {
        $ticket_user = "Employee";
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

        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Pending' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();

        $pendingTickets = $pdoResult->rowCount();

        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Processing' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();
        $openedTickets = $pdoResult->rowCount();

        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Returned' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();
        $returnedTickets = $pdoResult->rowCount();

        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Resolved' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();
        $completedTickets = $pdoResult->rowCount();

        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE Priority = 'YES' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();
        $priorityTickets = $pdoResult->rowCount();
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
    <title><?php echo $sysName ?></title>
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
            max-width: 80%;
            /* Adjust the modal width as needed */
        }

        .modal-content {
            overflow: hidden;
            /* Ensure the content doesn't overflow */
        }

        .modal-body img {
            width: 100%;
            height: auto;
            /* Maintain aspect ratio */
            max-height: 70vh;
            /* Adjust the maximum height as needed */
            object-fit: contain;
            /* Ensure the image is contained within the modal */
        }
    </style>

</head>

<body>
    <div id="wrapper">
        <!-- NAV SIDE  -->
        <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">

                <div class="col-md-12">
                    <h2>All Tickets</h2>

                    <hr>
                </div>


                <div class="col-md-12 row">

                    <div class="col-md-2 col-sm-6 col-xs-6">

                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-yellow set-icon">
                                <i class="fa fa-hourglass-half fa-xs" aria-hidden="true"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $pendingTickets ?></p>
                                <p class="text-muted pp"> Pending Tickets</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-6">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-green set-icon">
                                <i class="fa fa-envelope-open fa-xs" aria-hidden="true"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $openedTickets ?></p>
                                <p class="text-muted pp"> Processing Tickets</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-6">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-brown set-icon">
                                <i class="fa fa-check fa-xs" aria-hidden="true"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $completedTickets ?></p>
                                <p class="text-muted pp"> Resolved Tickets</p>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-2 col-sm-6 col-xs-6">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-black set-icon">
                                <i class="fa fa-reply fa-xs" aria-hidden="true"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $returnedTickets ?></p>
                                <p class="text-muted pp"> Returned Tickets</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-6">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-blue set-icon">
                                <i class="fa fa-exclamation-circle fa-xs" aria-hidden="true"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo $priorityTickets ?></p>
                                <p class="text-muted pp"> Priority Tickets</p>
                            </div>
                        </div>
                        <!--</a>-->
                    </div>
                    <hr>
                </div>

                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <!-- Advanced Tables -->
                            <div class="panel panel-default">

                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="ticketTable">
                                            <thead>
                                                <tr>
                                                    <th>Priority</th>
                                                    <th>Ticket ID</th>
                                                    <th>Status</th>
                                                    <th>Employee</th>
                                                    <th>Date Submitted</th>
                                                    <th>Name</th>
                                                    <th>Issue</th>
                                                    <th>Details</th>
                                                </tr>
                                            </thead>
                                            <tbody id="ticketTableBody"></tbody>


                                        </table>
                                    </div>

                                </div>
                            </div>
                            <!--End Advanced Tables -->
                        </div>
                    </div>

                    <!-- /. ROW  -->
                    <div class="col-md-12">

                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Age
                                </div>
                                <div class="panel-body">
                                    <div id="morris-donut-chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Gender
                                </div>
                                <div class="panel-body">
                                    <div id="morris-donut-chart2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Campus
                                </div>
                                <div class="panel-body">
                                    <div id="morris-donut-chart3"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /. PAGE INNER  -->
            </div><?php include '../footer.php' ?>
            <!-- /. PAGE WRAPPER  -->
        </div>
        <!-- /. WRAPPER  -->
        <!-- SCRIPTS -AT THE BOTTOM TO REDUCE THE LOAD TIME-->
        <script src="fetch/ticket-updater.js"></script>
        <!-- JQUERY SCRIPTS -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS -->
        <script src="assets/js/bootstrap.min.js"></script>

        <!-- METISMENU SCRIPTS -->
        <script src="assets/js/jquery.metisMenu.js"></script>
        <!-- MORRIS CHART SCRIPTS -->
        <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
        <script src="assets/js/morris/morris.js"></script>
        <script src="fetch/ticket-modal.js"></script>
        <script>
            $(document).ready(function() {
                // Function to create a donut chart
                function createDonutChart(elementId, dataUrl) {
                    $.getJSON(dataUrl, function(data) {
                        if (data.error) {
                            console.error('Error fetching data:', data.error);
                        } else {
                            Morris.Donut({
                                element: elementId,
                                data: data
                            });
                        }
                    }).fail(function(jqxhr, textStatus, error) {
                        console.error('Request Failed: ' + textStatus + ', ' + error);
                    });
                }

                // Create charts with dynamic data
                createDonutChart('morris-donut-chart', 'action/data.php?chart=age-groups&id=<?php echo $_GET['id'] ?>');
                createDonutChart('morris-donut-chart2', 'action/data.php?chart=genders&id=<?php echo $_GET['id'] ?>');
                createDonutChart('morris-donut-chart3', 'action/data.php?chart=locations&id=<?php echo $_GET['id'] ?>');
            });
        </script>

        <!-- DATA TABLE SCRIPTS -->


        <!-- DATA TABLE SCRIPTS -->
        <script src="assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="assets/js/dataTables/datatables.min.js"></script>


        <!-- CUSTOM SCRIPTS -->
        <script src="assets/js/custom.js"></script>
</body>

</html>