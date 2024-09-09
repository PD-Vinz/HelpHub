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
    $query = $pdoConnect->prepare("SELECT system_name, short_name, system_logo, system_cover FROM settings WHERE id = :id");
    $query->execute(['id' => 1]);
    $Datas = $query->fetch(PDO::FETCH_ASSOC);
    $sysName = $Datas['system_name'] ?? '';
    $shortName = $Datas['short_name'] ?? '';
    $systemLogo = $Datas['system_logo'];
    $systemCover = $Datas['system_cover'];
    
try {

    $pdoCountQuery = "SELECT * FROM tb_tickets";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $allTickets = $pdoResult->rowCount();

    $pdoCountfeedQuery = "SELECT * FROM tb_survey_feedback";
    $pdoResult = $pdoConnect->prepare($pdoCountfeedQuery);
    $pdoResult->execute();
    $allFeedback = $pdoResult->rowCount();

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


    //feedback analysis

    //overall satisfaction

    $sql = "SELECT overall_satisfaction FROM tb_survey_feedback";
$stmt = $pdoConnect->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$scounts = [
    'very satisfied' => 0,
    'satisfied' => 0,
    'neutral' => 0,
    'dissatisfied' => 0,
    'very dissatisfied' => 0
];

foreach ($results as $row) {
    $satisfaction = strtolower($row['overall_satisfaction']);
    if (array_key_exists($satisfaction, $scounts)) {
        $scounts[$satisfaction]++;
    }
}

$totalEntries = array_sum($scounts);
$percentages = [];
foreach ($scounts as $satisfaction => $count) {
    $percentages[$satisfaction] = ($totalEntries > 0) ? ($count / $totalEntries) * 100 : 0;
}
$overallSatisfactionPercentage = $percentages['very satisfied'] + $percentages['satisfied'];

// Service rating
$sql = "SELECT service_rating FROM tb_survey_feedback";
$stmt = $pdoConnect->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$service_counts = [
    'very satisfied' => 0,
    'satisfied' => 0,
    'neutral' => 0,
    'dissatisfied' => 0,
    'very dissatisfied' => 0
];

foreach ($results as $row) {
    $service = strtolower($row['service_rating']);
    if (array_key_exists($service, $service_counts)) {
        $service_counts[$service]++;
    }
}

$service_totalEntries = array_sum($service_counts);
$service_percentages = [];
foreach ($service_counts as $service => $service_count) {
    $service_percentages[$service] = ($service_totalEntries > 0) ? ($service_count / $service_totalEntries) * 100 : 0;
}
$overallServiceRating = $service_percentages['very satisfied'] + $service_percentages['satisfied'];

// Service expectations
$sql = "SELECT service_expectations FROM tb_survey_feedback";
$stmt = $pdoConnect->prepare($sql);
$stmt->execute();
$expectationResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

$expectation_counts = [
    'very satisfied' => 0,
    'satisfied' => 0,
    'neutral' => 0,
    'dissatisfied' => 0,
    'very dissatisfied' => 0
];

foreach ($expectationResults as $row) {
    $expectation = strtolower($row['service_expectations']);
    if (array_key_exists($expectation, $expectation_counts)) {
        $expectation_counts[$expectation]++;
    }
}

$expectation_totalEntries = array_sum($expectation_counts);
$expectation_percentages = [];
foreach ($expectation_counts as $expectation => $count) {
    $expectation_percentages[$expectation] = ($expectation_totalEntries > 0) ? ($count / $expectation_totalEntries) * 100 : 0;
}

$overallExpectationPercentage = $expectation_percentages['very satisfied'] + $expectation_percentages['satisfied'];


// Pass data to JavaScript
$ratingData = [
    ['label' => 'Very Satisfied', 'count' => $scounts['very satisfied'], 'color' => '#4caf50'],
    ['label' => 'Satisfied', 'count' => $scounts['satisfied'], 'color' => '#8bc34a'],
    ['label' => 'Neutral', 'count' => $scounts['neutral'], 'color' => '#ffeb3b'],
    ['label' => 'Dissatisfied', 'count' => $scounts['dissatisfied'], 'color' => '#ff9800'],
    ['label' => 'Very Dissatisfied', 'count' => $scounts['very dissatisfied'], 'color' => '#f44336']
];

$service_ratingData = [
    ['label' => 'Very Satisfied', 'count' => $service_counts['very satisfied'], 'color' => '#4caf50'],
    ['label' => 'Satisfied', 'count' => $service_counts['satisfied'], 'color' => '#8bc34a'],
    ['label' => 'Neutral', 'count' => $service_counts['neutral'], 'color' => '#ffeb3b'],
    ['label' => 'Dissatisfied', 'count' => $service_counts['dissatisfied'], 'color' => '#ff9800'],
    ['label' => 'Very Dissatisfied', 'count' => $service_counts['very dissatisfied'], 'color' => '#f44336']
];

$expectation_ratingData = [
    ['label' => 'Very Satisfied', 'count' => $expectation_counts['very satisfied'], 'color' => '#4caf50'],
    ['label' => 'Satisfied', 'count' => $expectation_counts['satisfied'], 'color' => '#8bc34a'],
    ['label' => 'Neutral', 'count' => $expectation_counts['neutral'], 'color' => '#ffeb3b'],
    ['label' => 'Dissatisfied', 'count' => $expectation_counts['dissatisfied'], 'color' => '#ff9800'],
    ['label' => 'Very Dissatisfied', 'count' => $expectation_counts['very dissatisfied'], 'color' => '#f44336']
];

echo "<script>
    var ratingData = " . json_encode($ratingData) . ";
    var totalResponses = $totalEntries;
    var serviceRatingData = " . json_encode($service_ratingData) . ";
    var serviceTotalResponses = $service_totalEntries;
    var expectationRatingData = " . json_encode($expectation_ratingData) . ";
    var expectationTotalResponses = $expectation_totalEntries;
</script>";


//bayes analysis

$sql = "SELECT bayes_rating_like, bayes_rating_improve, bayes_rating_comment FROM tb_survey_feedback";
$stmt = $pdoConnect->prepare($sql);
$stmt->execute();
$feedbackResults = $stmt->fetchAll(PDO::FETCH_ASSOC);


$bayesCounts = [
  'like' => ['positive' => 0, 'neutral' => 0, 'negative' => 0],
  'improve' => ['positive' => 0, 'neutral' => 0, 'negative' => 0],
  'comment' => ['positive' => 0, 'neutral' => 0, 'negative' => 0]
];

foreach ($feedbackResults as $row) {
  foreach (['like', 'improve', 'comment'] as $key) {
      $rating = strtolower($row["bayes_rating_$key"]);
      if (isset($bayesCounts[$key][$rating])) {
          $bayesCounts[$key][$rating]++;
      }
  }
}

// Calculate total counts and percentages
$bayesPercentages = [];
foreach ($bayesCounts as $key => $counts) {
  $total = array_sum($counts);
  $percentages = [];
  foreach ($counts as $rating => $count) {
      $percentages[$rating] = ($total > 0) ? ($count / $total) * 100 : 0;
  }
  $bayesPercentages[$key] = $percentages;
}
echo "<script>
    var bayesData = {
        like: " . json_encode($bayesPercentages['like']) . ",
        improve: " . json_encode($bayesPercentages['improve']) . ",
        comment: " . json_encode($bayesPercentages['comment']) . "
    };
</script>";

// Initialize variables for total responses and positive responses
$totalResponses = 0;
$totalPositiveResponses = 0;

foreach ($feedbackResults as $row) {
    foreach (['like', 'improve', 'comment'] as $key) {
        $rating = strtolower($row["bayes_rating_$key"]);
        
        // Increment the total responses counter
        $totalResponses++;
        
        // Increment the total positive responses counter if the rating is positive
        if ($rating === 'positive') {
            $totalPositiveResponses++;
        }
    }
}

// Calculate the overall percentage of positive responses

// Calculate total positive responses for each metric
$totalSatisfied = $scounts['very satisfied'] + $scounts['satisfied'];
$totalServiceSatisfied = $service_counts['very satisfied'] + $service_counts['satisfied'];
$totalExpectationSatisfied = $expectation_counts['very satisfied'] + $expectation_counts['satisfied'];

// Total positive responses from all metrics
$totalPositiveResponses = $totalSatisfied + $totalServiceSatisfied + $totalExpectationSatisfied + $totalPositiveResponses;

// Total responses from all metrics
$totalResponses = $totalEntries + $service_totalEntries + $expectation_totalEntries + $totalResponses;

// Calculate the overall positive percentage
$overallPositivePercentage = ($totalResponses > 0) ? ($totalPositiveResponses / $totalResponses) * 100 : 0;

// Output the overall positive percentage
echo "<script>
    var overallPositivePercentage = " . json_encode($overallPositivePercentage) . ";
    console.log('Overall Positive Percentage: ' + overallPositivePercentage + '%');
</script>";



//feedback analysis end

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
    <title><?php echo $sysName?></title>
  
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
                    <div  class="col-md-12">
                     <h2 >Feedback Analysis</h2>   
                     <div class="col-md-4"> 
  <div class="panel panel-default">
    <div class="panel-heading">
     <h3 style="margin-top: 5px; margin-bottom:0px;"> Customer Satisfaction (CSAT)</h3>
    </div>
    <div class="panel-body" >
      <div class="csat-container">
      <br> <br> <br>
        <span class="csat-label">Overall positive responses:</span> 
        <div class="csat-percentage">
          <?php echo number_format($overallPositivePercentage, 2); ?>%
        </div>
 
        <h4>Total number of feedbacks:<?php echo number_format($allFeedback); ?></h4> <br> <br> <br> <h4> </h4>
      </div>
    </div>
    
  </div>
</div>
                     <div class="col-md-8" >
                    <!-- Advanced Tables -->
                    <div class="panel panel-default" style="height:440">
                        <div class="panel-heading">
                            <h3 style="margin-top: 5px; margin-bottom:0px;">Feedback List</h3>
                        </div>
                        <div class="panel-body-ticket scrollable-panel" >
                            <div class="table-responsive col-md-12">

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
              
                <div  class="col-md-12" style="margin-top: 5px; margin-bottom:5px;">
                <div class="col-md-4"> 
  <div class="panel panel-default">
    <div class="panel-heading">
    <h3 style="margin-top: 5px; margin-bottom:0px;">"Overall Satisfaction"</h3>
    </div>
    <div class="panel-body" id="ratingBarsContainer">
      <div class="csat-container">
        
        <div class="csat-percentage">
          <?php echo number_format($overallSatisfactionPercentage, 2); ?>%
        </div>
 
        <h4>Overall Satisfaction Breakdown</h4>
      </div>
    </div>
    
  </div>
</div>

<div class="col-md-4"> 
  <div class="panel panel-default">
    <div class="panel-heading">
    <h3 style="margin-top: 5px; margin-bottom:0px;"> Service Rating</h3>
    </div>
    <div class="panel-body" id="serviceRatingBarsContainer">
      <div class="csat-container">
     
        <div class="csat-percentage">
          <?php echo number_format($overallServiceRating, 2); ?>%
        </div>
        <h4>Service Rating Breakdown</h4>

      </div>
    </div>
    
  </div>
</div>
<div class="col-md-4"> 
  <div class="panel panel-default">
    <div class="panel-heading">
    <h3 style="margin-top: 5px; margin-bottom:0px;">Service Expectations</h3> 
    </div>
    <div class="panel-body" id="expectationRatingBarsContainer">
      <div class="csat-container">

        <div class="csat-percentage">
          <?php echo number_format($overallExpectationPercentage, 2); ?>%
        </div>
        <h4>Service Expectation Breakdown</h4>

      </div>
    </div>
    
  </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading"><h3 style="margin-top: 5px; margin-bottom:0px;">Bayes Rating Like</h3></div>
        <div class="panel-body" id="likeContainer"></div>
    </div>
</div>

<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading"><h3 style="margin-top: 5px; margin-bottom:0px;">Bayes Rating Improve</h3></div>
        <div class="panel-body" id="improveContainer"></div>
    </div>
</div>

<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading"><h3 style="margin-top: 5px; margin-bottom:0px;">Bayes Rating Comment</h3></div>
        <div class="panel-body" id="commentContainer"></div>
        
    </div>
</div>
</div>

<br>

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
    const ratingBarsContainer = document.getElementById('ratingBarsContainer');

    ratingData.forEach(rating => {
    const percentage = (rating.count / totalResponses) * 100;

    const ratingBar = document.createElement('div');
    ratingBar.classList.add('rating-bar');

    const ratingLabel = document.createElement('span');
    ratingLabel.classList.add('rating-label');
    ratingLabel.textContent = rating.label;

    const progressContainer = document.createElement('div');
    progressContainer.classList.add('progress-container');

    const progressBar = document.createElement('div');
    progressBar.classList.add('progress-bar');
    progressBar.style.width = `${percentage.toFixed(2)}%`;
    progressBar.style.backgroundColor = rating.color;
    progressBar.setAttribute('aria-valuenow', percentage.toFixed(2));

    const ratingCount = document.createElement('span');
    ratingCount.classList.add('rating-count');
    ratingCount.textContent = rating.count;

    progressContainer.appendChild(progressBar);
    ratingBar.appendChild(ratingLabel);
    ratingBar.appendChild(progressContainer);
    ratingBar.appendChild(ratingCount);
    ratingBarsContainer.appendChild(ratingBar);
});


const serviceRatingBarsContainer = document.getElementById('serviceRatingBarsContainer');

serviceRatingData.forEach(rating => {
    const percentage = (rating.count / serviceTotalResponses) * 100;

    const ratingBar = document.createElement('div');
    ratingBar.classList.add('rating-bar');

    const ratingLabel = document.createElement('span');
    ratingLabel.classList.add('rating-label');
    ratingLabel.textContent = rating.label;

    const progressContainer = document.createElement('div');
    progressContainer.classList.add('progress-container');

    const progressBar = document.createElement('div');
    progressBar.classList.add('progress-bar');
    progressBar.style.width = `${percentage.toFixed(2)}%`;
    progressBar.style.backgroundColor = rating.color;
    progressBar.setAttribute('aria-valuenow', percentage.toFixed(2));

    const ratingCount = document.createElement('span');
    ratingCount.classList.add('rating-count');
    ratingCount.textContent = rating.count;

    progressContainer.appendChild(progressBar);
    ratingBar.appendChild(ratingLabel);
    ratingBar.appendChild(progressContainer);
    ratingBar.appendChild(ratingCount);
    serviceRatingBarsContainer.appendChild(ratingBar);
});

const expectationRatingBarsContainer = document.getElementById('expectationRatingBarsContainer');

expectationRatingData.forEach(rating => {
    const percentage = (rating.count / expectationTotalResponses) * 100;

    const ratingBar = document.createElement('div');
    ratingBar.classList.add('rating-bar');

    const ratingLabel = document.createElement('span');
    ratingLabel.classList.add('rating-label');
    ratingLabel.textContent = rating.label;

    const progressContainer = document.createElement('div');
    progressContainer.classList.add('progress-container');

    const progressBar = document.createElement('div');
    progressBar.classList.add('progress-bar');
    progressBar.style.width = `${percentage.toFixed(2)}%`;
    progressBar.style.backgroundColor = rating.color;
    progressBar.setAttribute('aria-valuenow', percentage.toFixed(2));

    const ratingCount = document.createElement('span');
    ratingCount.classList.add('rating-count');
    ratingCount.textContent = rating.count;

    progressContainer.appendChild(progressBar);
    ratingBar.appendChild(ratingLabel);
    ratingBar.appendChild(progressContainer);
    ratingBar.appendChild(ratingCount);
    expectationRatingBarsContainer.appendChild(ratingBar);
});

const bayesContainers = {
    like: document.getElementById('likeContainer'),
    improve: document.getElementById('improveContainer'),
    comment: document.getElementById('commentContainer')
};

function createBayesRow(container, label, percentage, color) {
    const row = document.createElement('div');
    row.classList.add('nps-row');

    const emoji = document.createElement('span');
    emoji.classList.add('nps-emoji');
    emoji.textContent = label;

    const progressContainer = document.createElement('div');
    progressContainer.classList.add('nps-progress-container');

    const progressBar = document.createElement('div');
    progressBar.classList.add('nps-progress-bar');
    progressBar.style.width = `${percentage.toFixed(2)}%`;
    progressBar.style.backgroundColor = color;

    const percentageText = document.createElement('span');
    percentageText.classList.add('rating-count')

    percentageText.textContent = `${percentage.toFixed(2)}%`;

    progressContainer.appendChild(progressBar);
    row.appendChild(emoji);
    row.appendChild(progressContainer);
    row.appendChild(percentageText);

    container.appendChild(row);
}

function displayBayesData(key, data) {
    const container = bayesContainers[key];
    container.innerHTML = ''; // Clear previous content

    const labels = {
        positive: '😍',
        neutral: '😐',
        negative: '☹️'
    };
    const colors = {
        positive: '#5cb85c',
        neutral: '#ffb300',
        negative: '#d9534f'
    };

    for (const [rating, percentage] of Object.entries(data)) {
        createBayesRow(container, labels[rating], percentage, colors[rating]);
    }
}

displayBayesData('like', bayesData.like);
displayBayesData('improve', bayesData.improve);
displayBayesData('comment', bayesData.comment);

</script>

    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->

    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script> 
       <script src="assets/js/custom.js"></script>
</body>
</html>
