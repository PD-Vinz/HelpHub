<?php
session_start();
header('Content-Type: application/json');

include_once("connection/conn.php"); // Make sure to include your database connection

if (isset($_POST['login'])) {
    try {
        $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $username = $_POST["username"];
        $pass = $_POST["password"];

        $queries = [
            [
                "query" => "SELECT password, account_status FROM student_user WHERE user_id = :username",
                "identity" => "Student",
                "redirect" => "User/dashboard.php"
            ],
            [
                "query" => "SELECT password, account_status FROM employee_user WHERE user_id = :username",
                "identity" => "Employee",
                "redirect" => "User/dashboard.php"
            ],
            [
                "query" => "SELECT password, account_status FROM mis_employees WHERE admin_number = :username",
                "identity" => "Admin",
                "redirect" => "Admin/index.php"
            ]
        ];

        foreach ($queries as $entry) {
            $stmt = $pdoConnect->prepare($entry['query']);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch();
                $storedHash = $user['password'];
                $status = $user['account_status'];

                if ($status == 'Not Activated' && $storedHash == $pass) {
                    $_SESSION["first-time"] = $username;
                    echo json_encode(["success" => true, "redirect_url" => "first-time/verify.php"]);
                    exit();
                }

                if (password_verify($pass, $storedHash)) {
                    if ($status == 'Disabled') {
                        echo json_encode([
                            "success" => false,
                            "message" => "Your account is currently deactivated. If you wish to activate your account, please proceed to the MIS Office"
                        ]);
                        exit();
                    }

                    $_SESSION["user_id"] = $username;
                    $_SESSION["user_identity"] = $entry["identity"];
                    echo json_encode(["success" => true, "redirect_url" => $entry["redirect"]]);
                    exit();
                }
            }
        }

        echo json_encode(["success" => false, "message" => "Wrong Username or Password"]);
        exit();
    } catch (PDOException $error) {
        echo json_encode(["success" => false, "message" => 'Error: ' . $error->getMessage()]);
        exit();
    }
}
