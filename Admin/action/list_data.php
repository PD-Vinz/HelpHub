<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

// Fetch list of entries (e.g., file names or other unique IDs)
$stmt = $pdoConnect->query("SELECT template_id, template_name FROM templates");
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
echo json_encode($entries);
?>
