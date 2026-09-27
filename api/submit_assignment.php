<?php
/**
 * Submit Assignment API
 * Method: POST
 * Parameters: assignment_id, file
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

// Check role (student only)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

$response = ['success' => false, 'message' => ''];

// Validate required fields
$assignment_id = intval($_POST['assignment_id'] ?? 0);

if ($assignment_id <= 0) {
    $response['message'] = 'Assignment ID is required';
    echo json_encode($response);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $response['message'] = 'No file uploaded or upload error';
    echo json_encode($response);
    exit;
}

$file = $_FILES['file'];

// Allowed file types
$allowed_ext = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'jpg', 'jpeg', 'png'];
$file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($file_ext, $allowed_ext)) {
    $response['message'] = 'File type not allowed';
    echo json_encode($response);
    exit;
}

// Create upload directory
$upload_dir = __DIR__ . '/../uploads/submissions/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Generate unique filename
$new_filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $file_ext;
$target_path = $upload_dir . $new_filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $target_path)) {
    try {
        // Check if already submitted
        $stmt = $pdo->prepare('SELECT id FROM submissions WHERE assignment_id = ? AND user_id = ?');
        $stmt->execute([$assignment_id, $_SESSION['user_id']]);
        
        if ($stmt->fetch()) {
            // Update existing submission
            $stmt = $pdo->prepare('UPDATE submissions SET file_path = ?, submitted_at = NOW() WHERE assignment_id = ? AND user_id = ?');
            $stmt->execute(['/uploads/submissions/' . $new_filename, $assignment_id, $_SESSION['user_id']]);
            $response['message'] = 'Assignment resubmitted successfully';
        } else {
            // Insert new submission
            $stmt = $pdo->prepare('INSERT INTO submissions (assignment_id, user_id, file_path) VALUES (?, ?, ?)');
            $stmt->execute([$assignment_id, $_SESSION['user_id'], '/uploads/submissions/' . $new_filename]);
            $response['message'] = 'Assignment submitted successfully';
        }
        
        $response['success'] = true;
        $response['data'] = ['id' => $pdo->lastInsertId()];
        
    } catch (PDOException $e) {
        // Delete uploaded file on database error
        unlink($target_path);
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Failed to save file';
}

echo json_encode($response);