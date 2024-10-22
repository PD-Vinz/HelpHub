<?php
// File: get_ticket_details.php

include_once("../connection/conn.php");
$pdoConnect = connection();

header('Content-Type: application/json');

if (isset($_GET['ticket_id']) && isset($_GET['status'])) {
    $ticket_id = $_GET['ticket_id'];
    $status = $_GET['status'];

    try {
        // Fetch ticket details
        $query = "SELECT * FROM tb_tickets WHERE ticket_id = :ticket_id";
        $stmt = $pdoConnect->prepare($query);
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->execute();
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ticket) {
            // Generate HTML based on the status
            $html = '';
            switch ($status) {
                case 'Pending':
                    $html = generatePendingHTML($ticket);
                    break;
                case 'Processing':
                    $html = generateProcessingHTML($ticket);
                    break;
                case 'Returned':
                    $html = generateReturnedHTML($ticket);
                    break;
                case 'Resolved':
                    $html = generateResolvedHTML($ticket);
                    break;
                default:
                    $html = "<p>No details available for this status.</p>";
            }
            echo json_encode(['success' => true, 'html' => $html, 'status' => $status, 'ticket_id' => $ticket_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ticket not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

function generatePendingHTML($ticket) {
    $html = "<p><strong>Ticket ID:</strong> " . htmlspecialchars($ticket['ticket_id']) . "</p>";
    $html .= "<p><strong>Full Name:</strong> " . htmlspecialchars($ticket['full_name']) . "</p>";
    $html .= "<p><strong>Issue:</strong> " . htmlspecialchars($ticket['issue']) . "</p>";
    $html .= "<p><strong>Description:</strong> " . htmlspecialchars($ticket['description']) . "</p>";
    return $html;
}

function generateProcessingHTML($ticket) {
    $html = "<p><strong>Ticket ID:</strong> " . htmlspecialchars($ticket['ticket_id']) . "</p>";
    $html .= "<p><strong>Full Name:</strong> " . htmlspecialchars($ticket['full_name']) . "</p>";
    $html .= "<p><strong>Issue:</strong> " . htmlspecialchars($ticket['issue']) . "</p>";
    $html .= "<p><strong>Description:</strong> " . htmlspecialchars($ticket['description']) . "</p>";
    $html .= "<p><strong>Employee:</strong> " . htmlspecialchars($ticket['employee']) . "</p>";
    return $html;
}

function generateReturnedHTML($ticket) {
    $html = "<p><strong>Ticket ID:</strong> " . htmlspecialchars($ticket['ticket_id']) . "</p>";
    $html .= "<p><strong>Full Name:</strong> " . htmlspecialchars($ticket['full_name']) . "</p>";
    $html .= "<p><strong>Issue:</strong> " . htmlspecialchars($ticket['issue']) . "</p>";
    $html .= "<p><strong>Description:</strong> " . htmlspecialchars($ticket['description']) . "</p>";
    $html .= "<p><strong>Return Reason:</strong> " . htmlspecialchars($ticket['resolution']) . "</p>";
    return $html;
}

function generateResolvedHTML($ticket) {
    $html = "<p><strong>Ticket ID:</strong> " . htmlspecialchars($ticket['ticket_id']) . "</p>";
    $html .= "<p><strong>Full Name:</strong> " . htmlspecialchars($ticket['full_name']) . "</p>";
    $html .= "<p><strong>Issue:</strong> " . htmlspecialchars($ticket['issue']) . "</p>";
    $html .= "<p><strong>Description:</strong> " . htmlspecialchars($ticket['description']) . "</p>";
    $html .= "<p><strong>Resolution:</strong> " . htmlspecialchars($ticket['resolution']) . "</p>";
    $html .= "<p><strong>Resolved By:</strong> " . htmlspecialchars($ticket['employee']) . "</p>";
    $html .= "<p><strong>Resolved Date:</strong> " . htmlspecialchars($ticket['finished_date']) . "</p>";
    return $html;
}
?>