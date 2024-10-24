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
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
  <!-- FONTAWESOME STYLES-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<?php include 'loading.php'; ?>
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
            <?php if (!isset($_SESSION["Super-Admin"])): ?>
                <?php if ($identity == "Student"): ?>
                <a class="dropdown-item" href="logout.php" onclick="window.open('https://forms.gle/Bf2yoFEiYE8k56Pb6', '_blank');"><span class="fa fa-sign-out"></span> LOG OUT </a>
                <?php elseif ($identity == "Employee"): ?>
                <a class="dropdown-item" href="logout.php" onclick="window.open('https://forms.gle/kUJQW5YTbBfKKMw37', '_blank');"><span class="fa fa-sign-out"></span> LOG OUT </a>
                <?php endif; ?>
            <?php elseif (isset($_SESSION["Super-Admin"]) && $_SESSION["Super-Admin"] === 'Log In Success'): ?>
                <a class="dropdown-item" href="../index.php"><span class="fas fa-sign-out-alt"></span> Log Out</a>
            <?php endif; ?>          </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="assets/img/find_user.png" class="user-image img-responsive" />
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

                </ul>
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
    <h1>
  <Strong>HELP US SERVE YOU BETTER!</Strong><br><br>
  This Client Satisfaction Measurement (CSM) tracks the customer's experience of government services provided by the office, 
  Your feedback on your recently concluded transaction will help this office provide a better service.
  Personal information shared will be kept confidential and you always have the option to not answer this form. 
  <br><br>
  <strong style="color: #666666;"><em>TULUNGAN MO KAMI MAS MAPABUTI ANG AMING MGA PROSESO AT SERBISYO!</strong> <br><br>
  <p style="color: #666666;">Ang Client Satisfaction Measurement (CSM) ay naglalayong masubaybayan ang karaunsan ng taumbayan hinggil sa kanilang pakikitransuksyon sa mga tanggapan ng gobyerno. 
  Makatutulong ang inyong kasagutan ukol sa inyong naging karanasan sa kakatapos Iamang na transaksyon, upang mas mapabuti at lalong mapabusay ang aming serbisyo publiko. 
  Ang personal na impormasyon na iyong ibabahagi ay mananatiling kumpidensyal. Maari ring piliin na hindi saguatan ang sarbey na ito.</p></em>
  <br><br>
  View the Management Information System Office's <a href="https://drive.google.com/file/d/15sdFXoWaPcDCEvliSP02YKfJ7cZFRhJi/view?usp=sharing">Citizen's Charter</a>.
</h1><hr>

  
<form action="survey-extension.php?id=<?php echo $_GET['id']?>" method="post">
 <p><strong>INSTRUCTIONS:</strong> Checkmark your answer to the <strong>Citizen's Charter (CC)</strong> questions. The Citizen's Charter is an official document that reflects the services of a 
    government agency/office including its requirements, fees, and processing lines among others.</p>
    <p><em style="color: #666666;"><strong>PANUTO: </strong>Lagyan ng tsek ang iyong sagot sa mga sumusunod na katanungan tungkol sa Citizen's Charter (CC). 
    Ito ay isang opisyal na dokumento na naglalaman ng mga serbisyo sa isang ahensya/ opisina ng gobyerno, makikita rito ang mga 
    kinkailangan na dokumento, kaukulang bayarin at pangkabuuang oras na pagproseso.</em></p>
  <div class="question">
   
<hr>

    <label>CC1. Which of the following best describes your awareness of a Citizen's Charter?</label>
                            <ul>
                                <li><input type="radio" name="cc1" value="a" required> a. I know what a Citizen's Charter is and I saw the office's Citizen's Charter. <br>(𝘈𝘭𝘢𝘮 𝘬𝘰 𝘢𝘯𝘨 𝘊𝘊 𝘢𝘵 𝘯𝘢𝘬𝘪𝘵𝘢 𝘬𝘰 𝘪𝘵𝘰 𝘴𝘢 𝘯𝘢𝘱𝘶𝘯𝘵𝘢𝘩𝘢𝘯𝘨 𝘰𝘱𝘪𝘴𝘪𝘯𝘢.)</li>
                                <li><input type="radio" name="cc1" value="b" required> b. I know what a Citizen's Charter is but I did NOT see the office's Citizen's Charter. <br>(𝘈𝘭𝘢𝘮 𝘬𝘰 𝘢𝘯𝘨 𝘊𝘊 𝘱𝘦𝘳𝘰 𝘩𝘪𝘯𝘥𝘪 𝘬𝘰 𝘪𝘵𝘰 𝘯𝘢𝘬𝘪𝘵𝘢 𝘴𝘢 𝘯𝘢𝘱𝘶𝘯𝘵𝘢𝘩𝘢𝘯𝘨 𝘰𝘱𝘪𝘴𝘪𝘯𝘢.)</li>
                                <li><input type="radio" name="cc1" value="c" required> c. I learned of the Citizen's Charter only when I saw the office's Citizen's Charter. <br>(𝘕𝘢𝘭𝘢𝘮𝘢𝘯 𝘬𝘰 𝘢𝘯𝘨 𝘊𝘊 𝘯𝘢𝘯𝘨 𝘮𝘢𝘬𝘪𝘵𝘢 𝘬𝘰 𝘪𝘵𝘰 𝘴𝘢 𝘯𝘢𝘱𝘶𝘯𝘵𝘢𝘩𝘢𝘯𝘨 𝘰𝘱𝘪𝘴𝘪𝘯𝘢.)                                </li>
                                <li><input type="radio" name="cc1" value="d" required> d. I do not know what a Citizen's Charter is and I did not see one in this office. <br>(𝘏𝘪𝘯𝘥𝘪 𝘬𝘰 𝘢𝘭𝘢𝘮 𝘬𝘶𝘯𝘨 𝘢𝘯𝘰 𝘢𝘯𝘨 𝘊𝘊 𝘢𝘵 𝘸𝘢𝘭𝘢 𝘢𝘬𝘰𝘯𝘨 𝘯𝘢𝘬𝘪𝘵𝘢 𝘴𝘢 𝘯𝘢𝘱𝘶𝘯𝘵𝘢𝘩𝘢𝘯𝘨 𝘰𝘱𝘪𝘴𝘪𝘯𝘢. 𝘓𝘢𝘨𝘺𝘢𝘯 𝘯𝘨 𝘵𝘴𝘦𝘬 𝘢𝘯𝘨 "𝘕/𝘈* 𝘴𝘢 𝘊𝘊2 𝘢𝘵 𝘊𝘊3 𝘬𝘢𝘱𝘢𝘨 𝘪𝘵𝘰 𝘢𝘯𝘨 𝘺𝘰𝘯𝘨 𝘴𝘢𝘨𝘰𝘵. )
                                </li>
                            </ul>
                        </div>
                        <br>  <div class="question">
   


   <label>CC2. If aware of Citizen's Charter (answered 1-3 in CCI), would you say that the Citizen's Charter of this office was...</label>
                           <ul>
                               <li><input type="radio" name="cc2" value="a" required> a. Easy to see (𝘔𝘢𝘥𝘢𝘭𝘪𝘯𝘨 𝘮𝘢𝘬𝘪𝘵𝘢)</li>
                               <li><input type="radio" name="cc2" value="b" required> b. Somewhat easy to see (𝘔𝘦𝘥𝘺𝘰 𝘮𝘢𝘥𝘢𝘭𝘪𝘯𝘨 𝘮𝘢𝘬𝘪𝘵𝘢)</li>
                               <li><input type="radio" name="cc2" value="c" required> c. Difficult to see (𝘔𝘢𝘩𝘪𝘳𝘢𝘱 𝘮𝘢𝘬𝘪𝘵𝘢)</li>
                               <li><input type="radio" name="cc2" value="d" required> d. Not visible at all (𝘏𝘪𝘯𝘥𝘪 𝘮𝘢𝘬𝘪𝘭𝘢)</li>
                               <li><input type="radio" name="cc2" value="e" required> e. N/A</li>
                           </ul>
                       </div>

                       <br>  <div class="question">
   


   <label>CC3. If aware of Citizen's Charter (answered codes 1-3 in CC1), how much did the Citizen's Charter help you in your Transaction?<br>Kung alam ang CC (Nag-tsek sa opsyon 1-3 sa CCI), gaano nakatulong ang CC sa transaksyon mo?</label>
                           <ul>
                               <li><input type="radio" name="cc3" value="a" required> a. Helped very much (𝘚𝘰𝘣𝘳𝘢𝘯𝘨 𝘯𝘢𝘬𝘢𝘵𝘶𝘭𝘰𝘯𝘨)</li>
                               <li><input type="radio" name="cc3" value="b" required> b. Somewhat helped (𝘕𝘢𝘬𝘢𝘵𝘶𝘭𝘰𝘯𝘨 𝘯𝘢𝘮𝘢𝘯)</li>
                               <li><input type="radio" name="cc3" value="c" required> c. Did not help (𝘏𝘪𝘯𝘥𝘪 𝘯𝘢𝘬𝘢𝘵𝘶𝘭𝘰𝘯𝘨)</li>
                               <li><input type="radio" name="cc3" value="d" required> d. N/A</li>
                           </ul>
                       </div>

                       <hr>
                       <p><strong>INSTRUCTIONS:</strong> Checkmark your answer to the <strong>Citizen's Charter (CC)</strong> questions. The Citizen's Charter is an official document that reflects the services of a 
    government agency/office including its requirements, fees, and processing lines among others.</p>
    <br>
                       <div class="question">
                            <label>SQDO. I am satisfied with the service that I availed.<br> (𝘕𝘢𝘴𝘪𝘺𝘢𝘩𝘢𝘯 𝘢𝘬𝘰 𝘴𝘢 𝘴𝘦𝘳𝘣𝘪𝘴𝘺𝘰 𝘯𝘢 𝘢𝘬𝘪𝘯𝘨 𝘯𝘢𝘵𝘢𝘯𝘨𝘨𝘢𝘱 𝘴𝘢 𝘯𝘢𝘱𝘶𝘯𝘵𝘢𝘩𝘢𝘯 𝘯𝘢 𝘵𝘢𝘯𝘨𝘨𝘢𝘱𝘢𝘯.)</label>
                            <ul>
                                <li><input type="radio" name="sqd0" value="Strongly Agree" required> Strongly Agree</li>
                                <li><input type="radio" name="sqd0" value="Agree" required> Agree</li>
                                <li><input type="radio" name="sqd0" value="Neutral" required> Neutral</li>
                                <li><input type="radio" name="sqd0" value="Disagree" required> Disagree</li>
                                <li><input type="radio" name="sqd0" value="Strongly Disagree" required> Strongly Disagree</li>
                            </ul>
                        </div>
                        <div class="question">
                            <label>SQD1: 1 spent a reasonable amount of time for my transaction.<br> (𝘔𝘢𝘬𝘢𝘵𝘸𝘪𝘳𝘢𝘯 𝘢𝘯𝘨 𝘰𝘳𝘢𝘴 𝘯𝘢 𝘢𝘬𝘪𝘯𝘨 𝘨𝘪𝘯𝘶𝘨𝘰𝘭 𝘱𝘢𝘳𝘢 𝘴𝘢 𝘱𝘢𝘨𝘱𝘳𝘰𝘴𝘦𝘴𝘰 𝘯𝘨 𝘢𝘬𝘪𝘯𝘨 𝘵𝘳𝘢𝘯𝘴𝘢𝘬𝘴𝘺𝘰𝘯.)</label>
                            <ul>
                                <li><input type="radio" name="sqd1" value="Strongly Agree" required> Strongly Agree</li>
                                <li><input type="radio" name="sqd1" value="Agree" required> Agree</li>
                                <li><input type="radio" name="sqd1" value="Neutral" required> Neutral</li>
                                <li><input type="radio" name="sqd1" value="Disagree" required> Disagree</li>
                                <li><input type="radio" name="sqd1" value="Strongly Disagree" required> Strongly Disagree</li>
                            </ul>
                        </div>
                        <div class="question">
                            <label>SQD2. The office followed the transaction's requirements and steps based on the information provided.<br> (𝘈𝘯𝘨 𝘰𝘱𝘪𝘴𝘪𝘯𝘢 𝘢𝘺 𝘴𝘶𝘮𝘶𝘴𝘶𝘯𝘰𝘥 𝘴𝘢 𝘮𝘨𝘢 𝘬𝘪𝘯𝘢𝘬𝘢𝘪𝘭𝘢𝘯𝘨𝘢𝘯𝘨 𝘥𝘰𝘬𝘶𝘮𝘦𝘯𝘵𝘰 𝘢𝘵 𝘮𝘨𝘢 𝘩𝘢𝘬𝘩𝘢𝘯𝘨 𝘣𝘢𝘵𝘢𝘺 𝘴𝘢 𝘪𝘮𝘱𝘰𝘳𝘮𝘢𝘴𝘺𝘰𝘯𝘨 𝘣𝘪𝘯𝘪𝘨𝘢𝘺.)</label>
                            <ul>
                                <li><input type="radio" name="sqd2" value="Strongly Agree" required> Strongly Agree</li>
                                <li><input type="radio" name="sqd2" value="Agree" required> Agree</li>
                                <li><input type="radio" name="sqd2" value="Neutral" required> Neutral</li>
                                <li><input type="radio" name="sqd2" value="Disagree" required> Disagree</li>
                                <li><input type="radio" name="sqd2" value="Strongly Disagree" required> Strongly Disagree</li>
                            </ul>
                        </div> 
                        <div class="question">
                            <label>SQD3. The steps I needed to do for my transaction were easy and simple.<br> (𝘈𝘯𝘨 𝘮𝘨𝘢 𝘩𝘢𝘬𝘣𝘢𝘯𝘨 𝘴𝘢 𝘱𝘢𝘨𝘱𝘳𝘰𝘴𝘦𝘴𝘰 𝘬𝘢𝘴𝘢𝘮𝘢 𝘯𝘢 𝘢𝘯𝘨 𝘱𝘢𝘨𝘣𝘢𝘺𝘢𝘥 𝘢𝘺 𝘮𝘢𝘥𝘢𝘭𝘪 𝘢𝘵 𝘴𝘪𝘮𝘱𝘭𝘦 𝘭𝘢𝘮𝘢𝘯𝘨.)</label>
                            <ul>
                                <li><input type="radio" name="sqd3" value="Strongly Agree" required> Strongly Agree</li>
                                <li><input type="radio" name="sqd3" value="Agree" required> Agree</li>
                                <li><input type="radio" name="sqd3" value="Neutral" required> Neutral</li>
                                <li><input type="radio" name="sqd3" value="Disagree" required> Disagree</li>
                                <li><input type="radio" name="sqd3" value="Strongly Disagree" required> Strongly Disagree</li>
                            </ul>
                        </div>
                        <div class="question">
                            <label>SQD4. I easily found information about my transaction from the office's website.<br> (𝘔𝘢𝘣𝘪𝘭𝘪𝘴 𝘢𝘵 𝘮𝘢𝘥𝘢𝘭𝘪 𝘢𝘬𝘰𝘯𝘨 𝘯𝘢𝘬𝘢𝘩𝘢𝘯𝘢𝘱 𝘯𝘨 𝘪𝘮𝘱𝘰𝘳𝘮𝘢𝘴𝘺𝘰𝘯 𝘵𝘶𝘯𝘨𝘬𝘰𝘭 𝘴𝘢 𝘢𝘬𝘪𝘯𝘨 𝘵𝘳𝘢𝘯𝘴𝘢𝘬𝘢𝘺𝘰𝘯 𝘮𝘶𝘭𝘢 𝘴𝘢 𝘰𝘱𝘪𝘴𝘪𝘯𝘢 𝘰 𝘴𝘢 𝘸𝘦𝘣𝘴𝘪𝘵𝘦 𝘯𝘪𝘵𝘰.)</label>
                            <ul>
                                <li><input type="radio" name="sqd4" value="Strongly Agree" required> Strongly Agree</li>
                                <li><input type="radio" name="sqd4" value="Agree" required> Agree</li>
                                <li><input type="radio" name="sqd4" value="Neutral" required> Neutral</li>
                                <li><input type="radio" name="sqd4" value="Disagree" required> Disagree</li>
                                <li><input type="radio" name="sqd4" value="Strongly Disagree" required> Strongly Disagree</li>
                            </ul>
                        </div>
                       
                        <div class="question">
                            <label>SQD6. I am confident my transaction was secure.<br> (𝘗𝘢𝘬𝘪𝘳𝘢𝘮𝘥𝘢𝘮 𝘬𝘰 𝘢𝘺 𝘱𝘢𝘵𝘢𝘴 𝘢𝘯𝘨 𝘰𝘱𝘪𝘴𝘪𝘯𝘢 𝘴𝘢 𝘭𝘢𝘩𝘢𝘵 𝘰 "𝘸𝘢𝘭𝘢𝘯𝘨 𝘱𝘢𝘭𝘢𝘬𝘢𝘴𝘢𝘯", 𝘴𝘢 𝘢𝘬𝘪𝘯𝘨 𝘵𝘳𝘢𝘯𝘴𝘢𝘬𝘴𝘺𝘰𝘯."</label>
                            <ul>
                                <li><input type="radio" name="sqd6" value="Strongly Agree" required> Strongly Agree</li>
                                <li><input type="radio" name="sqd6" value="Agree" required> Agree</li>
                                <li><input type="radio" name="sqd6" value="Neutral" required> Neutral</li>
                                <li><input type="radio" name="sqd6" value="Disagree" required> Disagree</li>
                                <li><input type="radio" name="sqd6" value="Strongly Disagree" required> Strongly Disagree</li>
                            </ul>
                        </div>
                        <div class="question">
                            <label>SQD7. The office's online support was available, and (if asked questions) online support was quick to respond.<br>  (𝘔𝘢𝘨𝘢𝘭𝘢𝘯𝘨 𝘢𝘬𝘰𝘯𝘨 𝘵𝘳𝘪𝘯𝘢𝘵𝘰 𝘯𝘨 𝘮𝘨𝘢 𝘵𝘢𝘶𝘩𝘢𝘯, 𝘢𝘵 (𝘬𝘶𝘯𝘨 𝘴𝘢𝘬𝘢𝘭𝘪 𝘢𝘬𝘰 𝘢𝘺 𝘩𝘶𝘮𝘪𝘯𝘨𝘪𝘯𝘨 𝘵𝘶𝘭𝘰𝘯𝘨) 𝘢𝘭𝘢𝘮 𝘬𝘰 𝘯𝘢 𝘴𝘪𝘭𝘢 𝘢𝘺 𝘩𝘢𝘯𝘥𝘢 𝘵𝘶𝘮𝘶𝘭𝘰𝘯𝘨 𝘴𝘢 𝘢𝘬𝘪𝘯.)</label>
                            <ul>
                                <li><input type="radio" name="sqd7" value="Strongly Agree" required> Strongly Agree</li>
                                <li><input type="radio" name="sqd7" value="Agree" required> Agree</li>
                                <li><input type="radio" name="sqd7" value="Neutral" required> Neutral</li>
                                <li><input type="radio" name="sqd7" value="Disagree" required> Disagree</li>
                                <li><input type="radio" name="sqd7" value="Strongly Disagree" required> Strongly Disagree</li>
                            </ul>
                        </div>
                        <div class="question">
                            <label>SQD8. I got what I needed from the government office, or (if denied) denial of request was sufficiently explained to me.<br> (𝘕𝘢𝘬𝘶𝘩𝘢 𝘬𝘰 𝘢𝘯𝘨 𝘬𝘪𝘯𝘬𝘢𝘪𝘭𝘢𝘯𝘨𝘢𝘯 𝘬𝘰 𝘮𝘶𝘭𝘢 𝘴𝘢 𝘵𝘢𝘯𝘨𝘨𝘢𝘱𝘢𝘯 𝘯𝘨 𝘨𝘰𝘣𝘺𝘦𝘳𝘯𝘰, 𝘬𝘶𝘯𝘨 𝘵𝘪𝘯𝘢𝘯𝘨𝘨𝘪𝘩𝘢𝘯 𝘮𝘢𝘯, 𝘪𝘵𝘰 𝘢𝘺 𝘴𝘢𝘱𝘢𝘵 𝘯𝘢 𝘪𝘱𝘢𝘭𝘪𝘸𝘢𝘯𝘢𝘨 𝘴𝘢 𝘢𝘬𝘪𝘯.)</label>
                            <ul>
                                <li><input type="radio" name="sqd8" value="Strongly Agree" required> Strongly Agree</li>
                                <li><input type="radio" name="sqd8" value="Agree" required> Agree</li>
                                <li><input type="radio" name="sqd8" value="Neutral" required> Neutral</li>
                                <li><input type="radio" name="sqd8" value="Disagree" required> Disagree</li>
                                <li><input type="radio" name="sqd8" value="Strongly Disagree" required> Strongly Disagree</li>
                            </ul>
                        </div>
                     
<hr>
 
                        <div class="question">
                            <label>Overall Satisfaction</label>
                            <ul>
                                <li><input type="radio" name="overall_satisfaction" value="Very Satisfied" required> Very Satisfied</li>
                                <li><input type="radio" name="overall_satisfaction" value="Satisfied" required> Satisfied</li>
                                <li><input type="radio" name="overall_satisfaction" value="Neutral" required> Neutral</li>
                                <li><input type="radio" name="overall_satisfaction" value="Dissatisfied" required> Dissatisfied</li>
                                <li><input type="radio" name="overall_satisfaction" value="Very Dissatisfied" required> Very Dissatisfied</li>
                            </ul>
                        </div>
                        <div class="question">
                            <label>How would you rate our service?</label>
                            <ul>
                                <li><input type="radio" name="service_rating" value="Very Satisfied" required> Very Satisfied</li>
                                <li><input type="radio" name="service_rating" value="Satisfied" required> Satisfied</li>
                                <li><input type="radio" name="service_rating" value="Neutral" required> Neutral</li>
                                <li><input type="radio" name="service_rating" value="Dissatisfied" required> Dissatisfied</li>
                                <li><input type="radio" name="service_rating" value="Very Dissatisfied" required> Very Dissatisfied</li>
                            </ul>
                        </div>
                        <div class="question">
                            <label>Did our service meet your expectations?</label>
                            <ul>
                                <li><input type="radio" name="service_expectations" value="Very Satisfied" required> Very Satisfied</li>
                                <li><input type="radio" name="service_expectations" value="Satisfied" required> Satisfied</li>
                                <li><input type="radio" name="service_expectations" value="Neutral" required> Neutral</li>
                                <li><input type="radio" name="service_expectations" value="Dissatisfied" required> Dissatisfied</li>
                                <li><input type="radio" name="service_expectations" value="Very Dissatisfied" required> Very Dissatisfied</li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="submit">NEXT</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                <!-- /. ROW  -->
            </div></div></div>
            <!-- /. PAGE INNER  -->
            <?php require_once ('../footer.php')?>
        </div>
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
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>

</html>