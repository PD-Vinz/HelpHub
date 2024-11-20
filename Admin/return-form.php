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
}

$pdoQuery = "SELECT * FROM tb_tickets WHERE ticket_id = :TID";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->bindParam(':TID', $_GET["id"], PDO::PARAM_STR);
$pdoExec = $pdoResult->execute();

$row = $pdoResult->fetch(PDO::FETCH_ASSOC);
if ($row) {
    extract($row);

    if ($status != "Processing"){
        if ($_GET['user'] === "Student"){
            header("Location: ticket-opened.php?id=1");
            exit;
        } elseif ($_GET['user'] === "Employee"){
            header("Location: ticket-opened.php?id=2");
            exit;
        }
    }

    $screenshotBase64 = base64_encode($screenshot);
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?php echo htmlspecialchars($sysName); ?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*">

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
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row col-md-12">
                    <div class="col-md-12">
                        <h2>Return Ticket #<?php echo htmlspecialchars($ticket_id); ?></h2>
                        <hr>
                    </div>
                </div>              
                <!-- /. ROW  -->
               
                <div class="row">
                <div class="col-md-12"> 
                    <div class="col-md-12">
                        <h3>Ticket Details</h3>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>User ID</label>
                                <input class="form-control" value="<?php echo htmlspecialchars($user_number); ?>" readonly/>
                            </div>
                            <div class="form-group">
                                <label>ISSUE/PROBLEM</label>
                                <input class="form-control" value="<?php echo htmlspecialchars($issue); ?>" readonly/>
                            </div>
                            <div class="form-group">
                                <label>DESCRIPTION</label>
                                <textarea class="form-control" style="height:148px; resize:none; overflow:auto;" readonly><?php echo htmlspecialchars($description); ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>SCREENSHOT</label><br>
                                <a href="view_image.php?id=<?php echo htmlspecialchars($ticket_id); ?>" target="_blank">
                                    <img src="data:image/jpeg;base64,<?php echo $screenshotBase64; ?>" alt="Screenshot" class="img-fluid" style="width: 320px; height:auto;">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <hr>
                        <form role="form" method="post" action="ticket-resolution.php?id=<?php echo $_GET['id']?>&user=<?php echo $_GET['user']?>&form=return">
                            
                        <div class="row col-md-12">
                            <div class="form-group">
                               <h3>Return details:</h3>
                                <div class="col-md-6">
                                    <textarea name="resolution" id="detailsTextarea" class="form-controlb" rows="5"   required></textarea>
                                    <br>
                                    <select id="fileSelector" class="form-control">
                                        <option value="">Select template</option>
                                    </select>    
                                    <br>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                            <label>Remarks ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" rows="7" style="resize:none; overflow:auto;"><?php echo htmlspecialchars($remarks); ?></textarea>
                                           
                                        </div>
                                        <div class="form-group">
                                            <label>Notes ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea class="form-control" rows="5" style="resize:none; overflow:auto;"><?php echo htmlspecialchars($notes); ?></textarea>
                                           
                                        </div>
                                    </div>
                                    </div>
        </div>
                            </div>
                        </div>
                        
                            <div class="col-md-12">
                                <div class="modal-footer">
                                <a href="#" data-dismiss="modal" class="btn" onclick="history.back()">Back</a>
                                <a data-toggle="modal" href="#myModalReturn" class="btn btn-primary">Return</a></div>

                                <div class="modal fade" id="myModalReturn">
                                    <div class="modal-dialog3">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h4 class="modal-title">Return Ticket</h4>
                                            </div>
                                            <div class="container"></div>
                                            <div class="modal-body">Confirm Returning ticket</div>
                                            <div class="modal-footer">	
                                                <button data-dismiss="modal" class="btn">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Confirm</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>     
                    </div>
                </div></div>
           
            <?php require_once('../footer.php') ?> 
        </div>   
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
</body>
</html>