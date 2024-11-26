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

    // Fetch admin details
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
        $formattedDate = $date->format('F j, Y');
    } else {
        echo "No Admin found with the given admin number.";
    }

    // Fetch system details
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

    // Fetch ticket durations for the current month
    $currentMonth = date('Y-m');
    $durationsQuery = "
        SELECT 
            user_type,
            MAX(duration) AS longest,
            MIN(duration) AS shortest,
            AVG(duration) AS average
        FROM tb_tickets
        WHERE DATE_FORMAT(finished_date, '%Y-%m') = :currentMonth 
        GROUP BY user_type
    ";
    $durationsStmt = $pdoConnect->prepare($durationsQuery);
    $durationsStmt->execute(['currentMonth' => $currentMonth]);
    $durationsData = $durationsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch ticket counts for the current month
    $ticketCountsQuery = "
        SELECT 
            user_type,
            COUNT(*) AS count
        FROM tb_tickets
        WHERE DATE_FORMAT(opened_date, '%Y-%m') = :currentMonth
        GROUP BY user_type
    ";
    $ticketCountsStmt = $pdoConnect->prepare($ticketCountsQuery);
    $ticketCountsStmt->execute(['currentMonth' => $currentMonth]);
    $ticketCountsData = $ticketCountsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch yearly ticket trends
    $yearlyTrendsQuery = "
        SELECT 
            YEAR(opened_date) AS year,
            user_type,
            COUNT(*) AS count
        FROM tb_tickets
        GROUP BY YEAR(opened_date), user_type
        ORDER BY year ASC
    ";
    $yearlyTrendsStmt = $pdoConnect->prepare($yearlyTrendsQuery);
    $yearlyTrendsStmt->execute();
    $yearlyTrendsData = $yearlyTrendsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Organize data for charts
    $yearlyData = [];
    foreach ($yearlyTrendsData as $row) {
        $yearlyData[$row['year']][$row['user_type']] = $row['count'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($sysName, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*"> 
    <link rel="stylesheet" href="assets/css/report.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header>
    <div class="header-content">
        <h1>Employee Report</h1>
        <div class="header-left">
            <h3>Employee Name: <?php echo htmlspecialchars($Name . " " . $lname, ENT_QUOTES, 'UTF-8'); ?></h3>
            <h3>Year: <?php echo date('Y'); ?></h3>
        </div>
        <div class="header-right">
            <img src="data:image/jpeg;base64,<?php echo $P_PBase64 ?>" alt="Employee Image" />
        </div>
    </div>
</header>

<main>
    <!-- Working Durations Section -->
    <section>
        <h2>Section 1: Working Durations (<?php echo date("F"); ?>)</h2>
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
                <?php foreach ($durationsData as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['user_type'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo gmdate("H:i:s", strtotime($row['longest'])); ?></td>
                        <td><?php echo gmdate("H:i:s", strtotime($row['shortest'])); ?></td>
                        <td><?php echo gmdate("H:i:s", strtotime($row['average'])); ?></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Pie Chart Section -->
    <section>
        <h2>Section 2: Total Tickets (<?php echo date("F"); ?>)</h2>
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
    // Data for Pie Chart
    const pieData = {
        labels: <?php echo json_encode(array_column($ticketCountsData, 'user_type')); ?>,
        datasets: [{
            data: <?php echo json_encode(array_column($ticketCountsData, 'count')); ?>,
            backgroundColor: ['#FF6384', '#36A2EB'],
            hoverOffset: 4
        }]
    };

    // Pie chart configuration
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: pieData,
    });

    // Data for Line Chart
    const lineLabels = <?php echo json_encode(array_keys($yearlyData)); ?>;
    const studentData = <?php echo json_encode(array_column(array_map(fn($y) => $y['Student'] ?? 0, $yearlyData), 0)); ?>;
    const employeeData = <?php echo json_encode(array_column(array_map(fn($y) => $y['Employee'] ?? 0, $yearlyData), 0)); ?>;

    // Line chart configuration
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: lineLabels,
            datasets: [
                { label: 'Student Tickets', data: studentData, borderColor: '#36A2EB', fill: false },
                { label: 'Employee Tickets', data: employeeData, borderColor: '#FF6384', fill: false }
            ]
        },
    });
</script>
</body>
</html>
