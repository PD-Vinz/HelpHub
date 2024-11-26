<?php
include_once("../../connection/conn.php");

$pdoConnect = connection();
$_SERVER['REQUEST_URI'];
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
        $Name = $Data['f_name'];
        $LastName = $Data['l_name'];
        $MiddleName = $Data['m_name'];
        $MiddleInitial = $Data['m_initial'];
        $ExtensionName = $Data['ext_name'];

        // Check if there's a middle name or initial
        if (!empty($MiddleInitial)) {
            $Name .= " " . $MiddleInitial . ".";
        } elseif (!empty($MiddleName)) {
            $Name .= " " . $MiddleName;
        }

        // Add last name if it exists
        if (!empty($LastName)) {
            $Name .= " " . $LastName;
        }

        // Add extension name if it exists (e.g., Jr., Sr., III)
        if (!empty($ExtensionName)) {
            $Name .= " " . $ExtensionName . ".";
        }
        $Position = $Data['position'];
        $U_T = $Data['user_type'];
        $P_P = $Data['profile_picture'];

        $P_PBase64 = base64_encode($P_P);


        $nameParts = explode(' ', $Name);
        $firstName = $nameParts[0];
    } else {
        // Handle the case where no results are found
        echo "No student found with the given student number.";
    }

    if (isset($_GET["id"]) && $_GET["id"] == 1) {
        $identity = "Student";
        $_SESSION["WhatUser"] = $identity;
    } elseif (isset($_GET["id"]) && $_GET["id"] == 2) {
        $identity = "Employee";
        $_SESSION["WhatUser"] = $identity;
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
    // for displaying system details //end

}

$js_file = 'upload.js';
$js_cache_buster = filemtime($js_file); // Only changes if the file is modified

$css_file = 'upload.css';
$cache_buster = filemtime($css_file); // Only changes if the file is 

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $sysName ?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($S_LBase64, ENT_QUOTES, 'UTF-8'); ?>" type="image/*">

    <!-- FONTAWESOME STYLES-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CUSTOM STYLES-->
    <link href="upload.css?v=<?php echo $cache_buster; ?>" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>

    <!-- /. NAV SIDE  -->

    <div id="page-inner">
        <div class="row">
            <div class="container-center">
                <div class="modal-header">
                    <button class="close-btn" onclick="closeWindow()">✖</button>
                    <img src="../assets/img/head.png" alt="Technical support for DHVSU students" style="height:auto;">
                    <hr>
                    <h3><?php echo $identity?> Information</h3>
                    <form class="issue-form" id="issueForm" action="ticket-submit.php" method="POST" enctype="multipart/form-data">
                    <div class="email-compose">
                        <label for="userid">User ID</label>
                        <input type="text" id="userid" name="userid" autocomplete="off" placeholder="Enter User ID" required>
                        <ul id="suggestions" class="suggestions"></ul>
            
                        <label for="f_name">First Name</label>
                        <input type="text" id="f_name" name="f_name" placeholder="First Name" readonly>
                        <label for="l_name">Last Name</label>
                        <input type="text" id="l_name" name="l_name" placeholder="Last Name" readonly>
                        <label for="email">Email Address</label>
                        <input type="text" id="email" name="email" placeholder="Email Address" readonly>

                    </div>
                    <hr>
                        <div class="form-group">
                            <label for="category">Issue</label>
                            <select id="category" name="category" class="form-control dropdown" required>
                                <option value="">SELECT PROBLEM</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="issue-description">Issue Description</label>
                            <textarea id="issue-description" name="issue-description" class="form-control"
                                maxlength="255" oninput="updateRemainingCharacters()" required></textarea>
                            <small id="remaining-characters" class="form-text text-muted">255 characters
                                remaining</small>
                        </div>
                        <label for="" class="control-label">Upload and attach files <?php echo ($identity == 'Employee') ? '(Optional)' : ''; ?></label>
                        <div>
        <label>
            <input type="radio" name="uploadOption" value="upload" checked> Upload Image
        </label>
        <label>
            <input type="radio" name="uploadOption" value="paste"> Paste Screenshot
        </label>
    </div>

    <div id="upload-container" style="display: block;">
        <div class="upload-area">
            <div class="upload-icon"><!-- SVG icon --></div>
            <p>Click to upload or drag and drop</p>
            <p>Only JPG, JPEG, and PNG.</p>
            <p>6 MB Max File Size</p>
            <input type="file" id="file-input" name="image" <?php echo ($identity == 'Student') ? 'required' : ''; ?> >
            <p class="file-message">No Files Selected</p>
            <p id="size-error" style="color:red;"></p>
            <p id="type-error" style="color:red;"></p>
        </div>
    </div>

    <div id="paste-container" style="display: none;">
        <div id="pasteArea" class="paste-area">
            <p>Click here then paste the image here (Ctrl+V)</p>
            <img id="pastedPreview" alt="Pasted Preview" style="max-width: 100%; display: none;">
            <p id="paste-error" style="color:red;"></p>
            <input type="hidden" id="pasted-image-data" name="image" <?php echo ($identity == 'Student') ? 'required' : ''; ?> >
        </div>
    </div>

                        <br>
                        <div class="modal-body">
                            <div class="letter">
                                <main>

                                    <p>By completing this form, I allow Don Honorio Ventura State University,
                                        specifically the Management Information Systems Office, to gather, store, and
                                        handle the information
                                        I provide regarding my SMS/LMS/@dhvsu Google account concerns.</p>
                                    <br>
                                    <label>
                                        <input type="checkbox" name="consent" value="yes" required> Yes, I consent
                                    </label>
                                </main>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">SUBMIT</Input>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="main-footer text-sm" style="text-align: center;">
        <strong>Copyright © <?php echo date('Y') ?>.
            <!-- <a href=""></a> -->
        </strong>
        All rights reserved. <b>(by: <a href="mailto:dhvsuhelphub@gmail.com"
                target="_blank">dhvsuhelphub@gmail.com</a>)</b> v1.0
    </footer>
    <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <script>// Pass the PHP variable to JavaScript
        var identity = "<?php echo $identity; ?>";
    </script>
    <script src="upload.js?v=<?php echo $js_cache_buster; ?>"></script>

</body>

</html>

<?php include '../loading.php'; ?>