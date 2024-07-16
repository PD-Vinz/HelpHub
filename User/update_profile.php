<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

//Updates the Balance Sheet
if (!isset($_SESSION["student_number"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["student_number"];

if (isset($_POST['update'])) {

    try{
    $NewName = $_POST['name'];
    $NewSex = $_POST['sex'];
    $NewDept = $_POST['dept'];
    $NewCourse = $_POST['course'];
    $NewYS = $_POST['ys'];

    $pdoUserQuery = "UPDATE student_user SET name = :name, department = :department, course = :course, year_section = :year_section, sex = :sex WHERE student_number = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->bindParam(':name', $NewName);
    $pdoResult->bindParam(':department', $NewDept);
    $pdoResult->bindParam(':course', $NewCourse);
    $pdoResult->bindParam(':year_section', $NewYS);
    $pdoResult->bindParam(':sex', $NewSex);
    $pdoResult->execute();

        // Set a session variable to indicate successful update
        $_SESSION['update_success'] = true;

        // Redirect to the same page to prevent form resubmission
        header("location: profile.php");
        exit();


    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
        exit(); // Exit after handling the error
    }

}
}