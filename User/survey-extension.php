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
    if ($identity == "Student"){
        $pdoUserQuery = "SELECT * FROM student_user WHERE user_id = :number";
        $pdoResult = $pdoConnect->prepare($pdoUserQuery);
        $pdoResult->bindParam(':number', $id);
        $pdoResult->execute();
    
        $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);
    
        if ($Data) {
            $Name = $Data['name'];
            $Department = $Data['department'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
    
            $nameParts = explode(' ', $Name);
            $firstName = $nameParts[0];
    
            $P_PBase64 = base64_encode($P_P);
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
            $Name = $Data['name'];
            $Department = $Data['department'];
            $Y_S = $Data['year_section'];
            $P_P = $Data['profile_picture'];
    
            $nameParts = explode(' ', $Name);
            $firstName = $nameParts[0];
    
            $P_PBase64 = base64_encode($P_P);
        } else {
            // Handle the case where no results are found
            echo "No student found with the given student number.";
        }
    }

}

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*"> 
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css?v=<?php echo time(); ?>" rel="stylesheet" />
  <!-- FONTAWESOME STYLES-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css?v=<?php echo time(); ?>" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css?v=<?php echo time(); ?>" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="bayes.js"></script>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><?php echo $shortName?></a>
            </div>
            <div style="color: white;
            padding: 15px 50px 5px 50px;
            float: right;
            font-size: 16px;"> Last access : <?php echo date('d F Y')?> &nbsp; 
            <div class="btn-group nav-link">
              <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="ml-3"><?php echo $Name?></span>
            <span class="fa fa-caret-down">
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="profile.php"><span class="fa fa-user"></span> MY ACCOUNT</a>
            <hr style="margin-top: 5px; margin-bottom: 5px;">
            <a class="dropdown-item" href="settings.php"><span class="fa fa-gear"></span> SETTINGS</a>
            <hr style="margin-top: 5px; margin-bottom: 5px;">
            <a class="dropdown-item" href="logout.php"><span class="fa fa-sign-out"></span> LOG OUT </a>          </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                    <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="user-image img-responsive" />
                        <h3 style="color:white;"><?php echo $Name?></h3>
                    </li>




                    <li>
                    <a href="dashboard.php"><i class="fa fa-dashboard fa-xl" style="font-size:24px;color:rgb(255, 255, 255)"></i>  DASHBOARD </a>
                    </li>
                    <li>
                    <a href="profile.php"><i class="fa fa-user fa-xl" style="font-size:24px;color:rgb(255, 255, 255)"></i> PROFILE </a>
                        </li>

                        <li>
                            <a href="create-ticket.php"><i class="fa fa-plus fa-xl" style="font-size: 24px; color: rgb(255, 255, 255)"></i> CREATE TICKET </a>
                            </li>
                            <li>
                        <a href="all-ticket.php"><i class="fa fa-ticket fa-xl" style="font-size: 24px; color: rgb(255, 255, 255)"></i> ALL TICKET </a>
                    </li>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12"> <div class="col-md-12">
                    <div class="col-md-12">
                        <h2>SURVEY FORM</h2>
                    </div>
                </div>


                <div class="container-center">
                    <div class="modal-header">
                        <img src="assets/pic/head.png" alt="Technical support for DHVSU students">  
                <div class="container-survey">
                    <h1>Thank you for choosing our services. We highly value your feedback as it helps us improve and better serve you in the future. Please take a moment to share your thoughts with us.</h1>
                    <script>
    function classifyText(text) {
        var scores = Bayes.guess(text);
        var winner = Bayes.extractWinner(scores);
        return winner.label;
    }

    function processForm(event) {
        event.preventDefault();

        const like = document.getElementById('like').value;
        const improve = document.getElementById('improve').value;
        const comments = document.getElementById('comments').value;

        const likeRating = classifyText(like);
        const improveRating = classifyText(improve);
        const commentsRating = classifyText(comments);

        document.getElementById('likeRating').value = likeRating;
        document.getElementById('improveRating').value = improveRating;
        document.getElementById('commentsRating').value = commentsRating;

        document.getElementById('surveyForm').submit();
    }
</script>

<form id="surveyForm" action="php/survey-finished.php?id=<?php echo $_GET['id']?>" method="post" onsubmit="processForm(event)">
 <input type="text" name="cc1" value="<?php echo $_POST['cc1']?>" hidden>
    <input type="text" name="cc2" value="<?php echo $_POST['cc2']?>" hidden>
    <input type="text" name="cc3" value="<?php echo $_POST['cc3']?>" hidden>
    <input type="text" name="sqd0" value="<?php echo $_POST['sqd0']?>" hidden>
    <input type="text" name="sqd1" value="<?php echo $_POST['sqd1']?>" hidden>
    <input type="text" name="sqd2" value="<?php echo $_POST['sqd2']?>" hidden>
    <input type="text" name="sqd3" value="<?php echo $_POST['sqd3']?>" hidden>
    <input type="text" name="sqd4" value="<?php echo $_POST['sqd4']?>" hidden>
    <input type="text" name="sqd6" value="<?php echo $_POST['sqd6']?>" hidden>
    <input type="text" name="sqd7" value="<?php echo $_POST['sqd7']?>" hidden>
    <input type="text" name="sqd8" value="<?php echo $_POST['sqd8']?>" hidden>
    <input type="text" name="overall_satisfaction" value="<?php echo $_POST['overall_satisfaction']?>" hidden>
    <input type="text" name="service_rating" value="<?php echo $_POST['service_rating']?>" hidden>
    <input type="text" name="service_expectations" value="<?php echo $_POST['service_expectations']?>" hidden>
    <input type="hidden" id="likeRating" name="likeRating">
    <input type="hidden" id="improveRating" name="improveRating">
    <input type="hidden" id="commentsRating" name="commentsRating">

    <div class="question">
        <p><strong>PLEASE ANSWER THE FOLLOWING QUESTION:</strong></p>
        <label for="like">What did you like most about our service?</label>
        <textarea id="like" name="like" rows="4" maxlength="255" oninput="likeRemainingCharacters()" required></textarea>
        <small id="like-remaining-characters" class="form-text text-muted">255 characters remaining</small>
    </div>
    <div class="question">
        <label for="improve">What areas do you think could be improved?</label>
        <textarea id="improve" name="improve" rows="4" maxlength="255" oninput="improveRemainingCharacters()" required></textarea>
        <small id="improve-remaining-characters" class="form-text text-muted">255 characters remaining</small>
    </div>
    <div class="question">
        <label for="comments">Comments or suggestions on how we can improve our service?</label>
        <textarea id="comments" name="comments" rows="4" maxlength="255" oninput="commentsRemainingCharacters()" required></textarea>
        <small id="comments-remaining-characters" class="form-text text-muted">255 characters remaining</small>
    </div>
    <div class="buttons">
        <a onclick="history.back()"><button type="button">BACK</button></a>
        <button type="submit">SUBMIT</button>
    </div>
</form>


                </div>
                <!-- /. ROW  -->
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    </div>
    </div><?php require_once ('../footer.php')?>
    </div>
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
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>

    <script>

    function likeRemainingCharacters() {
        const textarea = document.getElementById('like');
        const remainingChars = 255 - textarea.value.length;
        document.getElementById('like-remaining-characters').textContent = `${remainingChars} characters remaining`;
    }

    function improveRemainingCharacters() {
        const textarea = document.getElementById('improve');
        const remainingChars = 255 - textarea.value.length;
        document.getElementById('improve-remaining-characters').textContent = `${remainingChars} characters remaining`;
    }

    function commentsRemainingCharacters() {
        const textarea = document.getElementById('comments');
        const remainingChars = 255 - textarea.value.length;
        document.getElementById('comments-remaining-characters').textContent = `${remainingChars} characters remaining`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('like');
        textarea.addEventListener('input', function() {
            adjustHeight();
            likeRemainingCharacters();
            improveRemainingCharacters();
            commentsRemainingCharacters();
        });

        // Initial adjustment in case there's already content
        adjustHeight();
        likeRemainingCharacters();
        improveRemainingCharacters();
        commentsRemainingCharacters();
    });

    </script>
</body>

</html>