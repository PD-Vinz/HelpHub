<?php
require_once('bdd.php');

session_start();

if (isset($_SESSION['admin_number']))
$id = $_SESSION['admin_number'];

$pdoUserQuery = "SELECT * FROM mis_employees WHERE admin_number = :number";
$pdoResult = $bdd->prepare($pdoUserQuery);
$pdoResult->bindParam(':number', $id);
$pdoResult->execute();
$Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

$U_T = $Data ? $Data['user_type'] : null;

$events = array();
$privacy = '';
if ($U_T == 'Administrator' || $U_T == 'Staff') {
    $sql = "SELECT id, title, description, start, end, color, privacy FROM events";
    $req = $bdd->prepare($sql);
    $req->execute();
    $events = $req->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sql = "SELECT id, title, description, start, end, color, privacy FROM events WHERE privacy = 'public'";
    $req = $bdd->prepare($sql);
    $req->execute();
    $events = $req->fetchAll(PDO::FETCH_ASSOC);
}
?>


		<link href="../FullCalendar-BS3-PHP-MySQL-master/css/bootstrap.min.css" rel="stylesheet">
		
		<!-- FullCalendar -->
		<link href='../FullCalendar-BS3-PHP-MySQL-master/css/fullcalendar.css' rel='stylesheet' />
								

		<!-- Custom CSS -->
		<style>
		
		#calendar {
			max-width: 800px;
		}
		.col-centered{
			float: none;
			margin: 0 auto;
		}
		</style>





		<!-- Page Content -->
		<div class="container">

			<div class="row">
				<div class="col-lg-12 text-center">
				<br><br>
					<div id="calendar" class="col-centered">
					</div>
				</div>
				
			</div>
			<!-- /.row -->
			
			<!-- Modal -->
			<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				<form class="form-horizontal" method="POST" action="addEvent.php">
				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Add Event</h4>
				</div>
				<div class="modal-body">
					
					<div class="form-group">
						<label for="title" class="col-sm-2 control-label">Title</label>
						<div class="col-sm-10">
						<input type="text" name="title" class="form-control" id="title" placeholder="Title" required>
						</div>
					</div>
					<div class="form-group">
    <label for="description" class="col-sm-2 control-label">Description</label>
    <div class="col-sm-10">
        <textarea name="description" class="form-control" id="description" rows="5" placeholder="Description" style="resize: none;"  ></textarea>
    </div>
</div>

					<div class="form-group">
						<label for="color" class="col-sm-2 control-label">Color</label>
						<div class="col-sm-10">
						<select name="color" class="form-control" id="color"  required>
							<option value="">Choose</option>
							<option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
							<option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
							<option style="color:#008000;" value="#008000">&#9724; Green</option>						  
							<option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
							<option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
							<option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
							<option style="color:#000;" value="#000">&#9724; Black</option>
							
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="start" class="col-sm-2 control-label">Start date</label>
						<div class="col-sm-10">
						<input type="text" name="start" class="form-control" id="start" readonly>
						</div>
					</div>
					<div class="form-group">
						<label for="end" class="col-sm-2 control-label">End date</label>
						<div class="col-sm-10">
						<input type="text" name="end" class="form-control" id="end" readonly>
						</div>
					</div>
                    <div class="form-group">
    <label for="privacy" class="col-sm-2 control-label">Privacy</label>
    <div class="col-sm-10">
        <label class="radio-inline">
            <input type="radio" name="privacy" value="public" required> Public
        </label>
        <label class="radio-inline">
            <input type="radio" name="privacy" value="private" required> Private
        </label>
    </div>
</div>

				</div>  
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save changes</button>
				</div>
				</form>
				</div>
			</div>
			</div> 
			
			
			
			<!-- Modal -->
			<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" action="editEventTitle.php">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Event</h4>
                </div>
                <div class="modal-body">
                    <?php if ($U_T != 'Administrator') { ?>
                        <style>
                            .form-control {
                                pointer-events: none;
                                opacity: 0.5;
                            }
                        </style>
                    <?php } ?>
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-10">
                            <input type="text" name="title" class="form-control" id="title" placeholder="Title" <?php if ($U_T != 'Administrator') { ?>disabled<?php } ?> required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea name="description" class="form-control" id="description" rows="5" placeholder="Description" style="resize: none;" <?php if ($U_T != 'Administrator') { ?>disabled<?php } ?> ></textarea>
                        </div>
                    </div>

					<?php if ($U_T == 'Administrator') { ?>
                    <div class="form-group" >
                        <label for="color" class="col-sm-2 control-label">Color</label>
                        <div class="col-sm-10">
                            <select name="color" class="form-control" id="color" required>
                                <option value="">Choose</option>
                                <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
                                <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
                                <option style="color:#008000;" value="#008000">&#9724; Green</option>						  
                                <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
                                <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
                                <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
                                <option style="color:#000;" value="#000">&#9724; Black</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
    <label for="privacy" class="col-sm-2 control-label">Privacy</label>
    <div class="col-sm-10">
        <?php
        if ($privacy == 'public') {
            echo '<input type="radio" class="radio-inline" name="privacy" value="public" checked required> Public';
            echo '<input type="radio" class="radio-inline" name="privacy" value="private" required> Private';
        } else {
            echo '<input type="radio" class="radio-inline" name="privacy" value="public" required> Public';
            echo '<input type="radio" class="radio-inline" name="privacy" value="private" checked required> Private';
        }
        ?>
    </div>
</div>     







					<?php } ?>
                    <div class="form-group"  <?php if ($U_T != 'Administrator') { ?>hidden<?php } ?>> 
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label class="text-danger"><input type="checkbox"  name="delete"> Delete event</label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" class="form-control" id="id" <?php if ($U_T != 'Administrator') { ?>disabled<?php } ?>>
                </div>
                <div class="modal-footer" >
                    <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
					<?php if ($U_T == 'Administrator') { ?>
        <button type="submit" class="btn btn-primary">Save changes</button>
    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>

		</div>
		<!-- /.container -->

		<!-- jQuery Version 1.11.1 -->
		<script src="../FullCalendar-BS3-PHP-MySQL-master/js/jquery.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="../FullCalendar-BS3-PHP-MySQL-master/js/bootstrap.min.js"></script>
		
		<!-- FullCalendar -->
		<script src='../FullCalendar-BS3-PHP-MySQL-master/js/moment.min.js'></script>
		<script src='../FullCalendar-BS3-PHP-MySQL-master/js/fullcalendar.min.js'></script>
		
		<script>
var jsVariable = "<?php echo $U_T; ?>";
var eventsData = <?php echo json_encode($events); ?>; // JSON encode the events data

if(jsVariable === "Administrator") {
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next',
                center: 'title',
                right: 'today'
            },
            defaultDate: new Date(),
            editable: true,
            eventLimit: true,
            selectable: true,
            events: eventsData, // Use the JSON data directly

            select: function(start, end) {
                $('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD'));
                $('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD'));
                $('#ModalAdd').modal('show');
            },

            eventRender: function(event, element) {
    element.bind('dblclick', function() {
        $('#ModalEdit #id').val(event.id);
        $('#ModalEdit #title').val(event.title);
        $('#ModalEdit #description').val(event.description);
        $('#ModalEdit #color').val(event.color);
        $('#ModalEdit input[name="privacy"][value="' + event.privacy + '"]').prop('checked', true); // Set the privacy value
        $('#ModalEdit').modal('show');
    });
},      

            eventDrop: function(event) {
                edit(event);
            },

            eventResize: function(event) {
                edit(event);
            }
        });

        function edit(event) {
            var start = event.start.format('YYYY-MM-DD');
            var end = event.end ? event.end.format('YYYY-MM-DD') : start;

            var Event = [event.id, start, end];

            $.ajax({
                url: 'editEventDate.php',
                type: "POST",
                data: { Event: Event },
                success: function(rep) {
                    if(rep !== 'OK') {
                        alert('Could not be saved. Try again.'); 
                    }
                }
            });
        }
    });
}else{$(document).ready(function() {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next',
                center: 'title',
                right: 'today'
            },
            defaultDate: new Date(),
            editable: true,
            eventLimit: true,
            selectable: false,
            events: eventsData, // Use the JSON data directly

            select: function(start, end) {
                $('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD'));
                $('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD'));
                $('#ModalAdd').modal('show');
            },

            eventRender: function(event, element) {
                element.bind('dblclick', function() {
                    $('#ModalEdit #id').val(event.id);
                    $('#ModalEdit #title').val(event.title);
                    $('#ModalEdit #description').val(event.description);
                    $('#ModalEdit #color').val(event.color);
                    $('#ModalEdit').modal('show');
                });
            },

            eventDrop: function(event) {
                edit(event);
            },

            eventResize: function(event) {
                edit(event);
            }
        });

        function edit(event) {
            var start = event.start.format('YYYY-MM-DD');
            var end = event.end ? event.end.format('YYYY-MM-DD') : start;

            var Event = [event.id, start, end];

            $.ajax({
                url: 'editEventDate.php',
                type: "POST",
                data: { Event: Event },
                success: function(rep) {
                    if(rep !== 'OK') {
                        alert('Could not be saved. Try again.'); 
                    }
                }
            });
        }
    });}
</script>

	
