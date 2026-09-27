<?php
/**
 * Upload Assignment API
 * Method: POST
 * Parameters: title, description, course_id, due_date, file
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
$due_date = $_POST['due_date'] ?? '';

if (empty($title) || $course_id <= 0) {
    $response['message'] = 'Title and course are required';
    echo json_encode($response);
    exit;
}

// File handling
$file_path = '';
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['file'];
    
    // Allowed file types
    $allowed_ext = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'rar'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_ext, $allowed_ext)) {
        $response['message'] = 'File type not allowed';
        echo json_encode($response);
        exit;
    }
    
    // Create upload directory
    $upload_dir = __DIR__ . '/../uploads/assignments/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate unique filename
    $new_filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $file_ext;
    $target_path = $upload_dir . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $file_path = '/uploads/assignments/' . $new_filename;
    }
}

try {
    $stmt = $pdo->prepare('INSERT INTO assignments (title, description, course_id, faculty_id, due_date, file_path) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $title,
        $description,
        $course_id,
        $_SESSION['user_id'],
        $due_date ?: null,
        $file_path
    ]);
    
    $response['success'] = true;
    $response['message'] = 'Assignment created successfully';
    $response['data'] = ['id' => $pdo->lastInsertId()];
    
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);