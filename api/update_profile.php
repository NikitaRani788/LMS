<?php
/**
 * Update User Profile API
 * Method: POST
 * Parameters: name, bio, phone, address, current_password, new_password
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
    if (!$input) {
        $input = $_POST;
    }
    
    $name = sanitize($input['name'] ?? '');
    $bio = sanitize($input['bio'] ?? '');
    $phone = sanitize($input['phone'] ?? '');
    $address = sanitize($input['address'] ?? '');
    $current_password = $input['current_password'] ?? '';
    $new_password = $input['new_password'] ?? '';
    
    // Validate name
    if (empty($name)) {
        $response['message'] = 'Name is required';
        echo json_encode($response);
        exit;
    }
    
    // Check if changing password
    if (!empty($new_password)) {
        if (empty($current_password)) {
            $response['message'] = 'Current password is required to set new password';
            echo json_encode($response);
            exit;
        }
        
        // Verify current password
        $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!password_verify($current_password, $user['password'])) {
            $response['message'] = 'Current password is incorrect';
            echo json_encode($response);
            exit;
        }
        
        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('UPDATE users SET name = ?, password = ? WHERE id = ?');
        $stmt->execute([$name, $hashed_password, $user_id]);
    } else {
        // Just update name
        $stmt = $pdo->prepare('UPDATE users SET name = ? WHERE id = ?');
        $stmt->execute([$name, $user_id]);
    }
    
    // Update or insert settings
    $stmt = $pdo->prepare('SELECT id FROM user_settings WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $exists = $stmt->fetch();
    
    if ($exists) {
        $stmt = $pdo->prepare('UPDATE user_settings SET bio = ?, phone = ?, address = ? WHERE user_id = ?');
        $stmt->execute([$bio, $phone, $address, $user_id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO user_settings (user_id, bio, phone, address) VALUES (?, ?, ?, ?)');
        $stmt->execute([$user_id, $bio, $phone, $address]);
    }
    
    $response['success'] = true;
    $response['message'] = 'Profile updated successfully';
    
} catch (PDOException $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);