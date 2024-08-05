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
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
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
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>Feedback Analysis</h2>   
                        <h5>Welcome Jhon Deo , Love to see you back. </h5>
                 
                    </div>

                    
                </div>
<div class="col-md-8">
  <div class="panel panel-default">
    <div class="panel-heading">
      Top Comments
    </div>
    <div class="panel-body" >
      <ul>
      <li><h4> The response time was quick. i was able to see the progress of my ticket</h4></li>
      <li><h4> fast and reliable</h4></li>
      <li><h4> yes</h4></li>
      <li><h4> Admins are good looking</h4></li>
  </ul>
      </div> 
  </div>
</div>
                <div class="col-md-4">
  <div class="panel panel-default">
    <div class="panel-heading">
      Customer Satisfaction (CSAT)
    </div>
    <div class="panel-body">
      <div class="csat-container">
        <span class="csat-label">Monthly +43% &#9650;</span> 
        <div class="csat-percentage">
          70%
        </div>
      </div>
    </div>
  </div>
</div>



<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Ratings Distribution (4748 Responses)
        </div>
        <div class="panel-body" id="ratingBarsContainer"> 
        </div> 
    </div>
</div>

<div class="col-md-6">
  <div class="panel panel-default">
    <div class="panel-heading">
      User Feedback
    </div>
    <div class="panel-body" id="npsContainer">
      <br>
      </div> 
  </div>
</div>
<br>

<div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>Feedback List</h3>
                        </div>
                        <div class="panel-body-ticket">
                            <div class="table-responsive">

<?php
$pdoQuery = "SELECT * FROM tb_survey_feedback";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoExec = $pdoResult->execute();

?>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr class="btn-primary">
                                        <th>Survey ID</th>
                                        <th>User ID</th>
                                        <th>Date & Time</th>
                                        <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            <?php
                while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
            ?>
                    <tr class='odd gradeX'>
                    <td><?php echo htmlspecialchars($survey_id); ?></td>
                    <td><?php echo htmlspecialchars($user_id); ?></td>
                    <td><?php echo htmlspecialchars($date_time); ?></td>
                    <td>
                      <div class='panel-body-ticket'>
                            <button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModal<?php echo $survey_id; ?>'>
                                View Details
                            </button>
                      </div>
                    </td>


<div class="modal fade" id="myModal<?php echo $survey_id; ?>" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="container"></div>
            <div class="modal-body">
                                          <div class="row">
                                <div class="col-md-6">
                                    <h3>Survey Details</h3>
                                    <form role="form">
                                       
                                      
                                        <div class="form-group">
                                            <label>Survey ID‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($survey_id); ?>" disabled/>
                                            <br><br>
                                        </div>
                                      
                                        <div class="form-group">
                                            <label>User ID‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($user_id); ?>" disabled/>
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Date & Time ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($date_time); ?>" disabled/>
                                         <br><br>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Ticket ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($ticket_id); ?>" disabled/>
                                            <br><br>
                                        </div>
                                        
                                        <?php  
                                        if ($taken == 'before'){
                                              $whenistaken = "After Submitting Ticket";
                                        } elseif ($taken == 'after'){
                                              $whenistaken = "After Ticket was Completed";
                                        }
                                        ?>

                                        <div class="form-group">
                                            <label>Survey Taken ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php  echo htmlspecialchars($whenistaken) ?>" disabled/>
                                            <br><br>
                                        </div>

                                        
                                    </form>      
                                </div>
                                
                                <div class="col-md-6">
                                    <h3>Ratings And Comments</h3>
                                    
                                    <form role="form">
                                        <div class="form-group">
                                            <label>Overall Satisfaction‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($overall_satisfaction); ?>" disabled/>
                                            <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>How would you rate our service?  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($service_rating); ?>" disabled/>
                                            <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>Did our service meet your expectations? ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" value="<?php echo htmlspecialchars($service_expectations); ?>" disabled/>                                            <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>What did you like most about our service? </label>
                                            <textarea rows="4" class="form-control" style="height:148px; resize:none; overflow:auto;" disabled><?php echo htmlspecialchars($like_service); ?></textarea>
                                            <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>What areas do you think need improvement?‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea rows="4" class="form-control" style="height:148px; resize:none; overflow:auto;" disabled><?php echo htmlspecialchars($improvement); ?></textarea>
                                         <br><br>
                                        </div>

                                        <div class="form-group">
                                            <label>Any additional comments or suggestions?‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <textarea rows="4" class="form-control" style="height:148px; resize:none; overflow:auto;" disabled><?php echo htmlspecialchars($comments); ?></textarea>
                                         <br><br>
                                        </div>
                                    </form>
                                </div>
                            </div>
                          
        </div>
        
    </div>
</div>
                              </div>
        
        

                          </div>
        <?php
        }
        ?>
                                        

                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
</div>           
            </div>
        </div>




                 <!-- /. ROW  -->
               
               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    
    <script>
const ratingData = [
    { stars: 5, count: 1084, color: '#ff9900' },
    { stars: 4, count: 2801, color: '#ffb300' },
    { stars: 3, count: 341, color: '#ffc200' },
    { stars: 2, count: 402, color: '#ffd100' },
    { stars: 1, count: 120, color: '#ffdc00' },
];

const totalResponses = ratingData.reduce((sum, rating) => sum + rating.count, 0);

const ratingBarsContainer = document.getElementById('ratingBarsContainer');

ratingData.forEach(rating => {
    const percentage = (rating.count / totalResponses) * 100;

    const ratingBar = document.createElement('div');
    ratingBar.classList.add('rating-bar', 'mb-2');

    const ratingLabel = document.createElement('span');
    ratingLabel.classList.add('rating-label');
    ratingLabel.textContent = `${rating.stars} ★`;

    const progressContainer = document.createElement('div');
    progressContainer.classList.add('progress-container');

    const progressBar = document.createElement('div');
    progressBar.classList.add('progress-bar');
    progressBar.style.width = `${percentage}%`;
    progressBar.style.backgroundColor = rating.color;
    progressBar.setAttribute('aria-valuenow', percentage);

    const ratingCount = document.createElement('span');
    ratingCount.classList.add('rating-count');
    ratingCount.textContent = rating.count;

    progressContainer.appendChild(progressBar);
    ratingBar.appendChild(ratingLabel);
    ratingBar.appendChild(progressContainer);
    ratingBar.appendChild(ratingCount);
    ratingBarsContainer.appendChild(ratingBar);
});

const npsData = [
  { label: 'Positive', emoji: '😍', percentage: 55, color: '#5cb85c' },
  { label: 'Neutral', emoji: '😐', percentage: 23, color: '#ffb300' },
  { label: 'Negative', emoji: '☹️', percentage: 22, color: '#d9534f' }
];

const npsContainer = document.getElementById('npsContainer');

npsData.forEach(item => {
  const row = document.createElement('div');
  row.classList.add('nps-row');

  const emoji = document.createElement('span');
  emoji.classList.add('nps-emoji');
  emoji.textContent = item.emoji;

  const label = document.createElement('span');
  label.classList.add('nps-label');
  label.textContent = item.label;

  const progressContainer = document.createElement('div');
  progressContainer.classList.add('nps-progress-container');

  const progressBar = document.createElement('div');
  progressBar.classList.add('nps-progress-bar');
  progressBar.style.width = `${item.percentage}%`;
  progressBar.style.backgroundColor = item.color;

  const percentage = document.createElement('span');
  percentage.textContent = `${item.percentage}%`;

  progressContainer.appendChild(progressBar);
  row.appendChild(emoji); 
  row.appendChild(label);
  row.appendChild(progressContainer);
  row.appendChild(percentage); 

  npsContainer.appendChild(row);
});

</script>

    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
   
</body>
</html>
