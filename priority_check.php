<?php
// Database connection (Replace with your own database connection details)
include_once("connection/conn.php");
$pdoConnect = connection();
// Get the current time
$current_time = new DateTime();

// SQL query to select rows where priority is 'no' and status is either 'pending' or 'processing'
$sql = "SELECT ticket_id, created_date FROM tb_tickets WHERE priority = 'NO' AND status IN ('Pending', 'Processing')";
$stmt = $pdoConnect->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    // Calculate the difference between the current time and the date created
    $date_created = new DateTime($row['created_date']);
    $interval = $current_time->diff($date_created);

    // Check if the difference is 1 day or more
    if ($interval->days >= 1) {
        // Update the priority to 'yes'
        $update_sql = "UPDATE tb_tickets SET priority = 'YES' WHERE ticket_id = :id";
        $update_stmt = $pdoConnect->prepare($update_sql);
        $update_stmt->execute([':id' => $row['ticket_id']]);
    }
}

echo "Priority update completed.";
?>
