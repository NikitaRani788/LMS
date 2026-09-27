<?php
/**
 * Create Notification API
 * Method: POST
 * Parameters: user_id, title, message, type (optional), link (optional)
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

// Only admin and faculty can create notifications
$allowed_roles = ['admin', 'faculty'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

$response = ['success' => false, 'message' => ''];

// Get input
$input = json_decode(file_get_contents('php://input'), true);
$user_id = intval($input['user_id'] ?? $_POST['user_id'] ?? 0);
$title = sanitize($input['title'] ?? $_POST['title'] ?? '');
$message = sanitize($input['message'] ?? $_POST['message'] ?? '');
$type = sanitize($input['type'] ?? $_POST['type'] ?? 'info');
$link = sanitize($input['link'] ?? $_POST['link'] ?? '');

// Validate
if ($user_id <= 0 || empty($title) || empty($message)) {
    $response['message'] = 'User ID, title and message are required';
    echo json_encode($response);
    exit;
}

// Validate type
$allowed_types = ['info', 'warning', 'success', 'danger'];
if (!in_array($type, $allowed_types)) {
    $type = 'info';
}

try {
    $stmt = $pdo->prepare('INSERT INTO notifications (user_id, title, message, type, link) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$user_id, $title, $message, $type, $link]);
    
    $response['success'] = true;
    $response['message'] = 'Notification created';
    $response['data'] = ['id' => $pdo->lastInsertId()];
    
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);