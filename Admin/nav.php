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

<nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">      
        <a class="navbar-brand" href="index.php"><?php echo $shortName?></a><button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
         <div ><i class="fa-solid fa-bars fa-2x"></i></div>
        </button>

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
      
   
   <nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
        <div class="asidebar-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                
        <li class="text-center">
            <img src="assets/img/find_user.png" class="user-image img-responsive"/>
            </li>
        
            
            <li>
                <a class="active-menu"  href="index.php"><span class="number"><i class="fa fa-dashboard fa-xl"></i></span class="text"> <span> Dashboard</span></a>
            </li>
            <li>
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">

        <i class="fa-solid fa-graduation-cap fa-xl"></i> Student Tickets <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level ticket-dropdown-menu">
              <!--fix the icons-->
              
              <li>
                  <a href="ticketdash.php?id=1"> &nbsp;&nbsp;<i class="fa fa-ticket fa-xl" aria-hidden="true"></i>All Tickets</a>
                  </li>
                  <li>
                      <a href="ticket-pending.php?id=1">&nbsp;&nbsp;<i class="fa fa-hourglass-half fa-xl" aria-hidden="true"></i>Pending Tickets</a>
                  </li>
                  <li>
                      <a href="ticket-opened.php?id=1">&nbsp;&nbsp;<i class="fa fa-envelope-open fa-xl" aria-hidden="true"></i>Opened Tickets</a>
                  </li>
                  <li>
                    <a href="ticket-closed.php?id=1">&nbsp;&nbsp;<i class="fa-solid fa-check-to-slot fa-xl"></i>Closed Tickets</a>
                </li>
                <li>
                  <a href="ticket-returned.php?id=1">&nbsp;&nbsp;<i class="fa fa-undo fa-xl" aria-hidden="true"></i>Returned Tickets</a>
                </li>
                
              </ul>
            </li>
            <li>
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">

        <i class="fa-solid fa-briefcase fa-xl"></i> Employeee Tickets <span class="fa arrow"></span>

    </a>
    <ul class="nav nav-second-level ticket-dropdown-menu">
              <!--fix the icons-->
              
              <li>
                  <a href="ticketdash.php?id=2"> &nbsp;&nbsp;<i class="fa fa-ticket fa-xl" aria-hidden="true"></i>All Tickets</a>
                  </li>
                  <li>
                      <a href="ticket-pending.php?id=2">&nbsp;&nbsp;<i class="fa fa-hourglass-half fa-xl" aria-hidden="true"></i>Pending Tickets</a>
                  </li>
                  <li>
                      <a href="ticket-opened.php?id=2">&nbsp;&nbsp;<i class="fa fa-envelope-open fa-xl" aria-hidden="true"></i>Opened Tickets</a>
                  </li>
                  <li>
                    <a href="ticket-closed.php?id=2">&nbsp;&nbsp;<i class="fa-solid fa-check-to-slot fa-xl"></i>Closed Tickets</a>
                </li>
                <li>
                  <a href="ticket-returned.php?id=2">&nbsp;&nbsp;<i class="fa fa-undo fa-xl" aria-hidden="true"></i>Returned Tickets</a>
                </li>
                
              </ul>
            </li>
            <li>
            <a href="history-log.php"><i class="fa-regular fa-clock fa-xl"></i> Log History</a>

            </li>

            <?php if (isset($U_T) && $U_T === 'Administrator'): ?>
            <li>
                <a href="feedback-analysis.php" ><i class="fa-regular fa-comment-dots fa-xl"></i>Feedbacks</a>
            </li>
            <li>
                <a href="employee.php"><i class="fa-solid fa-user-tie fa-xl"></i> Employees</a>
            </li>
            <li>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">
        <i class="fa-regular fa-user fa-xl"></i> User list <span class="fa arrow "></span>
    </a>
    <ul class="nav nav-second-level ticket-dropdown-menu">
              <!--fix the icons-->
              <li>
                  <a href="user-student-list.php"> &nbsp;&nbsp;<i class="fa-solid fa-graduation-cap fa-xl" aria-hidden="true"></i>Student's Accounts</a>
                  </li>
                  <li>
                      <a href="user-employee-list.php">&nbsp;&nbsp;<i class="fa-solid fa-briefcase fa-xl" aria-hidden="true"></i>Employee's Account</a>
                  </li>
              </ul>
            </li>
            <li>
                <a href="settings.php"><i class="fa fa-gear fa-xl"></i>System Settings</a>
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