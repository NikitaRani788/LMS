<?php
/**
 * Get Study Materials API
 * Method: GET
 * Parameters: course_id (optional), user_id (for students)
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);
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

$response = ['success' => false, 'data' => []];

try {
    $course_id = intval($_GET['course_id'] ?? 0);
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'] ?? 'student';
    
    // Base query
    $sql = 'SELECT sm.id, sm.title, sm.description, sm.file_path, sm.file_type, sm.file_size, 
                   sm.created_at, c.name as course_name, u.name as uploaded_by_name
            FROM study_materials sm
            JOIN courses c ON sm.course_id = c.id
            JOIN users u ON sm.uploaded_by = u.id';
    
    $params = [];
    $conditions = [];
    
    if ($role === 'student') {
        // Students can only see materials for courses they're enrolled in
        $sql .= ' JOIN enrollments e ON sm.course_id = e.course_id AND e.user_id = ?';
        $params[] = $user_id;
        
        if ($course_id > 0) {
            $conditions[] = 'sm.course_id = ?';
            $params[] = $course_id;
        }
    } elseif ($course_id > 0) {
        // Admin/Faculty can view by course filter
        $conditions[] = 'sm.course_id = ?';
        $params[] = $course_id;
    }
    
    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }
    
    $sql .= ' ORDER BY sm.created_at DESC';
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format file size
    foreach ($materials as &$material) {
        $material['file_size_formatted'] = formatFileSize($material['file_size']);
        $material['created_at_formatted'] = date('M d, Y', strtotime($material['created_at']));
    }
    
    $response['success'] = true;
    $response['data'] = $materials;
    $response['count'] = count($materials);
    
} catch (PDOException $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);

/**
 * Format file size to human readable
 */
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < 3) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}