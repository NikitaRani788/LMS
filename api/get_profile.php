<?php
/**
 * Get User Profile API
 * Method: GET
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

$response = ['success' => false, 'data' => null];

try {
    $user_id = $_SESSION['user_id'];
    
    // Get user data
    $stmt = $pdo->prepare('SELECT id, name, email, role, department_id, created_at FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Get user settings
        $stmt = $pdo->prepare('SELECT * FROM user_settings WHERE user_id = ?');
        $stmt->execute([$user_id]);
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get department name
        $dept_name = '';
        if ($user['department_id']) {
            $stmt = $pdo->prepare('SELECT name FROM departments WHERE id = ?');
            $stmt->execute([$user['department_id']]);
            $dept = $stmt->fetch(PDO::FETCH_ASSOC);
            $dept_name = $dept['name'] ?? '';
        }
        
        $response['success'] = true;
        $response['data'] = [
            'user' => $user,
            'settings' => $settings ?: null,
            'department' => $dept_name
        ];
    } else {
        $response['message'] = 'User not found';
    }
    
} catch (PDOException $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);