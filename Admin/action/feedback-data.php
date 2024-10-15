<?php

header('Content-Type: application/json');
include_once("../../connection/conn.php");
$pdoConnect = connection();

try {
    // Get the chart type from the query parameter
    $chartType = $_GET['chart'] ?? '';

    // Define the SQL query based on the chart type
    switch ($chartType) {
        case 'cc1':
 
            $stmt = $pdoConnect->prepare("
                SELECT 
                    CASE 
                        WHEN cc1 = 'a' THEN 'I know what a Citizen\'s Charter is and I saw the office\'s Citizen\'s Charter.'
                        WHEN cc1 = 'b' THEN 'I know what a Citizen\'s Charter is but I did NOT see the office\'s Citizen\'s Charter.'
                        WHEN cc1 = 'c' THEN 'I learned of the Citizen\'s Charter only when I saw the office\'s Citizen\'s Charter.'
                        WHEN cc1 = 'd' THEN 'I do not know what a Citizen\'s Charter is and I did not see one in this office.'
                    END AS label,
                    COUNT(*) as value 
                FROM tb_survey_feedback 
                WHERE cc1 IN ('a', 'b', 'c', 'd')
                GROUP BY cc1
            ");
            break;
            case 'cc2':
              
                $stmt = $pdoConnect->prepare("
                SELECT 
                    CASE 
                        WHEN cc2 = 'a' THEN 'Easy to see'
                        WHEN cc2 = 'b' THEN 'Somewhat easy to see'
                        WHEN cc2 = 'c' THEN 'Difficult to see'
                        WHEN cc2 = 'd' THEN 'Not visible at all'
                        when cc2 = 'e' THEN 'N/A'
                    END AS label,
                    COUNT(*) as value 
                FROM tb_survey_feedback 
                WHERE cc2 IN ('a', 'b', 'c', 'd', 'e')
                GROUP BY cc2
            ");
            break;
        case 'cc3':
             
             $stmt = $pdoConnect->prepare("
             SELECT 
                 CASE 
                     WHEN cc3 = 'a' THEN 'Helped very much'
                     WHEN cc3 = 'b' THEN 'Somewhat helped'
                     WHEN cc3 = 'c' THEN 'Did not help'
                     WHEN cc3 = 'd' THEN 'N/A'
                 END AS label,
                 COUNT(*) as value 
             FROM tb_survey_feedback 
             WHERE cc3 IN ('a', 'b', 'c', 'd')
             GROUP BY cc3
         ");
         break;
        case 'sqd0':
        case 'sqd1':
        case 'sqd2':
        case 'sqd3':
        case 'sqd4':
        case 'sqd6':
        case 'sqd7':
        case 'sqd8':
            // Dynamically use the column name for the feedback data
            $stmt = $pdoConnect->prepare("SELECT $chartType AS label, COUNT(*) as value FROM tb_survey_feedback GROUP BY $chartType");
            break;

        default:
            // Return an empty array if the chart type is not recognized
            echo json_encode([]);
            exit;
    }

    // Execute the query and fetch the data
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
