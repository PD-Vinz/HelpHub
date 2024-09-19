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

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Processing'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $openedTickets = $pdoResult->rowCount();

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
    <link rel="icon" href="../img/logo.png" type="image/png">
  
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
    a {
        color: black;
    }
    textarea {
            width: 100%;
            height: 150px;
            margin-bottom: 10px;
        }
        .textarea-container {
            margin-bottom: 20px;
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
                     <h2>Issue Template</h2>   
                    </div>
                </div>              
                 <!-- /. ROW  -->
                 <hr />

    <div class="textarea-container">
        <h3>Employee Issues</h3>
        <textarea id="fileContent1"></textarea>
    </div>

    <div class="textarea-container">
        <h3>Student Issues</h3>
        <textarea id="fileContent2"></textarea>
    </div>
        
    <button id="saveButton">Save Changes</button>
    <a href="index.html"><button>Home</button></a>

    <script>
        // Function to load content from a text file into the corresponding textarea
        function loadFile(fileName, textareaId) {
            fetch(fileName)
                .then(response => response.text())
                .then(data => {
                    document.getElementById(textareaId).value = data;
                })
                .catch(error => console.error('Error loading the file:', error));
        }

        // Load both files on page load
        window.onload = function() {
            loadFile('../issue-template/employee-issue.txt', 'fileContent1');
            loadFile('../issue-template/student-issue.txt', 'fileContent2');
        };

        // Function to save both text files
        function saveFiles() {
            const content1 = document.getElementById('fileContent1').value;
            const content2 = document.getElementById('fileContent2').value;
            
            fetch('save.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'content1=' + encodeURIComponent(content1) + '&content2=' + encodeURIComponent(content2)
            })
            .then(response => {
                if (response.ok) {
                    alert('Saved Successfully!');
                } else {
                    alert('Failed to Save.');
                }
            })
            .catch(error => console.error('Error saving the files:', error));
        }

        document.getElementById('saveButton').addEventListener('click', saveFiles);
    </script>
                
                 <!-- /. ROW  -->
                         
                </div>
             <!-- /. PAGE INNER  -->
             <?php require_once('../footer.php') ?>
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
    <script>let profileDropdownList = document.querySelector(".profile-dropdown-list");
let btn = document.querySelector(".profile-dropdown-btn");

let classList = profileDropdownList.classList;

const toggle = () => classList.toggle("active");

window.addEventListener("click", function (e) {
  if (!btn.contains(e.target)) classList.remove("active");
});
</script>
    
   
</body>
</html>
