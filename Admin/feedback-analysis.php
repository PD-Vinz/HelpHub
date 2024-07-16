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
