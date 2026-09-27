<?php
/**
 * Get Notifications API
 * Method: GET
 * Parameters: unread_only (optional, default false)
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

$response = ['success' => false, 'data' => [], 'unread_count' => 0];

try {
    $user_id = $_SESSION['user_id'];
    $unread_only = isset($_GET['unread_only']) && $_GET['unread_only'] == '1';
    
    // Get unread count
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0');
    $stmt->execute([$user_id]);
    $response['unread_count'] = $stmt->fetchColumn();
    
    // Get notifications
    $sql = 'SELECT id, title, message, type, is_read, link, created_at 
            FROM notifications 
            WHERE user_id = ?';
    
    if ($unread_only) {
        $sql .= ' AND is_read = 0';
    }
    
    $sql .= ' ORDER BY created_at DESC LIMIT 50';
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format dates
    foreach ($notifications as &$notif) {
        $notif['created_at_formatted'] = timeAgo($notif['created_at']);
    }
    
    $response['success'] = true;
    $response['data'] = $notifications;
    
} catch (PDOException $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);

/**
 * Format time ago
 */
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' min ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' days ago';
    } else {
        return date('M d, Y', $timestamp);
    }
}