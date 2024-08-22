<nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">MIS Office</a> 
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
        <li class="text-center">
            <img src="assets/img/find_user.png" class="user-image img-responsive"/>
            </li>
        
            
            <li>
                <a class="active-menu"  href="index.php"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
            </li>
            <li>
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">
        <i class="fa fa-list fa-3x"></i> Student Tickets <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level ticket-dropdown-menu">
              <!--fix the icons-->
              
              <li>
                  <a href="ticketdash.php?id=1"> &nbsp;&nbsp;<i class="fa fa-ticket " aria-hidden="true"></i>All Tickets</a>
                  </li>
                  <li>
                      <a href="ticket-pending.php?id=1">&nbsp;&nbsp;<i class="fa fa-hourglass-half " aria-hidden="true"></i>Pending Tickets</a>
                  </li>
                  <li>
                      <a href="ticket-opened.php?id=1">&nbsp;&nbsp;<i class="fa fa-envelope-open" aria-hidden="true"></i>Opened Tickets</a>
                  </li>
                  <li>
                    <a href="ticket-closed.php?id=1">&nbsp;&nbsp;<i class="fa-solid fa-check-to-slot"></i>Closed Tickets</a>
                </li>
                <li>
                  <a href="ticket-returned.php?id=1">&nbsp;&nbsp;<i class="fa fa-undo" aria-hidden="true"></i>Returned Tickets</a>
                </li>
                
              </ul>
            </li>
            <li>
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">
        <i class="fa fa-list fa-3x"></i> Employeee Tickets <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level ticket-dropdown-menu">
              <!--fix the icons-->
              
              <li>
                  <a href="ticketdash.php?id=2"> &nbsp;&nbsp;<i class="fa fa-ticket " aria-hidden="true"></i>All Tickets</a>
                  </li>
                  <li>
                      <a href="ticket-pending.php?id=2">&nbsp;&nbsp;<i class="fa fa-hourglass-half " aria-hidden="true"></i>Pending Tickets</a>
                  </li>
                  <li>
                      <a href="ticket-opened.php?id=2">&nbsp;&nbsp;<i class="fa fa-envelope-open" aria-hidden="true"></i>Opened Tickets</a>
                  </li>
                  <li>
                    <a href="ticket-closed.php?id=2">&nbsp;&nbsp;<i class="fa-solid fa-check-to-slot"></i>Closed Tickets</a>
                </li>
                <li>
                  <a href="ticket-returned.php?id=2">&nbsp;&nbsp;<i class="fa fa-undo" aria-hidden="true"></i>Returned Tickets</a>
                </li>
                
              </ul>
            </li>
            <li>
            <a href="history-log.php"><i class="fa-regular fa-clock fa-3x"></i> Log History</a>

            </li>

            <?php if (isset($U_T) && $U_T === 'Administrator'): ?>
            <li>
                <a href="feedback-analysis.php" ><i class="fa-regular fa-comment-dots fa-3x"></i>Feedbacks</a>
            </li>
            <li>
                <a href="employee.php"><i class="fa-solid fa-user-tie fa-3x"></i> Employees</a>
            </li>
            <li>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="handleTicketDropdownToggle(event)">
        <i class="fa-regular fa-user fa-3x"></i> User list <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level ticket-dropdown-menu">
              <!--fix the icons-->
              <li>
                  <a href="user-student-list.php"> &nbsp;&nbsp;<i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>Student's Accounts</a>
                  </li>
                  <li>
                      <a href="user-employee-list.php">&nbsp;&nbsp;<i class="fa-solid fa-briefcase" aria-hidden="true"></i>Employee's Account</a>
                  </li>
              </ul>
            </li>
            <li>
                <a href="settings.php"><i class="fa fa-gear fa-3x"></i>System Settings</a>
            </li>
            <?php endif; ?>
        </ul>
       
    </div>
    
</nav>  
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



