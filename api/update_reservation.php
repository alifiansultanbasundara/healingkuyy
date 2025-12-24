<?php
include '../includes/config.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $reservation_id = sanitize($input['reservation_id']);
    $status = sanitize($input['status']);
    
    // Update reservation status
    $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?");
    if ($stmt->execute([$status, $reservation_id])) {
        logActivity('reservation_update', "Reservation $reservation_id updated to $status");
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
}
?>