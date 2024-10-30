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
        $user_ID = $Data['admin_number'];
        $Email_Add = $Data['email_address'];
        $Name = $Data['f_name'];
        $lname = $Data['l_name'];
        $P_P = $Data['profile_picture'];
        $Sex = $Data['sex'];
        $Age = $Data['age'];
        $Bday = $Data['birthday'];
        $U_T = $Data['user_type'];

    

        $P_PBase64 = base64_encode($P_P);
        $date = new DateTime($Bday);
        $formattedDate = $date->format('F j, Y'); // This will give "July 22, 1990"
    } else {
        // Handle the case where no results are found
        echo "No Admin found with the given student number.";
    }

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
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sysName?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*"> 
    <link rel="stylesheet" href="assets/css/report.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header>
    <div class="header-content">
        <h1>Employee Report</h1>
        <div class="header-left">
            <h3>Employee Name: <?php echo $Name . " " . $lname ?></h3>
            <h3>Year: <?php echo date('Y')?></h3>
        </div>
        <div class="header-right">
            <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" alt="Employee Image" />
        </div>
    </div>
</header>


    <main>
        <!-- Sample Table -->
        <section>
            <h2>Section 1: Working Durations (<?php echo date("F");?>)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tickets</th>
                        <th>Longest Duration</th>
                        <th>Shortest Duration</th>
                        <th>Average Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>All Tickets</td>
                        <td>1 Hour, 24 Minutes</td>
                        <td>26 Minutes</td>
                        <td>55 Minutes</td>
                    </tr>
                    <tr>
                        <td>Student Tickets</td>
                        <td>1 Hour, 15 Minutes</td>
                        <td>26 Minutes</td>
                        <td>50 Minutes, 30 Seconds</td>
                    </tr>
                    <tr>
                        <td>Employee Tickets</td>
                        <td>1 Hour, 24 Minutes</td>
                        <td>35 Minutes</td>
                        <td>59 Minutes, 30 Seconds</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Pie Chart Section -->
        <section>
            <h2>Section 2: Total Tickets (<?php echo date("F");?>)</h2>
            <canvas id="pieChart"></canvas>
        </section>

        <!-- Line Chart Section -->
        <section>
            <h2>Section 3: Total Tickets (Yearly)</h2>
            <canvas id="lineChart"></canvas>
        </section>
    </main>

    <footer>
        <p>Footer Information</p>
    

    <button onclick="window.print()">Print this page</button>
    </footer>
    <script>
        // Pie chart configuration
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Student Tickets', 'Employee Tickets'],
                datasets: [{
                    label: '2024 Data',
                    data: [50, 100],
                    backgroundColor: ['#FF6384', '#36A2EB'],
                    hoverOffset: 4
                }]
            },
        });

        // Line chart configuration
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['2022', '2023', '2024'],
                datasets: [{
                    label: 'Student Tickets',
                    data: [45, 75, 60],
                    borderColor: '#36A2EB',
                    fill: false
                }, {
                    label: 'Employee Tickets',
                    data: [60, 95, 100],
                    borderColor: '#FF6384',
                    fill: false
                }]
            },
        });
    </script>
</body>
</html>
