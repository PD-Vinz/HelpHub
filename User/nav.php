<?php
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

} catch (PDOException $e) {
echo "Error: " . $e->getMessage();
}

?>

<style>
.btn-light.dropdown-toggle:hover {
    background-color: #f0f0f0; /* Adjust to your preferred hover color */
    color: #333; /* Adjust to your preferred text color */
}

.btn-light.dropdown-toggle:focus {
    outline: none;
    box-shadow: none;
}

</style>

<nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="dashboard.php"><?php echo $shortName?></a>
            </div>
            <div style="color: white; padding: 15px 50px 5px 50px; float: right;"> 
<div class="btn-group nav-link">
    <button type="button" class="btn btn-light rounded-circle dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <span class="ml-3"><?php echo htmlspecialchars($Name); ?></span>
        <i class="fas fa-caret-down ml-1"></i>
    </button>
    
    <ul class="dropdown-menu dropdown-menu-right" role="menu">
        <!--
        <li><a href="profile.php"><i class="fa fa-user"></i> MY ACCOUNT</a></li>
        <li class="divider"></li>
-->
        <li><a href="settings.php"><i class="fa fa-gear"></i> SETTINGS</a></li>
        <li class="divider"></li>
        
        <?php if (!isset($_SESSION["Super-Admin"])): ?>
            <?php if ($identity == "Student"): ?>
                <li><a href="logout.php" onclick="window.open('https://forms.gle/Bf2yoFEiYE8k56Pb6', '_blank');"><i class="fa fa-sign-out"></i> LOG OUT</a></li>
            <?php elseif ($identity == "Employee"): ?>
                <li><a href="logout.php" onclick="window.open('https://forms.gle/kUJQW5YTbBfKKMw37', '_blank');"><i class="fa fa-sign-out"></i> LOG OUT</a></li>
            <?php endif; ?>
        <?php elseif (isset($_SESSION["Super-Admin"]) && $_SESSION["Super-Admin"] === 'Log In Success'): ?>
            <li><a href="../index.php"><i class="fa fa-sign-out"></i> Log Out</a></li>
        <?php endif; ?>
    </ul>
</div>


</div>
        </nav>

<!-- /. NAV TOP  -->

<nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="user-image img-responsive" />
                        <h3 style="color:white;"><?php echo $Name?></h3>
                    </li>

                    <li>
                    <a class="<?= ($currentFile == 'dashboard.php') ? 'active-menu' : '' ?>" href="dashboard.php"><i class="fa fa-dashboard fa-xl" style="font-size:24px;color:rgb(255, 255, 255)"></i>  DASHBOARD </a>
                    </li>
                    <li>
                    <a class="<?= ($currentFile == 'profile.php') ? 'active-menu' : '' ?>" href="profile.php"><i class="fa fa-user fa-xl" style="font-size:24px;color:rgb(255, 255, 255)"></i> PROFILE </a>
                        </li>

                        <li>
                            <a class="<?= ($currentFile == 'create-ticket.php') ? 'active-menu' : '' ?>" href="create-ticket.php"><i class="fa fa-plus fa-xl" style="font-size: 24px; color: rgb(255, 255, 255)"></i> CREATE TICKET </a>
                            </li>
                            <li>
                        <a class="<?= ($currentFile == 'all-ticket.php') ? 'active-menu' : '' ?>" href="all-ticket.php"><i class="fa fa-ticket fa-xl" style="font-size: 24px; color: rgb(255, 255, 255)"></i> ALL TICKET </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE -->