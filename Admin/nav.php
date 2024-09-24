<?php

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


    $Name = $Data['f_name'];
    $lname = $Data['l_name'];
    $P_P = $Data['profile_picture'];

    $P_PBase64 = base64_encode($P_P);

    
    if ($Data) {
        $Name = $Data['f_name'];
        $Position = $Data['position'];
        $U_T = $Data['user_type'];

        $nameParts = explode(' ', $Name);
        $firstName = $nameParts[0];
      } else {
        // Handle the case where no results are found
        echo "User not found";
    }

try {


   
    
     // for displaying system details
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
  // for displaying system details //end
    



    // Get the current page name and id
    $currentFile = basename($_SERVER['PHP_SELF']);
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    
    // Determine if the Student or Employee Tickets dropdowns should be open
    $studentDropdownOpen = ($id == '1' && in_array($currentFile, ['ticketdash.php', 'ticket-pending.php', 'ticket-opened.php', 'ticket-closed.php', 'ticket-returned.php']));
    $employeeDropdownOpen = ($id == '2' && in_array($currentFile, ['ticketdash.php', 'ticket-pending.php', 'ticket-opened.php', 'ticket-closed.php', 'ticket-returned.php']));
    $userListDropdownOpen = in_array($currentFile, ['employee.php', 'user-student-list.php', 'user-employee-list.php']);
    $systemDocsDropdownOpen = in_array($currentFile, ['templates.php', 'issues.php']);



} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

}




?>

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
<div style="color: white; padding: 15px 50px 5px 50px; float: right;"> Last access : <?php echo date('d F Y')?> &nbsp; 
<div class="btn-group nav-link">
          <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
         
            <span class="ml-3"><?php echo $Name?></span>
            <span class="fa fa-caret-down">
            <span class="sr-only">Toggle Dropdown</span>
            
          </button>
          <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="profile.php"><span class="fa fa-user"></span> My Account</a>
            <hr style="margin-top: 5px; margin-bottom: 5px;">
            <a class="dropdown-item" href="logout.php"><span class="fas fa-sign-out-alt"></span> Logout</a>
          </div>
      </div>
</div>
</nav>   
   <!-- /. NAV TOP  -->
      
   
   <?php
$currentFile = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
    
        <li class="text-center">
            <a href="profile.php"><img src="assets/img/find_user.png" class="user-image img-responsive"/></a>
            </li>
        
            
            <li>
                <a class="active-menu"  href="index.php"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
            </li>
            <li>
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">
        <i class="fa fa-list fa-3x"></i> Student Tickets <span class="fa arrow"></span>
        <div class="text-center"><br>
                                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="user-image img-responsive" alt="avatar">
                                        <h3 style="color:white;"><?php echo $Name,  " ", $lname?></h3>
                                       
                                    </div>
        </li>

        <li>
            <a class="<?= ($currentFile == 'index.php') ? 'active-menu' : '' ?>" href="index.php">
                <span class="number fa fa-dashboard fa-xl"></span><span class="text"> Dashboard</span>
            </a>
        </li>

        <li>


    <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">
        <i class="number fa-solid fa-graduation-cap fa-xl"></i> Student Tickets <span class="fa arrow"></span>
    </a>


    <ul class="nav nav-second-level ticket-dropdown-menu <?= $studentDropdownOpen ? 'in' : '' ?>"> <!-- Keep open if active -->
    <li>
            <a class="<?= ($currentFile == 'ticketdash.php' && $_GET['id'] == 1) ? 'active-menu' : '' ?>" href="ticketdash.php?id=1">
                &nbsp;&nbsp;<i class="fa fa-ticket fa-xl" aria-hidden="true"></i>All Tickets
            </a>
        </li>
        <li>
            <a class="<?= ($currentFile == 'ticket-pending.php' && $_GET['id'] == 1) ? 'active-menu' : '' ?>" href="ticket-pending.php?id=1">
                &nbsp;&nbsp;<i class="fa fa-hourglass-half fa-xl" aria-hidden="true"></i>Pending Tickets
            </a>
        </li>
        <li>
            <a class="<?= ($currentFile == 'ticket-opened.php' && $_GET['id'] == 1) ? 'active-menu' : '' ?>" href="ticket-opened.php?id=1">
                &nbsp;&nbsp;<i class="fa fa-envelope-open fa-xl" aria-hidden="true"></i>Opened Tickets
            </a>
        </li>
        <li>
            <a class="<?= ($currentFile == 'ticket-closed.php' && $_GET['id'] == 1) ? 'active-menu' : '' ?>" href="ticket-closed.php?id=1">
                &nbsp;&nbsp;<i class="fa-solid fa-check-to-slot fa-xl"></i>Closed Tickets
            </a>
        </li>
     
    </ul>
</li>

<li>
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">
        <i class="fa-solid fa-briefcase fa-xl"></i> Employee Tickets <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level ticket-dropdown-menu <?= $employeeDropdownOpen ? 'in' : '' ?>"> <!-- Keep open if active -->
    <li>
            <a class="<?= ($currentFile == 'ticketdash.php' && $_GET['id'] == 2) ? 'active-menu' : '' ?>" href="/Admin/ticketdash.php?id=2">
                &nbsp;&nbsp;<i class="fa fa-ticket fa-xl" aria-hidden="true"></i>All Tickets
            </a>
        </li>
        <li>
            <a class="<?= ($currentFile == 'ticket-pending.php' && $_GET['id'] == 2) ? 'active-menu' : '' ?>" href="/Admin/ticket-pending.php?id=2">
                &nbsp;&nbsp;<i class="fa fa-hourglass-half fa-xl" aria-hidden="true"></i>Pending Tickets
            </a>
        </li>
        <li>
            <a class="<?= ($currentFile == 'ticket-opened.php' && $_GET['id'] == 2) ? 'active-menu' : '' ?>" href="/Admin/ticket-opened.php?id=2">
                &nbsp;&nbsp;<i class="fa fa-envelope-open fa-xl" aria-hidden="true"></i>Opened Tickets
            </a>
        </li>
        <li>
            <a class="<?= ($currentFile == 'ticket-closed.php' && $_GET['id'] == 2) ? 'active-menu' : '' ?>" href="/Admin/ticket-closed.php?id=2">
                &nbsp;&nbsp;<i class="fa-solid fa-check-to-slot fa-xl"></i>Closed Tickets
            </a>
        </li>
       
    </ul>
</li>


        <li>
            <a class="<?= ($currentFile == 'history-log.php') ? 'active-menu' : '' ?>" href="history-log.php">
                <i class="fa-regular fa-clock fa-xl"></i> History Log
            </a>
        </li>

        <?php if (isset($U_T) && $U_T === 'Administrator'): ?>
        <li>
            <a class="<?= ($currentFile == 'feedback-analysis.php') ? 'active-menu' : '' ?>" href="feedback-analysis.php">
                <i class="fa-regular fa-comment-dots fa-xl"></i> Feedback Analysis
            </a>
        </li>

        <!--<li>
            <a class="<?= ($currentFile == 'employee-report.php') ? 'active-menu' : '' ?>" href="employee-report.php">
                <i class="fa-regular fa-comment-dots fa-xl"></i> Employee Reports
            </a>
        </li>-->
        
        <li>
            <a href="#"><i class="fa-regular fa-user fa-xl"></i> User list <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level ticket-dropdown-menu <?= $userListDropdownOpen ? 'in' : '' ?>"> 
            <li>
                    <a class="<?= ($currentFile == 'employee.php') ? 'active-menu' : '' ?>" href="employee.php">
                        &nbsp;&nbsp;<i class="fa-solid fa-user-tie"></i> MIS Employees
                    </a>
                </li>
                <li>
                    <a class="<?= ($currentFile == 'user-student-list.php') ? 'active-menu' : '' ?>" href="user-student-list.php">
                        &nbsp;&nbsp;<i class="fa-solid fa-graduation-cap fa-xl" aria-hidden="true"></i> Student's Accounts
                    </a>
                </li>
                <li>
                    <a class="<?= ($currentFile == 'user-employee-list.php') ? 'active-menu' : '' ?>" href="user-employee-list.php">
                        &nbsp;&nbsp;<i class="fa-solid fa-briefcase fa-xl" aria-hidden="true"></i> Employee's Account
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">
                <i class="fa fa-paste fa-xl"></i> System Documents <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-second-level ticket-dropdown-menu <?= $systemDocsDropdownOpen ? 'in' : '' ?>">
        <li>
                    <a class="<?= ($currentFile == 'templates.php') ? 'active-menu' : '' ?>" href="templates.php">
                        <i class="fa fa-exclamation-triangle"></i> Issues Templates
                    </a>
                </li>
                <li>
                            <a class="<?= $currentFile == 'issues.php' ? 'active-menu' : '' ?>" href="response-issues.php">
                                <i class="fa fa-comment-dots"></i> Response Templates
                            </a>
                  </li>
                  <li>
                      <a href="others.php"><i class="fa fa-folder"></i>Others</a>
                        </li>
            </ul>
        </li>
        <li>
            <a class="<?= ($currentFile == 'settings.php') ? 'active-menu' : '' ?>" href="settings.php">
                <i class="fa fa-gear fa-xl"></i> System Settings
            </a>
        </li>
        <?php endif; ?>
    </ul>
</div>
</nav>
 
<script>
    const buttonside = document.querySelector('.asidebar-btn');

    buttonside.addEventListener('click',()=>{
        const sidebar = document.querySelector('.asidebar');
        sidebar.classList.toggle('open');
    })
</script>
<!--
<script>
// Get elements
const dropdown = document.querySelector('.ticket-dropdown-menu');
const dropdownToggle = document.querySelector('.dropdown-toggle');
const sidebar = document.querySelector('.sidebar-collapse'); 

// Function to toggle the dropdown
function handleTicketDropdownToggle(event) {
  event.preventDefault();
  dropdown.classList.toggle('show');
  sessionStorage.setItem(
    "ticketDropdownState",
    dropdown.classList.contains("show") ? "open" : "closed"
  );
}

// Event listener for dropdown toggle
document.addEventListener("DOMContentLoaded", function () {
  if (dropdownToggle) {
    dropdownToggle.addEventListener("click", handleTicketDropdownToggle);
  }
});

// Event listener for clicks on the sidebar (after it has fully loaded)
window.addEventListener("load", function () { // Attach listener on 'load'
  sidebar.addEventListener("click", function (event) {
    const target = event.target;

    // Check if the click was outside the dropdown AND not on the toggle button itself
    // AND not on a link within the dropdown
    if (
      !dropdown.contains(target) &&
      target !== dropdownToggle &&
      target.tagName !== "A"
    ) {
      dropdown.classList.remove("show");
      sessionStorage.setItem("ticketDropdownState", "closed");
    }
  });

  // Restore dropdown state after sidebar event listener is attached
  var dropdownState = sessionStorage.getItem("ticketDropdownState");
  if (dropdownState === "open" && dropdown) {
    dropdown.classList.add("show");
  }
});


</script> 
            -->