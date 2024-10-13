




















<?php
include_once("../../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {

try {

    $id = $_SESSION["user_id"];
    $ticket_id = $_GET['id'];
   $cc1 = $_POST['cc1'];
  $cc2 = $_POST['cc2'];
   $cc3 = $_POST['cc3'];
  $sqd0 = $_POST['sqd0'];
  $sqd1 = $_POST['sqd1'];
  $sqd2 = $_POST['sqd2'];
  $sqd3 = $_POST['sqd3'];
  $sqd4 = $_POST['sqd4'];
  $sqd6 = $_POST['sqd6'];
  $sqd7 = $_POST['sqd7'];
  $sqd8 = $_POST['sqd8'];
    $overall_satisfaction = $_POST['overall_satisfaction'];
    $service_rating = $_POST['service_rating'];
    $service_expectations = $_POST['service_expectations'];
    $like = $_POST['like'];
    $improve = $_POST['improve'];
    $comments = $_POST['comments'];
    $likeRating = $_POST['likeRating'];
    $improveRating = $_POST['improveRating'];
    $commentsRating = $_POST['commentsRating'];
    $datetime = date('Y-m-d H:i:s');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Start a transaction
            $pdoConnect->beginTransaction();
            
            // Prepare an insert statement
            $stmt = $pdoConnect->prepare("INSERT INTO tb_survey_feedback 
                                                (cc1, cc2, cc3, sqd0, sqd1, sqd2, sqd3, sqd4, sqd6, sqd7, sqd8, overall_satisfaction, service_rating, service_expectations, like_service, improvement, comments, user_id, ticket_id,  date_time, bayes_rating_like, bayes_rating_improve, bayes_rating_comment) 
                                        VALUES  (:cc1, :cc2, :cc3, :sqd0, :sqd1, :sqd2, :sqd3, :sqd4, :sqd6, :sqd7, :sqd8, :O_S, :S_R, :S_E, :like_service, :improve, :comments, :id, :T_ID, :D_T, :likeRating, :improveRating, :commentsRating)");
            // Bind the blob data
           $stmt->bindParam(':cc1', $cc1, PDO::PARAM_LOB); 
            $stmt->bindParam(':cc2', $cc2, PDO::PARAM_LOB); 
            $stmt->bindParam(':cc3', $cc3, PDO::PARAM_LOB); 
            $stmt->bindParam(':sqd0', $sqd0, PDO::PARAM_LOB);
            $stmt->bindParam(':sqd1', $sqd1, PDO::PARAM_LOB);
            $stmt->bindParam(':sqd2', $sqd2, PDO::PARAM_LOB);
            $stmt->bindParam(':sqd3', $sqd3, PDO::PARAM_LOB);
            $stmt->bindParam(':sqd4', $sqd4, PDO::PARAM_LOB);
            $stmt->bindParam(':sqd6', $sqd6, PDO::PARAM_LOB);
            $stmt->bindParam(':sqd7', $sqd7, PDO::PARAM_LOB);
            $stmt->bindParam(':sqd8', $sqd8, PDO::PARAM_LOB);
            $stmt->bindParam(':O_S', $overall_satisfaction, PDO::PARAM_LOB);
            $stmt->bindParam(':S_R', $service_rating, PDO::PARAM_LOB);
            $stmt->bindParam(':S_E', $service_expectations, PDO::PARAM_LOB);
            $stmt->bindParam(':like_service', $like, PDO::PARAM_LOB);
            $stmt->bindParam(':improve', $improve, PDO::PARAM_LOB);
            $stmt->bindParam(':comments', $comments, PDO::PARAM_LOB);
            $stmt->bindParam(':id', $id, PDO::PARAM_LOB);
            $stmt->bindParam(':T_ID', $ticket_id, PDO::PARAM_LOB);
            $stmt->bindParam(':D_T', $datetime, PDO::PARAM_LOB);
            $stmt->bindParam(':likeRating', $likeRating, PDO::PARAM_LOB);
            $stmt->bindParam(':improveRating', $improveRating, PDO::PARAM_LOB);
            $stmt->bindParam(':commentsRating', $commentsRating, PDO::PARAM_LOB);

            // Execute the statement
            if ($stmt->execute()) {
                    // Get the last inserted ID
                    $lastInsertId = $pdoConnect->lastInsertId();

                    // Commit the transaction
                    $pdoConnect->commit();

                header("Location: ../dashboard.php");
                exit();
            } else {
                // Roll back the transaction on failure
                $pdoConnect->rollBack();
                header("Location: ../survey-extension.php");
                exit();
            }
    }


} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    echo "<a href='index.php'>Back</a>";

    // Roll back the transaction on exception
    if ($pdoConnect->inTransaction()) {
        $pdoConnect->rollBack();
    }
}

// Close the connection
$conn = null;
}
?>

