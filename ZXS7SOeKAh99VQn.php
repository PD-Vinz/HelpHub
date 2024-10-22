<?php

session_start();

include_once("connection/conn.php");
$pdoConnect = connection();

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

if (isset($_POST['login'])) {
    try {

        $one = $_POST["word_one"];
        $two = $_POST["word_two"];
        $three = $_POST["word_three"];
        $four = $_POST["word_four"];
        $five = $_POST["word_five"];
        $six = $_POST["word_six"];
        $seven = $_POST["word_seven"];
        $eight = $_POST["word_eight"];
        $nine = $_POST["word_nine"];
        $ten = $_POST["word_ten"];

        if (($one === "alvin")&&($two === "felix")&&($three === "earvin")&&($four === "rica")&&($five === "angela")
        &&($six === "jean")&&($seven === "helphub")&&($eight === "reden")&&($nine === "mis")&&($ten === "dhvsu")){
            $_SESSION["Super-Admin"] = "Log In Success";
            header("Location: Super-Admin/Dashboard.php");
            exit(); // Prevent further execution after redirection
        } else {
            // If no match found in both tables
        $errorMessage = "Wrong word/s. Try again.";
        echo "<script type='text/javascript'>
            window.onload = function() {
                alert('$errorMessage');
                
            };
            </script>";
        }

            
    } catch (PDOException $error) {
        $message = '<label>Error: ' . $error->getMessage() . '</label>';
    }
}
?>

<!DOCTYPE html>
<html lang="en" oncontextmenu="return false;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DHVSU MIS - HelpHub</title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link  rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
    <style>
body {
    overflow-y: scroll;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5px; /* Reduce the gap between columns */
}

.form-group {
    margin-bottom: 5px; /* Reduce the margin at the bottom of each input */
}

.form-group input[type="text"] {
    width: 100%;
    padding: 8px; /* Slightly reduce padding for a more compact input */
    border: 1px solid #ccc;
    border-radius: 10px; /* Reduce border-radius for smaller input appearance */
    box-sizing: border-box;
}

input[type="submit"] {
    width: 75%;
    background-color: #E4A001;
    color: #fff;
    padding: 8px; /* Reduce padding for a smaller button */
    border: none;
    border-radius: 15px; /* Reduce the radius for a tighter look */
    cursor: pointer;
    font-size: 14px; /* Slightly reduce the font size */
    margin-top: 10px; /* Ensure the button isn't too close to inputs */
}

    </style>
</head>
<body>
    <img class="logo" src="img/MIS logo.png" alt="Image">

    <div class="login">
        <form method="post">
            <h3 style="text-shadow: 0.3px 0.3px #18181a;">Log In</h3>
            <hr><br>

            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="word_one" required placeholder="First Word" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" name="word_two" required placeholder="Second Word" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" name="word_three" required placeholder="Third Word" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" name="word_four" required placeholder="Fourth Word" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" name="word_five" required placeholder="Fifth Word" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" name="word_six" required placeholder="Sixth Word" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" name="word_seven" required placeholder="Seventh Word" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" name="word_eight" required placeholder="Eighth Word" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" name="word_nine" required placeholder="Ninth Word" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" name="word_ten" required placeholder="Tenth Word" autocomplete="off">
                </div>
            </div>

            <input type="submit" name="login" value="Log In"><br>
        </form>
    </div> 

<footer>
    <p>&copy; 2024 HelpHub. All rights reserved.</p>
    <p>
        <button class="link-button" onclick="window.open('/terms-of-service.html', '_blank');">Terms of Service</button> | 
        <button class="link-button" onclick="window.open('/privacy-policy.html', '_blank');">Privacy Policy</button>
    </p>
</footer>


    <script src="script.js"></script>
</body>
</html>