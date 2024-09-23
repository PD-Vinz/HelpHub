<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>USER</title>
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
                <a class="navbar-brand" href="dashboard.php">USER</a>
            </div>
            <div style="color: white;
            padding: 15px 50px 5px 50px;
            float: right;
            font-size: 16px;"> Last access : 30 May 2014 &nbsp; 
            <div class="btn-group nav-link">
              <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="ml-3">LOREM IPSUN</span>
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
                        <img src="assets/img/find_user.png" class="user-image img-responsive" />
                    </li>




                    <li>
                        <a href="dashboard.php"><i class="bx bxs-dashboard fa" style="font-size:36px;color:rgb(255, 255, 255)"></i> DASHBOARD </a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="bx bx-user" style="font-size:36px;color:rgb(255, 255, 255)"></i> PROFILE </a>
                    </li>
                    <li>
                            <a href="create-ticket.php">
                            <i class="fa fa-plus" style="font-size: 36px; color: rgb(255, 255, 255)"></i> CREATE TICKET </a>
                            </li>
                            <li>
                        <a href="all-ticket.php"><i class="fa fa-ticket" style="font-size:36px"></i> ALL TICKET </a>
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

<form id="surveyForm" action="php/survey-finished.php?id=<?php echo $_GET['id']?>&taken=<?php echo $_GET['taken']?>" method="post" onsubmit="processForm(event)">
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
        <label for="improve">What areas do you think need improvement?</label>
        <textarea id="improve" name="improve" rows="4" maxlength="255" oninput="improveRemainingCharacters()" required></textarea>
        <small id="improve-remaining-characters" class="form-text text-muted">255 characters remaining</small>
    </div>
    <div class="question">
        <label for="comments">Any additional comments or suggestions?</label>
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
    <?php require_once ('../footer.php')?>
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
