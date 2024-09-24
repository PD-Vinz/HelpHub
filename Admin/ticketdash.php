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

        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Completed' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();
        $completedTickets = $pdoResult->rowCount();

        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Due' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();
        $dueTickets = $pdoResult->rowCount();

        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Transferred' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();
        $transferredTickets = $pdoResult->rowCount();
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


                <div class="col-md-2 col-sm-6 col-xs-6">
                    <div class="panel panel-back noti-box">
                        <span class="icon-box bg-color-yellow set-icon">
                            <i class="fa fa-hourglass-half fa-xs" aria-hidden="true"></i>
                        </span>
                        <div class="text-box">
                            <p class="main-text"><?php echo $pendingTickets ?> Pending</p>
                            <!-- <p class="text-muted">Tickets</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-6">
                    <div class="panel panel-back noti-box">
                        <span class="icon-box bg-color-green set-icon">
                            <i class="fa fa-envelope-open fa-xs" aria-hidden="true"></i>
                        </span>
                        <div class="text-box">
                            <p class="main-text"><?php echo $openedTickets ?> Processing </p>
                            <!-- <p class="text-muted">Tickets</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-6">
                    <div class="panel panel-back noti-box">
                        <span class="icon-box bg-color-brown set-icon">
                            <i class="fa fa-check fa-xs" aria-hidden="true"></i>
                        </span>
                        <div class="text-box">
                            <p class="main-text"><?php echo $completedTickets ?> Closed</p>
                            <!-- <p class="text-muted">Tickets</p> -->
                        </div>
                    </div>
                </div>


                <div class="col-md-2 col-sm-6 col-xs-6">
                    <div class="panel panel-back noti-box">
                        <span class="icon-box bg-color-black set-icon">
                            <i class="fa fa-reply fa-xs" aria-hidden="true"></i>
                        </span>
                        <div class="text-box">
                            <p class="main-text"><?php echo $returnedTickets ?> Returned</p>
                            <!-- <p class="text-muted">Tickets</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-6">
                    <div class="panel panel-back noti-box">
                        <span class="icon-box bg-color-blue set-icon">
                            <i class="fa fa-upload fa-xs" aria-hidden="true"></i>
                        </span>
                        <div class="text-box">
                            <p class="main-text"><?php echo $dueTickets ?> Priority</p>
                            <!-- <p class="text-muted">Tickets</p> -->
                        </div>
                    </div>
                </div>

                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <!-- Advanced Tables -->
                            <div class="panel panel-default">

                                <div class="panel-body-ticket">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
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
                                         
                                                <?php

                                                $pdoQuery = "SELECT * FROM tb_tickets WHERE user_type = :user";
                                                $pdoResult = $pdoConnect->prepare($pdoQuery);
                                                $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
                                                $pdoExec = $pdoResult->execute();
                                                while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
                                                    $statusClass = ($row['status'] === 'Resolved') ? 'success' : (($row['status'] === 'Pending') ? 'danger' : (($row['status'] === 'Transferred') ? 'info' : (($row['status'] === 'Processing') ? 'warning' : '')));


                                                    $createdDate = new DateTime($row['created_date']);
                                                    $now = new DateTime();
                                                    $interval = $now->diff($createdDate);
                                                    $hoursElapsed = $interval->h + ($interval->days * 24);

                                                    $priorityIcon = '';
                                                    if (in_array($row['status'], ['Pending', 'Processing']) && $hoursElapsed >= 48) {
                                                        $priorityIcon = '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>';
                                                    }

                                                    extract($row);
                                                    ?>

                                                    <tr class='odd gradeX <?php echo $statusClass?>'>
                                                    <td><?php echo $priorityIcon; ?></td>
                                                    <td><?php echo htmlspecialchars($ticket_id); ?></td>
                                                    <td><?php echo htmlspecialchars($status); ?></td>
                                                    <td><?php echo htmlspecialchars($employee); ?></td>
                                                    <td><?php echo htmlspecialchars($created_date); ?></td>
                                                    <td><?php echo htmlspecialchars($full_name); ?></td>
                                                    <td><?php echo htmlspecialchars($issue); ?></td>


                                                                      
    <?php if ( $status === 'Pending'): ?>
        <td>

            <div class='panel-body-ticket'>
                <button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#PendingModal<?php echo $ticket_id; ?>'>
                    View Pending
                </button>
            </div>
            
        </td>
<?php elseif ( $status === 'Processing'): ?>
        <td>

            <div class='panel-body-ticket'>
                <button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#ProcessingModal<?php echo $ticket_id; ?>'>
                    View Processing
                </button>
            </div>
            
        </td>
<?php elseif ( $status === 'Returned'): ?>
        <td>

            <div class='panel-body-ticket'>
                <button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#ReturnModal<?php echo $ticket_id; ?>'>
                    View Returned
                </button>
            </div>
            
        </td>   
<?php elseif ( $status === 'Resolved'): ?>
        <td>

            <div class='panel-body-ticket'>
                <button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#ResolvedModal<?php echo $ticket_id; ?>'>
                    View Resolved
                </button>
            </div>
            
        </td>              
<?php endif; ?>

        </tr>
                      <!-- Pending Ticket -->    
<div class="modal fade" id="PendingModal<?php echo $ticket_id; ?>" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">Pending Ticket</h4>

            </div>
            <div class="container"></div>
            <div class="modal-body">
                                          <div class="row">
                                <div class="col-md-6">
                                    <h3>User Information</h3>
                                    <form role="form">
                                       
                                      
                                        <div class="form-group">
                                            <label>Full Name‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($full_name); ?>" disabled/>
                                            <br><br>
                                        </div>
                                      
                                        <div class="form-group">
                                            <label>User ID‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($user_number); ?>" disabled/>
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>College‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($department); ?>" disabled/>
                                         <br><br>
                                        </div>
                                        <?php if ( $ticket_user === 'Student'): ?>
                                        <div class="form-group">
                                            <label>Course‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            
                                            <input class="form-control" value="<?php echo htmlspecialchars($course); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Year & Section‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($year_section); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label>Campus ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php  echo htmlspecialchars($campus) ?>" disabled/>
                                            <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>Gender ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($sex) ?>" disabled/>
                                            <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>Age ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($age) ?>" disabled/>
                                            <br><br>
                                        </div>
                                    </form>      
                                </div>
                                
                                <div class="col-md-6">
                                    <h3>Ticket Details</h3>
                                    
                                    <form role="form">
                                    <div class="form-group">
                                            <label>Ticket ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($ticket_id); ?>" disabled/>
                                            <br><br>
                                        </div>
                                    <div class="form-group">
                                            <label>Issue/Problem  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($issue); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Description ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" disabled style="height:148px; resize:none; overflow:auto;"><?php echo htmlspecialchars($description); ?></textarea>
                                            <!--<input class="form-control" value="<?php // echo htmlspecialchars($description); ?>" disabled style=""/> -->
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Screenshot ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <a href="view_image.php?id=<?php echo htmlspecialchars($ticket_id); ?>" target="_blank">
                                                <img src="data:image/jpeg;base64,<?php echo $screenshotBase64; ?>" alt="Screenshot" class="img-fluid">
                                            </a>


                                            <br><br>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">	
                                <a href="#" data-dismiss="modal" class="btn">Back</a>
                                <a data-toggle="modal" href="#myModal4<?php echo $ticket_id; ?>" class="btn btn-primary">Open Ticket</a>

            </div>
        </div>
        
    </div>
</div>
</div>
<div class="modal fade" id="myModal4<?php echo $ticket_id; ?>">
    <div class="modal-dialog3">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">Open Ticket</h4>

            </div>
            <div class="container"></div>
            <div class="modal-body">Confirm opening ticket</div>
            <div class="modal-footer">	
                <a href="#" data-dismiss="modal" class="btn">Cancel</a>
	            <a href="ticket-pending-update.php?id=<?php echo $ticket_id; ?>" class="btn btn-primary">Confirm</a>
            </div>
        </div>
    </div>
</div>

<!--Processing Ticket -->
<div class="modal fade" id="ProcessingModal<?php echo $ticket_id; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">Opened Ticket</h4>

            </div>
            <div class="container"></div>
            <div class="modal-body">
                                          <div class="row">
                                <div class="col-md-6">
                                    <h3>User Information</h3>
                                    <form role="form">
                                        
                                      
                                        <div class="form-group">
                                            <label>Full Name‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($full_name); ?>" disabled/>
                                            <br><br>
                                        </div>
                                      
                                        <div class="form-group">
                                            <label>Student ID‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($user_number); ?>" disabled/>
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>College‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($department); ?>" disabled/>
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Course‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($course); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Year & Section ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($year_section); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Campus ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php  echo htmlspecialchars($campus) ?>" disabled/>
                                            <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>Gender ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($sex) ?>" disabled/>
                                            <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>Age ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($age) ?>" disabled/>
                                            <br><br>
                                        </div>
                                    </form>      
                                </div>
                                
                                <div class="col-md-6">
                                    <h3>Ticket Details</h3>
                                    <form role="form">
                                        <div class="form-group">
                                            <label>Ticket ID‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($ticket_id); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Issue/Problem  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($issue); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Description ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" disabled style="height:148px; resize:none; overflow:auto;"><?php echo htmlspecialchars($description); ?></textarea>
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Screenshot ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <a href="view_image.php?id=<?php echo htmlspecialchars($ticket_id); ?>" target="_blank">
                                                <img src="data:image/jpeg;base64,<?php echo $screenshotBase64; ?>" alt="Screenshot" class="img-fluid">
                                            </a>
                                            <br><br>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">	<a href="#" data-dismiss="modal" class="btn">Back</a>
                            <?php if ($employee === $Name): ?>
                            <a data-toggle="modal" href="#myModalTransfer<?php echo $ticket_id; ?>" class="btn btn-primary">Transfer</a>
                            <a data-toggle="modal" href="#myModalReturn<?php echo $ticket_id; ?>" class="btn btn-primary">Return</a>
                            <a data-toggle="modal" href="#myModalClose<?php echo $ticket_id; ?>" class="btn btn-primary">Close</a>
                            <?php endif; ?>
            </div>
        </div>
        
    </div>
</div>
                              </div>
<!--confirmation modals -->
<div class="modal fade" id="myModalTransfer<?php echo $ticket_id; ?>">
                            <div class="modal-dialog3">
                            <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Transfer Ticket</h4>

                            </div>
                            <div class="container"></div>
                            <div class="modal-body">Confirm transfering ticket</div>
                            <div class="modal-footer">	<a href="#" data-dismiss="modal" class="btn">Cancel</a>
                            <a href="../Admin/transfer-form.php?id=<?php echo $ticket_id; ?>&user=<?php echo $ticket_user; ?>" data-toggle="modal"  class="btn btn-primary">Confirm</a>
                            
                            </div>
                            </div>
                            </div>
                            </div>
<div class="modal fade" id="myModalReturn<?php echo $ticket_id; ?>">
                            <div class="modal-dialog3">
                            <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Return Ticket</h4>

                            </div>
                            <div class="container"></div>
                            <div class="modal-body">Confirm returning ticket</div>
                            <div class="modal-footer">	<a href="#" data-dismiss="modal" class="btn">Cancel</a>
                            <a href="../Admin/return-form.php?id=<?php echo $ticket_id; ?>&user=<?php echo $ticket_user; ?>" data-toggle="modal"  class="btn btn-primary">Confirm</a>

                            </div>
                            </div>
                            </div>
                            </div>
<div class="modal fade" id="myModalClose<?php echo $ticket_id; ?>">
                            <div class="modal-dialog3">
                            <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Close Ticket</h4>

                            </div>
                            <div class="container"></div>
                            <div class="modal-body">Confirm closing ticket</div>
                            <div class="modal-footer">	<a href="#" data-dismiss="modal" class="btn">Cancel</a>
                            <a href="../Admin/close-form.php?id=<?php echo $ticket_id; ?>&user=<?php echo $ticket_user; ?>" data-toggle="modal"  class="btn btn-primary">Confirm</a>

                            </div>
                            </div>
                            </div>
                            </div>

<!--Return Ticket -->
<div class="modal fade" id="ReturnModal<?php echo $ticket_id; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">Pending Ticket</h4>

            </div>
            <div class="container"></div>
            <div class="modal-body">
                                          <div class="row">
                                <div class="col-md-6">
                                    <h3>User Information</h3>
                                    <form role="form">
                                       
                                      
                                        <div class="form-group">
                                            <label>Full Name‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($full_name); ?>" disabled/>
                                            <br><br>
                                        </div>
                                      
                                        <div class="form-group">
                                            <label>User ID‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($user_number); ?>" disabled/>
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>College‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($department); ?>" disabled/>
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Course‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            
                                            <input class="form-control" value="<?php echo htmlspecialchars($course); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Year‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($year_section); ?>" disabled/>
                                            <br><br>
                                            
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Campus ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php  echo htmlspecialchars($campus) ?>" disabled/>
                                            <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>Gender ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($sex) ?>" disabled/>
                                            <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>Age ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($age) ?>" disabled/>
                                            <br><br>
                                        </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h3>Ticket Details</h3>
                                    
                                    <form role="form">
                                    <div class="form-group">
                                            <label>Ticket ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($ticket_id); ?>" disabled/>
                                            <br><br>
                                        </div>
                                    <div class="form-group">
                                            <label>Issue/Problem  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($issue); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Description ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" disabled style="height:148px; resize:none; overflow:auto;"><?php echo htmlspecialchars($description); ?></textarea>
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Screenshot ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <a href="view_image.php?id=<?php echo htmlspecialchars($ticket_id); ?>" target="_blank">
                                                <img src="data:image/jpeg;base64,<?php echo $screenshotBase64; ?>" alt="Screenshot" class="img-fluid">
                                            </a>
                                            <br><br>
                                        </div>
                                    </form>
                                    
                                        </div>
                                </div>
                                <h3>Return Reason</h3>
                                <textarea class="form-control" disabled style="height:148px; resize:none; overflow:auto;"><?php echo htmlspecialchars($resolution); ?></textarea>
                                <br><br>
                            </div>
            </div>
        </div>
        
    </div>

<!--Resolved Ticket -->
<div class="modal fade" id="ResolvedModal<?php echo $ticket_id; ?>">
    <div class="modal-dialog2">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">Closed Ticket</h4>

            </div>
            <div class="container"></div>
            <div class="modal-body">
                                          <div class="row">
                                            
                                <div class="col-md-4">
                                    <h3>User Information</h3>
                                    <form role="form">
                                       
                                      
                                        <div class="form-group">
                                            <label>Full Name‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($full_name); ?>" disabled//>
                                            <br><br>
                                        </div>
                                      
                                        <div class="form-group">
                                            <label>User ID‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($user_number); ?>" disabled/>
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>College‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($department); ?>" disabled/>
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Course‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($course); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Year & Section‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($year_section); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Campus ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php  echo htmlspecialchars($campus) ?>" disabled/>
                                            <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>Gender ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($sex) ?>" disabled/>
                                            <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>Age ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($age) ?>" disabled/>
                                            <br><br>
                                        </div>
                                    </form>      
                                </div>
                                <div class="col-md-4">
                                    <h3>Ticket Details</h3>
                                    
                                    <form role="form">
                                    <div class="form-group">
                                            <label>Ticket ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($ticket_id); ?>" disabled/>
                                            <br><br>
                                        </div>
                                    <div class="form-group">
                                            <label>Issue/Problem  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($issue); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Description ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" disabled style="height:148px; resize:none; overflow:auto;"><?php echo htmlspecialchars($description); ?></textarea>
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Screenshot ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            
                                            <a href="view_image.php?id=<?php echo htmlspecialchars($ticket_id); ?>" target="_blank">
                                                <img  src="data:image/jpeg;base64,<?php echo $screenshotBase64; ?>" alt="Screenshot" class="img-fluid">
                                            </a>    
                                            
                                            <br><br>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <h3>Closing Summary</h3>
                                    <form role="form">
                                       
                                      
                                        <div class="form-group">
                                            <label>Employee Name </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($employee); ?>" disabled/>
                                            <br><br>
                                        </div>
                                      
                                        <div class="form-group">
                                            <label>Opened‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($opened_date); ?>" disabled/>
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Closed‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($finished_date); ?>" disabled/>
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Duration‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            
                                            <input class="form-control" value="<?php echo htmlspecialchars($duration); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Resolution‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" disabled style="height: 378px; resize:none; overflow:auto;"><?php echo htmlspecialchars($resolution); ?></textarea>
                                            <br><br>
                                        </div>
                                        
                                    </form>      
                                </div>
                            </div>
                            
                            
            </div>
        </div>
        
    </div>
</div>                    
                                <!-- form general example -->
                                </tr>
                                <?php }  ?>
                                </tbody>

                                
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

    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- MORRIS CHART SCRIPTS -->
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>

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
    <script>
  $(document).ready(function() {
    var statusOrderIndex = 0;
    var statusOrderValues = ['Pending', 'Processing', 'Returned', 'Resolved', 'Transferred'];

    $.fn.dataTable.ext.type.order['custom-status-pre'] = function(Status) {
      switch (Status) {
        case 'Pending':
          return 1;
        case 'Processing':
          return 2;
        case 'Returned':
          return 3;
        case 'Transferred':
          return 4;
        case 'Resolved':
          return 5;
        default:
          return 6;
      }
    };

    $.fn.dataTable.ext.type.order['custom-priority-status'] = function(data, settings, row) {
      // Get the priority icon HTML (if present)
      var priorityIcon = $(row).find('td:eq(0) i.fa-exclamation-circle').length > 0 ? 1 : 0;

      // Get the status text
      var status = $(row).find('td:eq(2)').text();

      // Use the custom-status-pre function to get the status order
      var statusOrder = $.fn.dataTable.ext.type.order['custom-status-pre'](status);

      // Return a value that determines the sorting order
      return priorityIcon * 10 + statusOrder; // You can adjust this formula to fit your needs
    };

    $('#dataTables-example').DataTable({
      "order": [[0, 'desc'], [2, 'asc']], // Initial sorting order
      "columnDefs": [{
        "targets": 0, // Target the first column (priority icon)
        "type": "custom-priority-status", // Use the custom sort type
      }, {
        "targets": 2, // Target the third column (status)
        "type": "custom-status-pre", // Use the custom sort type
      }, {
        "width": "6%",
        "targets": [0], // Adjust width for priority icon column
        "className": "text-center"
      }, {
        "width": "9%",
        "targets": [2] // Adjust width for status column
      }, {
        "width": "10%",
        "targets": [1], // Adjust width for columns 0 and 1
      }, {
        "width": "10%",
        "targets": [2], // Adjust width for column 5
      }, {
        "width": "17%",
        "targets": [3, 5], // Adjust width for column 2
      }, {
        "width": "13%",
        "targets": [4], // Adjust width for column 3
      }, {
        "width": "15%",
        "targets": [6], // Adjust width for column 2
      }, {
        "width": "5%",
        "targets": [7], // Adjust width for column 2
      }],
      "drawCallback": function(settings) {
        // Rotate the status order index on each draw
        statusOrderIndex = (statusOrderIndex + 1) % statusOrderValues.length;
      }
    });
  });
</script>

    <!-- DATA TABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTables-example').dataTable();
        });
    </script>

    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>

</html>
