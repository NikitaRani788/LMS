<?php
/**
 * Upload Study Material API
 * Method: POST
 * Parameters: title, description, course_id, file
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

// Check role (admin or faculty only)
$allowed_roles = ['admin', 'faculty'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

$response = ['success' => false, 'message' => ''];

// Validate required fields
$title = sanitize($_POST['title'] ?? '');
$description = sanitize($_POST['description'] ?? '');
$course_id = intval($_POST['course_id'] ?? 0);

if (empty($title) || $course_id <= 0) {
    $response['message'] = 'Title and course are required';
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
$allowed_types = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'image/jpeg',
    'image/png',
    'image/gif',
    'text/plain'
];

$file_type = $file['type'];
$file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

// Check file extension
$allowed_ext = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'txt'];

if (!in_array($file_ext, $allowed_ext)) {
    $response['message'] = 'File type not allowed';
    echo json_encode($response);
    exit;
}

// Create upload directory if not exists
$upload_dir = __DIR__ . '/../uploads/materials/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Generate unique filename
$new_filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $file_ext;
$target_path = $upload_dir . $new_filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $target_path)) {
    try {
        $stmt = $pdo->prepare('INSERT INTO study_materials (title, description, file_path, file_type, file_size, course_id, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $title,
            $description,
            '/uploads/materials/' . $new_filename,
            $file_type,
            $file['size'],
            $course_id,
            $_SESSION['user_id']
        ]);
        
        $response['success'] = true;
        $response['message'] = 'Material uploaded successfully';
        $response['data'] = ['id' => $pdo->lastInsertId(), 'file_path' => '/uploads/materials/' . $new_filename];
    } catch (PDOException $e) {
        // Delete uploaded file on database error
        unlink($target_path);
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Failed to save file';
}

echo json_encode($response);