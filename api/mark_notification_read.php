<?php
/**
 * Mark Notification as Read API
 * Method: POST
 * Parameters: notification_id (optional, marks single), mark_all (optional, marks all)
 */

header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Check authentication
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$response = ['success' => false, 'message' => ''];

try {
    $user_id = $_SESSION['user_id'];
    $input = json_decode(file_get_contents('php://input'), true);
    
    $notification_id = intval($input['notification_id'] ?? $_POST['notification_id'] ?? 0);
    $mark_all = isset($input['mark_all']) ? (bool)$input['mark_all'] : (isset($_POST['mark_all']) && $_POST['mark_all'] == '1');
    
    if ($mark_all) {
        // Mark all as read
        $stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0');
        $stmt->execute([$user_id]);
        
        $response['success'] = true;
        $response['message'] = 'All notifications marked as read';
        
    } elseif ($notification_id > 0) {
        // Mark single as read
        $stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?');
        $stmt->execute([$notification_id, $user_id]);
        
        $response['success'] = true;
        $response['message'] = 'Notification marked as read';
        
    } else {
        $response['message'] = 'Invalid request';
    }
    
} catch (PDOException $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);