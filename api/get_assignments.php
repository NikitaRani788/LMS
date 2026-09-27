<?php
/**
 * Get Assignments API
 * Method: GET
 * Parameters: course_id (optional)
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

$response = ['success' => false, 'data' => []];

try {
    $course_id = intval($_GET['course_id'] ?? 0);
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'] ?? 'student';
    
    // Base query
    $sql = 'SELECT a.id, a.title, a.description, a.due_date, a.file_path, a.created_at,
                   c.name as course_name, c.code as course_code,
                   u.name as faculty_name,
                   (SELECT COUNT(*) FROM submissions s WHERE s.assignment_id = a.id AND s.user_id = ?) as submitted
            FROM assignments a
            JOIN courses c ON a.course_id = c.id
            JOIN users u ON a.faculty_id = u.id';
    
    $params = [$user_id];
    
    // Filter by role and course
    if ($role === 'student') {
        // Students can only see assignments for courses they're enrolled in
        $sql .= ' INNER JOIN enrollments e ON a.course_id = e.course_id AND e.user_id = ?';
        $params[] = $user_id;
        
        if ($course_id > 0) {
            $sql .= ' WHERE a.course_id = ?';
            $params[] = $course_id;
        }
    } elseif ($course_id > 0) {
        // Admin/Faculty can view by course filter
        $sql .= ' WHERE a.course_id = ?';
        $params[] = $course_id;
    }
    
    $sql .= ' ORDER BY a.due_date ASC, a.created_at DESC';
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format data
    foreach ($assignments as &$assignment) {
        $assignment['due_date_formatted'] = $assignment['due_date'] ? date('M d, Y', strtotime($assignment['due_date'])) : 'No due date';
        $assignment['created_at_formatted'] = date('M d, Y', strtotime($assignment['created_at']));
        $assignment['is_overdue'] = $assignment['due_date'] && strtotime($assignment['due_date']) < time();
    }
    
    $response['success'] = true;
    $response['data'] = $assignments;
    $response['count'] = count($assignments);
    
} catch (PDOException $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);