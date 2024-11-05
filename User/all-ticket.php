<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["user_id"];
    $identity = $_SESSION["user_identity"];

    if ($identity == "Student"){
        $pdoUserQuery = "SELECT * FROM student_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->execute();
    
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $Email_Add = $Data['email_address'];
            $Alt_Email_Add = $Data['alt_email_address'];

            $FirstName = $Data['first_name'];
            $LastName = $Data['last_name'];
            $MiddleName = $Data['middle_name'];
            $MiddleInitial = $Data['middle_initial'];
            $ExtensionName = $Data['ext_name'];

$Name = $Data['first_name'];
// Check if there's a middle name or initial
if (!empty($MiddleInitial)) {
    $Name .= " " . $MiddleInitial . ".";
} elseif (!empty($MiddleName)) {
    $Name .= " " . $MiddleName;
}

// Add last name if it exists
if (!empty($LastName)) {
    $Name .= " " . $LastName;
}

// Add extension name if it exists (e.g., Jr., Sr., III)
if (!empty($ExtensionName)) {
    $Name .= " " . $ExtensionName . ".";
}

            $Campus = $Data['campus'];
            $Department = $Data['department'];
            $Course = $Data['course'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
            $Sex = $Data['sex'];
            $Age = $Data['age'];
            $Bday = $Data['birthday'];
            $UserType = $Data['user_type'];
    
            $nameParts = explode(' ', $Name);
            $firstName = $nameParts[0];
    
            $P_PBase64 = base64_encode($P_P);
            $date = new DateTime($Bday);
            $formattedDate = $date->format('F j, Y'); // This will give "July 22, 1990"
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }
    } elseif ($identity == "Employee") {
        $pdoUserQuery = "SELECT * FROM employee_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->execute();
    
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $Email_Add = $Data['email_address'];
            $Alt_Email_Add = $Data['alt_email_address'];
            
            $FirstName = $Data['first_name'];
            $LastName = $Data['last_name'];
            $MiddleName = $Data['middle_name'];
            $MiddleInitial = $Data['middle_initial'];
            $ExtensionName = $Data['ext_name'];

$Name = $Data['first_name'];
// Check if there's a middle name or initial
if (!empty($MiddleInitial)) {
    $Name .= " " . $MiddleInitial . ".";
} elseif (!empty($MiddleName)) {
    $Name .= " " . $MiddleName;
}

// Add last name if it exists
if (!empty($LastName)) {
    $Name .= " " . $LastName;
}

// Add extension name if it exists (e.g., Jr., Sr., III)
if (!empty($ExtensionName)) {
    $Name .= " " . $ExtensionName . ".";
}

            $Campus = $Data['campus'];
            $Department = $Data['department'];
            $Course = $Data['course'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
            $Sex = $Data['sex'];
            $Age = $Data['age'];
            $Bday = $Data['birthday'];
            $UserType = $Data['user_type'];
    
            $nameParts = explode(' ', $Name);
            $firstName = $nameParts[0];
    
            $P_PBase64 = base64_encode($P_P);
            $date = new DateTime($Bday);
            $formattedDate = $date->format('F j, Y'); // This will give "July 22, 1990"
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*"> 
    
    <link href="assets/js/dataTables/datatables.min.css" rel="stylesheet">

    <!-- BOOTSTRAP STYLES -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!-- FONTAWESOME STYLES-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- CUSTOM STYLES -->
    <link href="assets/css/custom.css" rel="stylesheet">
    <!-- GOOGLE FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <!-- TABLE STYLES -->
    <link href="assets/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<?php include 'loading.php'; ?>
<body>
    <div id="wrapper">
    <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12"> <div class="col-md-12">
                  

                        <h2>ALL TICKET</h2> <hr>
                        <!-- /. ROW -->
                        <div id="content" class="row">
                            <div class="col-md-12">
                                <!-- Advanced Tables -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        LAST ACTIVITY
                                    </div>
                                    <div class="panel-body" style="overflow:hidden;">
                                        <div class="table-responsive" >
                                            <table class="table table-striped table-bordered table-hover" id="dataTables-example" >
                                                <thead>
                                                    <tr class="btn-primary">
                                                        <th>TICKET NUMBER</th>
                                                        <th>DATE</th>
                                                        <th>PROBLEM</th>
                                                        <th>MIS STAFF</th>
                                                        <th>STATUS</th>
                                                        <th>DURATION</th>
                                                        <th>ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
// Database query to select tickets for a specific user
$pdoQuery = "SELECT * FROM tb_tickets WHERE user_number = :usernumber";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->bindParam(':usernumber', $id, PDO::PARAM_INT);
$pdoExec = $pdoResult->execute();

// Loop through each result row
while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
    // Extract variables from the row for convenience
    extract($row);

    // Determine the CSS class based on the ticket status
    $statusClass = ($row['status'] === 'Resolved') ? 'success' :
                  (($row['status'] === 'Pending') ? 'danger' :
                  (
                  (($row['status'] === 'Processing') ? 'warning' : '')));

    ?>
    <tr class="odd gradeX <?php echo $statusClass?>">
        <td class="center"><?php echo htmlspecialchars($ticket_id); ?></td>
        <td class="center"><?php echo htmlspecialchars($created_date); ?></td>
        <td class="center"><?php echo htmlspecialchars($issue); ?></td>
        <td class="center"><?php echo htmlspecialchars($employee); ?></td>
        <td class="center"><?php echo htmlspecialchars($status); ?></td>
        <td><?php echo htmlspecialchars($duration); ?></td>
        <td>
<?php

if ($status == 'Resolved') {
// Display the button that triggers the modal
echo '<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal' . htmlspecialchars($ticket_id) . '">
VIEW TICKET
</button>';

} else {
// Display the link with the button
echo '<a href="ticket-view.php?ticket_id=' . htmlspecialchars($ticket_id) . '">
<button class="btn btn-primary btn-xs">
VIEW TICKET
</button>
</a>';
}
?>

    <div class="modal fade" id="myModal<?php echo $ticket_id; ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <img src="assets/pic/head.png" alt="Technical support for DHVSU students">  
                </div>
                <div class="modal-body" style="background-color: white;"> 
                    <h4 class="modal-title">TICKET STATUS</h4>
                    <div class="letter">
                        <main>
                            <style>
                                p {
                                    line-height: 1.5; 
                                    margin-bottom: 20px; 
                                }
                        
                                h1 {
                                    line-height: 1.2; 
                                    margin-bottom: 10px; 
                                }
                            </style>
                            <?php echo nl2br(htmlspecialchars($resolution)); ?>
                        </main>
                    
                        <div class="modal-footer">
                            <a href="survey.php?id=<?php echo $ticket_id; ?>"><button type="button" class="btn btn-primary">TAKE SURVEY</button></a>
                            <a href="ticket-view.php?ticket_id=<?php echo $ticket_id; ?>"><button class='btn btn-primary'>VIEW TICKET</button></a>                                                                    </div>
                </div>
            </div>
        </div>
    </div>


        </td>
    </tr>
<?php
}
?>

                             </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <!-- /. Advanced Tables -->
                           
                        </div>
                        <!-- /. ROW -->
                    </div> 
                    <!-- /. PAGE INNER -->
                     </div></div></div>
                <!-- /. PAGE WRAPPER -->
            
       </div><?php require_once ('../footer.php')?>
       <!-- /. WRAPPER -->
       </div>
    <!-- SCRIPTS - AT THE BOTTOM TO REDUCE THE LOAD TIME -->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- DATA TABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });
    </script>
 <?php require_once ('../footer.php')?>

    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
