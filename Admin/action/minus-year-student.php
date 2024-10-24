<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

try {
    $sql = "UPDATE student_user
    SET year_section = CONCAT(CAST(SUBSTRING(year_section, 1, 1) AS UNSIGNED) - 1, 
                                      SUBSTRING(year_section, 2, 1))";
$stmt = $pdoConnect->prepare($sql);
$stmt->execute();

header("Location: ../user-student-list.php");
exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $pdo = null; // Close the connection
}
?>
