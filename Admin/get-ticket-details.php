<?php
// File: get_ticket_details.php

include_once("../connection/conn.php");
$pdoConnect = connection();

header('Content-Type: application/json');

if (isset($_GET['ticket_id']) && isset($_GET['status'])) {
    $ticket_id = $_GET['ticket_id'];
    $status = $_GET['status'];

    try {
        // Fetch ticket details
        $query = "SELECT * FROM tb_tickets WHERE ticket_id = :ticket_id";
        $stmt = $pdoConnect->prepare($query);
        $stmt->bindParam(':ticket_id', $ticket_id);
      
        $stmt->execute();
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ticket) {
            // Generate HTML based on the status
            $html = '';
            switch ($status) {
                case 'Pending':
                    $html = generatePendingHTML($ticket);
                    break;
                case 'Processing':
                    $html = generateProcessingHTML($ticket);
                    break;
                case 'Returned':
                    $html = generateReturnedHTML($ticket);
                    break;
                case 'Resolved':
                    $html = generateResolvedHTML($ticket);
                    break;
                default:
                    $html = "<p>No details available for this status.</p>";
            }
            echo json_encode(['success' => true, 'html' => $html, 'status' => $status, 'ticket_id' => $ticket_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ticket not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

function generatePendingHTML($ticket) {
    $screenshot = $ticket['screenshot'];
    
    $screenshotBase64 = base64_encode($screenshot);
    
    $html = "<div class='row'>";
    
    $html .= "<div class='col-md-12'>";
    $html .= "<h3>Ticket Details</h3>"; 
  
   
    $html .= "<div class='col-md-6'>";
    $html .= "<form role='form'>";
    $html .= "<div class='form-group'>";
            $html .= "<label>Ticket ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['ticket_id']) . " disabled/>";
         
        $html .= "</div>";
    $html .= "<div class='form-group'>";
            $html .= "<label>Issue/Problem  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['issue']) . " disabled/>";
            $html .= "</div>";

            $html .= "<div class='form-group'>";
            $html .= "<label>Description ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<textarea class='form-control' style='height:148px; resize:none; overflow:auto;' disabled>";
            $html .= htmlspecialchars($ticket['description']);
            $html .= "</textarea>";
            $html .= "</div>";
  
            $html .= "</div>";
          
            $html .= "<div class='col-md-6'>";
        // Screenshot
      if (isset( $screenshotBase64)) {
          $html .= "<p><strong>Screenshot:</strong></p>";
          $html .= "<a href='view_image.php?id=" . htmlspecialchars($ticket['ticket_id']) . "' target='_blank'>";
          $html .= "<img src='data:image/jpeg;base64," .  $screenshotBase64. "' alt='Screenshot' class='img-fluid'>";
          $html .= "</a>";
          }  
          $html .= "</div>";
       
    $html .= "</form>";
    $html .= "</div>";
    // User Information
    $html .= "<div class='col-md-12'>";
    $html .= "<hr>";
       $html .= "<h3>User Information</h3>"; 
       $html .= "<div class='col-md-6'>";

    


    $html .= "<form role='form'>";  

     $html .= " <div class='form-group'>";
      $html .= "<label>Full Name‎ ‎ ‎ ‎ ‎ </label>";
      $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['full_name']) . " disabled/>";
     $html .= " </div>";
  
     $html .= " <div class='form-group'>";
       $html .= "   <label>User ID‎ ‎ ‎ </label>";
        $html .= "  <input class='form-control' value=" . htmlspecialchars($ticket['user_id']) . " disabled/>";
    
      $html .= "</div>";
   
      $html .= "<div class='form-group'>";
      $html .= "<label>Email Address  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
      $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['email_address']) . " disabled/>";
      $html .= "</div>";

      $html .= "<div class='form-group'>";
          $html .= "<label>Gender ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['sex']) . " disabled/>";
       
      $html .= "</div>";

      $html .= "<div class='form-group'>";
          $html .= "<label>Age ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['age']) . " disabled/>";
       
          $html .= "</div>";
          $html .= "</div>";
      $html .= "<div class='col-md-6'>";
      $html .= "<div class='form-group'>";
       $html .= "   <label>College‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
        $html .= "  <input class='form-control' value=" . htmlspecialchars($ticket['department']) . " disabled/>";
    
      $html .= "</div>";


      if ($ticket['user_type'] === 'Student') {
   
      $html .= "<div class='form-group'>";
          $html .= "<label>Course‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['course']) . " disabled/>";
      $html .= "</div>";
      $html .= "<div class='form-group'>";
          $html .= "<label>Year & Section‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['year_section']) . " disabled/>";
      $html .= "</div>";
      }

      
      $html .= "<div class='form-group'>";
          $html .= "<label>Campus ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket ['campus']) . " disabled/>";
       
      $html .= "</div>";

  $html .= "</form>";      
  $html .= "</div>";

  $html .= "</div>";


 
  $html .= "</div>";
    return $html;
}

function generateProcessingHTML($ticket) {
    $screenshot = $ticket['screenshot'];
    
    $screenshotBase64 = base64_encode($screenshot);
    
    $html = "<div class='row'>";
    
    $html .= "<div class='col-md-12'>";
    $html .= "<h3>Ticket Details</h3>"; 
  
   
    $html .= "<div class='col-md-6'>";
    $html .= "<form role='form'>";
    $html .= "<div class='form-group'>";
            $html .= "<label>Ticket ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['ticket_id']) . " disabled/>";
         
        $html .= "</div>";
    $html .= "<div class='form-group'>";
            $html .= "<label>Issue/Problem  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['issue']) . " disabled/>";
            $html .= "</div>";
  
            $html .= "<div class='form-group'>";
            $html .= "<label>Description ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<textarea class='form-control' style='height:148px; resize:none; overflow:auto;' disabled>";
            $html .= htmlspecialchars($ticket['description']);
            $html .= "</textarea>";
            $html .= "</div>";
  
            $html .= "</div>";
          
            $html .= "<div class='col-md-6'>";
        // Screenshot
      if (isset( $screenshotBase64)) {
          $html .= "<p><strong>Screenshot:</strong></p>";
          $html .= "<a href='view_image.php?id=" . htmlspecialchars($ticket['ticket_id']) . "' target='_blank'>";
          $html .= "<img src='data:image/jpeg;base64," .  $screenshotBase64. "' alt='Screenshot' class='img-fluid'>";
          $html .= "</a>";
          }  
          $html .= "</div>";
       
    $html .= "</form>";
    $html .= "</div>";
    
    // User Information
    $html .= "<div class='col-md-12'>";$html .= "<hr>";
       $html .= "<h3>User Information</h3>"; 
       $html .= "<div class='col-md-6'>";

    


    $html .= "<form role='form'>";  

     $html .= " <div class='form-group'>";
      $html .= "<label>Full Name‎ ‎ ‎ ‎ ‎ </label>";
      $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['full_name']) . " disabled/>";
     $html .= " </div>";
  
     $html .= " <div class='form-group'>";
       $html .= "   <label>User ID‎ ‎ ‎ </label>";
        $html .= "  <input class='form-control' value=" . htmlspecialchars($ticket['user_id']) . " disabled/>";
    
      $html .= "</div>";
   

      $html .= "<div class='form-group'>";
      $html .= "<label>Email Address ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
      $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['user_id']) . " disabled/>";
      $html .= "</div>";

      
      $html .= "<div class='form-group'>";
          $html .= "<label>Gender ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['sex']) . " disabled/>";
       
      $html .= "</div>";

      $html .= "<div class='form-group'>";
          $html .= "<label>Age ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['age']) . " disabled/>";
       
          $html .= "</div>";
          $html .= "</div>";
      $html .= "<div class='col-md-6'>";
      $html .= "<div class='form-group'>";
       $html .= "   <label>College‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
        $html .= "  <input class='form-control' value=" . htmlspecialchars($ticket['department']) . " disabled/>";
    
      $html .= "</div>";


      if ($ticket['user_type'] === 'Student') {
   
      $html .= "<div class='form-group'>";
          $html .= "<label>Course‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['course']) . " disabled/>";
      $html .= "</div>";
      $html .= "<div class='form-group'>";
          $html .= "<label>Year & Section‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['year_section']) . " disabled/>";
      $html .= "</div>";
      }

      
      $html .= "<div class='form-group'>";
          $html .= "<label>Campus ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket ['campus']) . " disabled/>";
       
      $html .= "</div>";

  $html .= "</form>";      
  $html .= "</div>";

  $html .= "</div>";


 
  $html .= "</div>";
    return $html;
}

function generateResolvedHTML($ticket) {
    $screenshot = $ticket['screenshot'];
    
    $screenshotBase64 = base64_encode($screenshot);
    
    $html = "<div class='row'>";
    
    
    $html .= "<div class='col-md-4'>";
    $html .= "<h3>Closing Summary</h3>";
  
    $html .= "<form role='form'>";
    $html .= "<div class='form-group'>";
            $html .= "<label>Employee Name‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['employee']) . " disabled/>";
         
        $html .= "</div>";
        $html .= "<div class='form-group'>";
        $html .= "<label>Opened Date‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
        $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['opened_date']) . " disabled/>";
        $html .= "</div>";

        $html .= "<div class='form-group'>";
        $html .= "<label>Closed Date‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
        $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['finished_date']) . " disabled/>";
        $html .= "</div>";

        $html .= "<div class='form-group'>";
        $html .= "<label>Duration‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
        $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['duration']) . " disabled/>";
     
            $html .= "<div class='form-group'>";
            $html .= "<label>Resolution ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<textarea class='form-control' style='height:148px; resize:none; overflow:auto;' disabled>";
            $html .= htmlspecialchars($ticket['resolution']);
            $html .= "</textarea>";
            $html .= "</div>";
        // Screenshot
     
        $html .= "</div>";
    $html .= "</form>";
    $html .= "</div>";

    $html .= "<div class='col-md-4'>";
    $html .= "<h3>Ticket Details</h3>";
  
    $html .= "<form role='form'>";
    $html .= "<div class='form-group'>";
            $html .= "<label>Ticket ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['ticket_id']) . " disabled/>";
         
        $html .= "</div>";
    $html .= "<div class='form-group'>";
            $html .= "<label>Issue/Problem  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['issue']) . " disabled/>";
         
            $html .= "<div class='form-group'>";
            $html .= "<label>Description ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<textarea class='form-control' style='height:148px; resize:none; overflow:auto;' disabled>";
            $html .= htmlspecialchars($ticket['description']);
            $html .= "</textarea>";
            $html .= "</div>";
        // Screenshot
      if (isset( $screenshotBase64)) {
          $html .= "<p><strong>Screenshot:</strong></p>";
          $html .= "<a href='view_image.php?id=" . htmlspecialchars($ticket['ticket_id']) . "' target='_blank'>";
          $html .= "<img src='data:image/jpeg;base64," .  $screenshotBase64. "' alt='Screenshot' class='img-fluid'>";
          $html .= "</a>";
          }
        $html .= "</div>";
    $html .= "</form>";
    $html .= "</div>";
  
  
    // User Information
    $html .= "<div class='col-md-4'>";
    $html .= "<h3>User Information</h3>";
    


    $html .= "<form role='form'>";  
     $html .= " <div class='form-group'>";
      $html .= "<label>Full Name‎ ‎ ‎ ‎ ‎ </label>";
      $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['full_name']) . " disabled/>";
     $html .= " </div>";
  
     $html .= " <div class='form-group'>";
       $html .= "   <label>User ID‎ ‎ ‎ </label>";
        $html .= "  <input class='form-control' value=" . htmlspecialchars($ticket['user_number']) . " disabled/>";
    
      $html .= "</div>";
   
      $html .= "<div class='form-group'>";
       $html .= "   <label>College‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
        $html .= "  <input class='form-control' value=" . htmlspecialchars($ticket['department']) . " disabled/>";
    
      $html .= "</div>";


      if ($ticket['user_type'] === 'Student') {
   
      $html .= "<div class='form-group'>";
          $html .= "<label>Course‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['course']) . " disabled/>";
      $html .= "</div>";
      $html .= "<div class='form-group'>";
          $html .= "<label>Year & Section‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['year_section']) . " disabled/>";
      $html .= "</div>";
      }

      
      $html .= "<div class='form-group'>";
          $html .= "<label>Campus ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket ['campus']) . " disabled/>";
       
      $html .= "</div>";

      $html .= "<div class='form-group'>";
          $html .= "<label>Gender ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['sex']) . " disabled/>";
       
      $html .= "</div>";

      $html .= "<div class='form-group'>";
          $html .= "<label>Age ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['age']) . " disabled/>";
       
      $html .= "</div>";
  $html .= "</form>";      
  $html .= "</div>";

  $html .= "</div>";
    return $html;
}

function generateReturnedHTML($ticket) {
    $screenshot = $ticket['screenshot'];
    
    $screenshotBase64 = base64_encode($screenshot);
    
    $html = "<div class='row'>";
    
    
    $html .= "<div class='col-md-4'>";
    $html .= "<h3>Closing Summary</h3>";
  
    $html .= "<form role='form'>";
    $html .= "<div class='form-group'>";
            $html .= "<label>Employee Name‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['employee']) . " disabled/>";
         
        $html .= "</div>";
        $html .= "<div class='form-group'>";
        $html .= "<label>Opened Date‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
        $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['opened_date']) . " disabled/>";
        $html .= "</div>";

        $html .= "<div class='form-group'>";
        $html .= "<label>Closed Date‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
        $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['finished_date']) . " disabled/>";
        $html .= "</div>";

        $html .= "<div class='form-group'>";
        $html .= "<label>Duration‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
        $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['duration']) . " disabled/>";
     
            $html .= "<div class='form-group'>";
            $html .= "<label>Resolution ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<textarea class='form-control' style='height:148px; resize:none; overflow:auto;' disabled>";
            $html .= htmlspecialchars($ticket['resolution']);
            $html .= "</textarea>";
            $html .= "</div>";
        // Screenshot
     
        $html .= "</div>";
    $html .= "</form>";
    $html .= "</div>";

    $html .= "<div class='col-md-4'>";
    $html .= "<h3>Ticket Details</h3>";
  
    $html .= "<form role='form'>";
    $html .= "<div class='form-group'>";
            $html .= "<label>Ticket ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['ticket_id']) . " disabled/>";
         
        $html .= "</div>";
    $html .= "<div class='form-group'>";
            $html .= "<label>Issue/Problem  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['issue']) . " disabled/>";
         
            $html .= "<div class='form-group'>";
            $html .= "<label>Description ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
            $html .= "<textarea class='form-control' style='height:148px; resize:none; overflow:auto;' disabled>";
            $html .= htmlspecialchars($ticket['description']);
            $html .= "</textarea>";
            $html .= "</div>";
        // Screenshot
      if (isset( $screenshotBase64)) {
          $html .= "<p><strong>Screenshot:</strong></p>";
          $html .= "<a href='view_image.php?id=" . htmlspecialchars($ticket['ticket_id']) . "' target='_blank'>";
          $html .= "<img src='data:image/jpeg;base64," .  $screenshotBase64. "' alt='Screenshot' class='img-fluid'>";
          $html .= "</a>";
          }
        $html .= "</div>";
    $html .= "</form>";
    $html .= "</div>";
  
  
    // User Information
    $html .= "<div class='col-md-4'>";
    $html .= "<h3>User Information</h3>";
    


    $html .= "<form role='form'>";  
     $html .= " <div class='form-group'>";
      $html .= "<label>Full Name‎ ‎ ‎ ‎ ‎ </label>";
      $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['full_name']) . " disabled/>";
     $html .= " </div>";
  
     $html .= " <div class='form-group'>";
       $html .= "   <label>User ID‎ ‎ ‎ </label>";
        $html .= "  <input class='form-control' value=" . htmlspecialchars($ticket['user_number']) . " disabled/>";
    
      $html .= "</div>";
   
      $html .= "<div class='form-group'>";
       $html .= "   <label>College‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
        $html .= "  <input class='form-control' value=" . htmlspecialchars($ticket['department']) . " disabled/>";
    
      $html .= "</div>";


      if ($ticket['user_type'] === 'Student') {
   
      $html .= "<div class='form-group'>";
          $html .= "<label>Course‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['course']) . " disabled/>";
      $html .= "</div>";
      $html .= "<div class='form-group'>";
          $html .= "<label>Year & Section‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['year_section']) . " disabled/>";
      $html .= "</div>";
      }

      
      $html .= "<div class='form-group'>";
          $html .= "<label>Campus ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket ['campus']) . " disabled/>";
       
      $html .= "</div>";

      $html .= "<div class='form-group'>";
          $html .= "<label>Gender ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['sex']) . " disabled/>";
       
      $html .= "</div>";

      $html .= "<div class='form-group'>";
          $html .= "<label>Age ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>";
          $html .= "<input class='form-control' value=" . htmlspecialchars($ticket['age']) . " disabled/>";
       
      $html .= "</div>";
  $html .= "</form>";      
  $html .= "</div>";

  $html .= "</div>";
    return $html;
}
?>