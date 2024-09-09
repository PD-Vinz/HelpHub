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


    $sql = "SELECT id, event_date, event_description, event_title FROM tb_calendar";
    $req = $pdoConnect->prepare($sql);
    $req->execute();
    $events = $req->fetchAll(PDO::FETCH_ASSOC);

    
    $query = $pdoConnect->prepare("SELECT system_name, short_name, system_logo, system_cover FROM settings WHERE id = :id");
    $query->execute(['id' => 1]);
    $Datas = $query->fetch(PDO::FETCH_ASSOC);
    $sysName = $Datas['system_name'] ?? '';
    $shortName = $Datas['short_name'] ?? '';
    $systemLogo = $Datas['system_logo'];
    $systemCover = $Datas['system_cover'];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newSysName = $_POST['name'];
        $newShortName = $_POST['short_name'];
    
        try {
            $updateQuery = $pdoConnect->prepare("UPDATE settings SET system_name = :system_name, short_name = :short_name WHERE id = :id");
            $updateQuery->execute([
                'system_name' => $newSysName,
                'short_name' => $newShortName,
                'id' => 1 
            ]);
    
            header('Location: settings.php');
        } catch (PDOException $e) {
            // Error handling
            echo "Error updating data: " . $e->getMessage();
        }
    }


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
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                    <div class="col-md-12">
                        
                     <h2>Settings</h2>   
                     <hr>
                     </div>
                        <form method="post" id="system-frm">
			<div class="col-md-6">
			<div class="form-group" >
				<label for="name" class="control-label">System Name</label>
				<input type="text" class="form-control form-control-sm" name="name" id="system_name" value="<?php echo htmlspecialchars($sysName); ?>">
			</div>
			<div class="form-group">
				<label for="short_name" class="control-label">System Short Name</label>
				<input type="text" class="form-control form-control-sm" name="short_name" id="short_name" value="<?php echo htmlspecialchars($shortName); ?>">
			</div>
			<!-- <div class="form-group">
				<label for="content[about_us]" class="control-label">About Us</label>
				<textarea type="text" class="form-control form-control-sm summernote" name="content[about_us]" id="about_us"></textarea>
			</div> -->
			<div class="form-group">
    <label for="" class="control-label">System Logo</label><br>
   
<img src="../img/Logo.png" class="avatar img-circle img-thumbnail" alt="avatar" style="height: 150px; width: 150px; "><br><br>
<br><br>
 <a href=".."><button type="button" class="btn btn-primary">Choose file</button></a><br>
</div>  
</div>
<div class="col-md-6">
<div class="form-group">
    <label for="" class="control-label">Cover Photo</label>
</div>
<div class="form-group d-flex justify-content-center">
    <img src="../img/background.png " alt="" id="cimg2" class="img-fluid img-thumbnail"> <a href=".."><br>
    <br>
    <button type="button" class="btn btn-primary">Choose file</button></a>
</div>
</form><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#confirmsubmit">Update</button>
            
</div> 
 

                    </div>
                  <!-- Button to trigger the modal -->

<!-- Modal -->
<div class="modal fade" id="confirmsubmit">
    <div class="modal-dialog3">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                Save system settings?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                <!-- Confirm button that submits the form -->
                <button type="submit" class="btn btn-primary" form="system-frm">Confirm</button>
            </div>
        </div>
    </div>
</div>


                 <br>
                   
                    <div class="wrapper col-md-5">

        <h1>Calendar</h1>
            <div>
                 
            <div id="event-section">
    <h3>Add Event</h3>
 
    <input type="date" style="width:100%;" class="form-control form-control-sm" id="eventDate">
    <input type="text" style="width:100%;" class="form-control form-control-sm" id="eventTitle" placeholder="Event Title">
    <input type="text" style="width:100%;" class="form-control form-control-sm" id="eventDescription" placeholder="Event Description">
    <button id="addEvent" class="btn btn-primary"onclick="addEvent()">Add</button>
</div>


                <div id="reminder-section">
                    <h3>Reminders</h3><table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                                        <tr>
                                            <th>Date</th> 
                                            <th>Event</th>
                                            <th>Description</th>
                                            <th>Option</th>
                                    
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                        <tr>
                                        <?php foreach ($events as $event): ?>
                                             <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                                             <td><?php echo htmlspecialchars($event['event_title']); ?></td>
                                            <td><?php echo htmlspecialchars($event['event_description']); ?></td>
                                           
                                            <td style="width:8%;"> <button class=" btn btn-primary delete-event"
                                onclick="deleteEvent(1)">
                                Delete
                            </button></td>
                            
                                        </tr><?php endforeach; ?>
                                    </tbody>

                    </table>
                    <!-- List to display reminders -->


                  
                </div>
            </div>
                    </div>
            <!-- /. Calendar  -->   
          
            <div class="col-md-7">
            <br><br>
                 <div class="wrapper">
		
			<div id="right">
				
				<div class="button-container-calendar">
					<button id="previous" class="col-md-1"
							onclick="previous()">
						‹
					</button>
          <h3 id="monthAndYear" class="col-md-10"></h3>
					<button id="next" class="col-md-1"
							onclick="next()">
						›
					</button>
				</div>
				<table class="table-calendar"
					id="calendar"
					data-lang="en">
					<thead id="thead-month"></thead>
					
					<tbody id="calendar-body"></tbody>
				</table>
				<div class="footer-container-calendar">
					<label for="month">Jump To: </label>
					<!-- Dropdowns to select a specific month and year -->
					<select id="month" onchange="jump()">
						<option value=0>Jan</option>
						<option value=1>Feb</option>
						<option value=2>Mar</option>
						<option value=3>Apr</option>
						<option value=4>May</option>
						<option value=5>Jun</option>
						<option value=6>Jul</option>
						<option value=7>Aug</option>
						<option value=8>Sep</option>
						<option value=9>Oct</option>
						<option value=10>Nov</option>
						<option value=11>Dec</option>
					</select>
					<!-- Dropdown to select a specific year -->
					<select id="year" onchange="jump()"></select>
				</div>
			</div>
		
	</div>
	<!-- Include the JavaScript file for the calendar functionality -->
	<script src="./script.js"></script>
  
                 </div>     
        
    </div>
    <!-- Include the JavaScript file for the calendar functionality -->
    <script src="./script.js"></script>
                    <div class="card-footer">
			<div class="col-md-12">
      <hr />
				<div class="row" style=" padding-left: 15px; padding-bottom: 15px;">
          
				
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
	$(function(){
		$('.select2').select2({
			width:'resolve'
		})
	})

	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
  function displayImg2(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg2').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	

</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
   <script>
    //--------------------DATEPICKER---------------------
document.addEventListener("DOMContentLoaded", function() {
                flatpickr("#expiry_date", {
                    dateFormat: "Y-m-d" // Specify the date format
                });
            });
//--------------------DATEPICKER---------------------     
          </script>
          <script>
            function addEvent() {
    const eventDate = document.getElementById('eventDate').value;
    const eventTitle = document.getElementById('eventTitle').value;
    const eventDescription = document.getElementById('eventDescription').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_event.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (this.status === 200) {
            alert(this.responseText);
            // Optionally, refresh the event list here
        }
    };

    xhr.send(`eventDate=${eventDate}&eventTitle=${eventTitle}&eventDescription=${eventDescription}`);
}

document.addEventListener('DOMContentLoaded', () => {
    fetchReminders();
});

function fetchReminders() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_reminders.php', true);

    xhr.onload = function() {
        if (this.status === 200) {
            const reminders = JSON.parse(this.responseText);
            const reminderList = document.getElementById('reminderList');
            reminderList.innerHTML = ''; // Clear the list before adding new items

            reminders.forEach(reminder => {
                const li = document.createElement('li');
                li.dataset.eventId = reminder.id;
                li.innerHTML = `
                    <strong>${reminder.event_title}</strong>
                    - ${reminder.event_description} on ${reminder.event_date}
                    <button class="delete-event" onclick="deleteEvent(${reminder.id})">Delete</button>
                `;
                reminderList.appendChild(li);
            });
        }
    };

    xhr.send();
}

document.addEventListener("DOMContentLoaded", function() {
    const events = <?php echo json_encode($events); ?>;

    events.forEach(event => {
        const eventDate = new Date(event.event_date);
        const formattedDate = eventDate.toISOString().split('T')[0];
        const dayCell = document.querySelector(`[data-date="${formattedDate}"]`);

        if (dayCell) {
            const eventElement = document.createElement('div');
            eventElement.className = 'event';
            eventElement.innerHTML = `
                <strong>${event.event_title}</strong>
                <p>${event.event_description}</p>
            `;
            dayCell.appendChild(eventElement);
        }
    });
});


          </script>
          
          <script>
    // Prevent form submission on Enter key press
    document.getElementById('system-frm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission
            $('#confirmsubmit').modal('show'); // Show the modal
        }
    });

    // Prevent form submission on Enter key press in input fields
    document.querySelectorAll('#system-frm input').forEach(input => {
        input.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent form submission
                $('#confirmsubmit').modal('show'); // Show the modal
            }
        });
    });
</script>   
   



</body>
</html>
