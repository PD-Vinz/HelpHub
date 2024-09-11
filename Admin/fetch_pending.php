<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
include_once("../connection/conn.php");
$pdoConnect = connection();

// Start session if needed
session_start();

// Verify if admin is logged in (if required)
if (!isset($_SESSION["admin_number"])) {
    echo "Session expired. Please log in again.";
    exit();
}

try {
    // Fetch ticket data with status 'Pending'
    $pdoQuery = "SELECT * FROM tb_tickets WHERE status = 'Pending'";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute();

    // Check if there are pending tickets
    if ($pdoResult->rowCount() > 0) {
        echo '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="width:10%">Ticket ID</th>';
        echo '<th style="width:15%">Date Submitted</th>';
        echo '<th>Name</th>';
        echo '<th>Issue(s)</th>';
        echo '<th style="width:25%">Descriptions</th>';
        echo '<th style="width:8%">Details</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Loop through and output each ticket row
        while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
            $ticket_id = htmlspecialchars($row['ticket_id']);
            $created_date = htmlspecialchars($row['created_date']);
            $full_name = htmlspecialchars($row['full_name']);
            $issue = htmlspecialchars($row['issue']);
            $description = htmlspecialchars($row['description']);
            $screenshot = base64_encode($row['screenshot']);

            // Shorten the description if needed
            $max_length = 25;
            $short_description = strlen($description) > $max_length ? htmlspecialchars(substr($description, 0, $max_length)) . '...' : $description;

            echo "<tr class='odd gradeX'>";
            echo "<td>$ticket_id</td>";
            echo "<td>$created_date</td>";
            echo "<td>$full_name</td>";
            echo "<td>$issue</td>";
            echo "<td>$short_description</td>";
            echo "<td>
                <div class='panel-body-ticket'>
                    <button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModal{$ticket_id}'>
                        View Details
                    </button>
                </div>
            </td>";
            echo "</tr>";

            // Modal content for each ticket
            echo "<div class='modal fade' id='myModal{$ticket_id}'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                            <h4 class='modal-title'>Pending Ticket</h4>
                        </div>
                        <div class='modal-body'>
                            <div class='row'>
                                <div class='col-md-6'>
                                    <h3>Ticket Details</h3>
                                    <form role='form'>
                                        <div class='form-group'>
                                            <label>Ticket ID</label>
                                            <input class='form-control' value='$ticket_id' disabled/>
                                        </div>
                                        <div class='form-group'>
                                            <label>Issue/Problem</label>
                                            <input class='form-control' value='$issue' disabled/>
                                        </div>
                                        <div class='form-group'>
                                            <label>Description</label>
                                            <textarea class='form-control' disabled style='height:148px; resize:none; overflow:auto;'>$description</textarea>
                                        </div>
                                        <div class='form-group'>
                                            <label>Screenshot</label>
                                            <a href='view_image.php?id=$ticket_id' target='_blank'>
                                                <img src='data:image/jpeg;base64,$screenshot' alt='Screenshot' class='img-fluid'>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <a href='#' data-dismiss='modal' class='btn'>Back</a>
                            <a data-toggle='modal' href='#myModalTransfer{$ticket_id}' class='btn btn-primary'>Transfer</a>
                            <a data-toggle='modal' href='#myModalReturn{$ticket_id}' class='btn btn-primary'>Return</a>
                            <a data-toggle='modal' href='#myModalClose{$ticket_id}' class='btn btn-primary'>Resolve</a>
                        </div>
                    </div>
                </div>
            </div>";
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo "<tr><td colspan='6'>No pending tickets found.</td></tr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
