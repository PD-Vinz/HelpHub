<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start();

if (!isset($_SESSION["admin_number"])) {
    header("Location: ../index.php");
    exit();
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
        echo "No student found with the given student number.";
    }

    // Handle form submission for system settings
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $newSysName = $_POST['name'];
        $newShortName = $_POST['short_name'];

        // Handle file upload
        if (isset($_FILES['system_logo']) && $_FILES['system_logo']['error'] === UPLOAD_ERR_OK) {
            $newLogo = file_get_contents($_FILES['system_logo']['tmp_name']);
        } else {
            $query = $pdoConnect->prepare("SELECT system_logo FROM settings WHERE id = :id");
            $query->execute(['id' => 1]);
            $Datas = $query->fetch(PDO::FETCH_ASSOC);
            $newLogo = $Datas['system_logo'];
        }

        try {
            $updateQuery = $pdoConnect->prepare("UPDATE settings SET system_name = :system_name, short_name = :short_name, system_logo = :system_logo WHERE id = :id");
            $updateQuery->execute([
                'system_name' => $newSysName,
                'short_name' => $newShortName,
                'system_logo' => $newLogo,
                'id' => 1
            ]);

            header('Location: settings.php');
            exit;
        } catch (PDOException $e) {
            echo "Error updating data: " . $e->getMessage();
        }
    }

    // Handle toggle for accept_tickets
    if (isset($_POST['accept_tickets'])) {
        $new_status = $_POST['accept_tickets'] === 'on' ? 'on' : 'off';

        $stmt = $pdoConnect->prepare("UPDATE settings SET accept_tickets = ? WHERE id = 1");
        $stmt->execute([$new_status]);

        // Refresh the page to reflect the updated state
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Fetch settings
    $query = $pdoConnect->prepare("SELECT system_name, short_name, system_logo, accept_tickets FROM settings WHERE id = :id");
    $query->execute(['id' => 1]);
    $Datas = $query->fetch(PDO::FETCH_ASSOC);
    $sysName = $Datas['system_name'] ?? '';
    $shortName = $Datas['short_name'] ?? '';
    $S_L = $Datas['system_logo'];
    $accept_tickets = $Datas['accept_tickets'];
}

// Display image if it exists
$S_LBase64 = '';
if (!empty($S_L)) {
    $base64Image = base64_encode($S_L);
    $imageType = 'image/png'; // Default MIME type
    $S_LBase64 = 'data:' . $imageType . ';base64,' . $base64Image;
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
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
                    
                   
                     <div class="col-md-12 text-center "> 
    <h3>Accept Tickets?</h3>
    <form id="acceptTicketsForm" method="POST" action="">
        <input type="hidden" name="accept_tickets" value="<?php echo $accept_tickets === 'on' ? 'off' : 'on'; ?>">

        <!-- OFF label, always visible -->
        <span>OFF</span>

        <!-- Slider -->
        <label class="switch">
            <input type="checkbox" id="acceptTicketsCheckbox" <?php echo $accept_tickets === 'on' ? 'checked' : ''; ?>>
            <span class="slider"></span>
        </label>

        <!-- ON label, always visible -->
        <span>ON</span>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmsubmit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog3">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  onclick="window.location.reload();">×</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body" id="modalBodyText">
               <!-- The text will be updated dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"  onclick="window.location.reload();">Cancel</button>
                <!-- Confirm button that submits the form -->
                <button id="confirmSubmitButton" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>



<form method="post" id="system-frm" role="form" enctype="multipart/form-data">
    <div class="col-md-12"><hr> 
    <h3>System settings</h3> 
        <div class="form-group">
            <label for="name" class="control-label">System Name</label>
            <input type="text" class="form-control form-control-sm" name="name" id="system_name" value="<?php echo htmlspecialchars($sysName); ?>">
            <div id="name-notification" style="color: red; display: none;">System Name cannot exceed 20 characters</div>
        </div>
        <div class="form-group">
            <label for="short_name" class="control-label">System Short Name</label>
            <input type="text" class="form-control form-control-sm" name="short_name" id="short_name" value="<?php echo htmlspecialchars($shortName); ?>">
            <div id="notification" style="color: red; display: none;">Short Name cannot exceed 10 characters</div>
        </div>
        <div class="form-group">
    <label for="" class="control-label">System Logo</label><br>
    <div class="avatar2" id="avatar">
        <div id="preview">
            <img src="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" id="avatar-image" class="avatar_img" alt="No Image">
        </div>
        <div class="avatar_upload">
            <label class="upload_label">Choose
                <input type="file" id="upload" name="system_logo" accept="image/*">
            </label>
        </div>
    </div><BR>
</div><div class="modal-footer">
<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#confirmsubmits">Update</button>
            </div>

    </div>
   <!-- <div class="col-md-6">
        <div class="form-group">
            <label for="" class="control-label">Cover Photo</label>
        </div>
        <div class="form-group d-flex justify-content-center">
            <img src="../img/background.png" alt="" id="cimg2" class="img-fluid img-thumbnail">
            <a href=".."><br><br>
                <button type="button" class="btn btn-primary">Choose file</button>
            </a>
        </div>
    </div>-->
  
</form>



 
 
                    </div>
                  <!-- Button to trigger the modal -->

<!-- Modal -->
<div class="modal fade" id="confirmsubmits">
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
                <button type="submit" class="btn btn-primary" name="update" form="system-frm">Confirm</button>
            </div>
        </div>
    </div>
</div>







                 <br>
                 <hr>
                   

	<!-- Include the JavaScript file for the calendar functionality -->
	<script src="./script.js"></script>   
                 </div> 
    </div>

    <?php include '../footer.php' ?>
                    </div>
                </div>
                 <!-- /. ROW  -->
                 
               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTTOM TO REDUCE THE LOAD TIME-->
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
            // Get references to elements
const checkbox = document.getElementById('acceptTicketsCheckbox');
const modalBodyText = document.getElementById('modalBodyText');

// Prevent the form from submitting when the checkbox is clicked
checkbox.addEventListener('change', function (e) {
    // Prevent the default form submission
    e.preventDefault();

    // Update the modal text based on the checkbox state
    if (checkbox.checked) {
        modalBodyText.textContent = 'Turning this on will allow users to create and submit tickets.';
    } else {
        modalBodyText.textContent = 'Turning this off will prevent users from creating and submitting tickets.';
    }

    // Open the modal
    $('#confirmsubmit').modal('show');
});

// Handle the modal confirm button click
document.getElementById('confirmSubmitButton').addEventListener('click', function () {
    // Submit the form after the user confirms
    document.getElementById('acceptTicketsForm').submit();
});

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


//character limit notifier//
document.getElementById('short_name').addEventListener('input', function() {
    var shortNameInput = this.value;
    var notification = document.getElementById('notification');
    
    if (shortNameInput.length > 10) {
        notification.style.display = 'block';
    } else {
        notification.style.display = 'none';
    }
});


document.getElementById('system_name').addEventListener('input', function() {
    var systemNameInput = this.value;
    var nameNotification = document.getElementById('name-notification');
    
    if (systemNameInput.length > 20) {
        nameNotification.style.display = 'block';
    } else {
        nameNotification.style.display = 'none';
    }
});

//!character limit notifier//
          </script>
          
          <script>
    // Prevent form submission on Enter key press
    document.getElementById('system-frm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission
            $('#confirmsubmits').modal('show'); // Show the modal
        }
    });

    // Prevent form submission on Enter key press in input fields
    document.querySelectorAll('#system-frm input').forEach(input => {
        input.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent form submission
                $('#confirmsubmits').modal('show'); // Show the modal
            }
        });
    });
</script>   
<script>// Prevent the form from submitting when the checkbox is clicked
document.getElementById('acceptTicketsCheckbox').addEventListener('change', function (e) {
    // Prevent the default form submission
    e.preventDefault();
    
    // Open the modal
    $('#confirmsubmit').modal('show');

    
});

// Handle the modal confirm button click
document.getElementById('confirmSubmitButton').addEventListener('click', function () {
    // Submit the form after the user confirms
    document.getElementById('acceptTicketsForm').submit();
});
</script>
<script>
        document.getElementById('imageInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const maxSize = 6 * 1024 * 1024; // 6MB in bytes
            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];

            if (file) {
                if (file.size > maxSize) {
                    document.getElementById('sizeError').textContent = 'File size exceeds 6MB limit.';
                    event.target.value = ''; // Reset the file input
                    return;
                } else {
                    document.getElementById('sizeError').textContent = '';
                }

                if (!allowedTypes.includes(file.type)) {
                    document.getElementById('typeError').textContent = 'Only PNG, JPG, and JPEG files are allowed.';
                    event.target.value = ''; // Reset the file input
                    return;
                } else {
                    document.getElementById('typeError').textContent = '';
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.onload = function() {
                        const preview = document.getElementById('imagePreview');
                        preview.innerHTML = '';
                        preview.appendChild(img);
                        // Set preview size to match image size
                        //preview.style.width = img.naturalWidth + 'px';
                        //preview.style.height = img.naturalHeight + 'px';
                    };
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('imagePreview').innerHTML = '<img src="assets/pic/pics.jpg" alt="" id="cimg2" class="img-thumbnail">';
            }
        });

        function adjustHeight() {
        const textarea = document.getElementById('issue-description');
        textarea.style.height = 'auto'; // Reset height to auto to shrink if needed
        textarea.style.height = textarea.scrollHeight + 'px'; // Adjust height to fit the content
    }

    function updateRemainingCharacters() {
        const textarea = document.getElementById('issue-description');
        const remainingChars = 255 - textarea.value.length;
        document.getElementById('remaining-characters').textContent = `${remainingChars} characters remaining`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('issue-description');
        textarea.addEventListener('input', function() {
            adjustHeight();
            updateRemainingCharacters();
        });

        // Initial adjustment in case there's already content
        adjustHeight();
        updateRemainingCharacters();
    });




    

    </script>
<script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });
    </script>
    <script src="../user/assets/js/custom.js"></script>
<script type="text/javascript" src="post.js"></script>


</body>
</html>
