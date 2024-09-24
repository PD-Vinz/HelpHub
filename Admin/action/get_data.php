<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

// Get the entry ID from the request
$id = $_GET['id'];

// Fetch the data for the selected entry
$stmt = $pdoConnect->prepare("SELECT template_content FROM templates WHERE template_id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Return the content as plain text
if ($data) {
    echo $data['template_content'];
} else {
    echo "No content found.";
}
?>
