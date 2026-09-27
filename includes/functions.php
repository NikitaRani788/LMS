<?php
/**
 * UTILITY FUNCTIONS FOR if0_41817906_lms
 * Role-based system without login
 */

// Sanitize input - prevent XSS
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Format date nicely
function formatDate($date) {
    if (empty($date)) return 'N/A';
    return date('M d, Y', strtotime($date));
}

// Format date and time
function formatDateTime($datetime) {
    if (empty($datetime)) return 'N/A';
    return date('M d, Y h:i A', strtotime($datetime));
}

// Get all users
function getAllUsers($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM users ORDER BY name ASC');
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get users by role
function getUsersByRole($pdo, $role) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE role = ? ORDER BY name ASC');
    $stmt->execute([$role]);
    return $stmt->fetchAll();
}

// Get user by ID
function getUserById($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE userid = ?');
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

// Get all courses
function getAllCourses($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM courses ORDER BY name ASC');
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get course by ID
function getCourseById($pdo, $courseId) {
    $stmt = $pdo->prepare('SELECT * FROM courses WHERE id = ?');
    $stmt->execute([$courseId]);
    return $stmt->fetch();
}

// Get all departments
function getAllDepartments($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM departments ORDER BY name ASC');
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get department by ID
function getDepartmentById($pdo, $deptId) {
    $stmt = $pdo->prepare('SELECT * FROM departments WHERE id = ?');
    $stmt->execute([$deptId]);
    return $stmt->fetch();
}

// Get all announcements
       function getAnnouncements($pdo, $limit = 10) {
    $stmt = $pdo->prepare('
        SELECT * 
        FROM announcements
        ORDER BY created_at DESC LIMIT ?
    ');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Get all semesters
function getAllSemesters($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM semesters ORDER BY year DESC, name DESC');
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get semester by ID
function getSemesterById($pdo, $semesterId) {
    $stmt = $pdo->prepare('SELECT * FROM semesters WHERE id = ?');
    $stmt->execute([$semesterId]);
    return $stmt->fetch();
}

// Get faculty assigned courses
function getFacultyCourses($pdo, $facultyId) {
    $stmt = $pdo->prepare('
        SELECT * 
        FROM courses
        ORDER BY name ASC
    ');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get student enrolled courses
function getStudentCourses($pdo, $studentId) {
    $stmt = $pdo->prepare('
        SELECT c.* FROM courses c
        INNER JOIN enrollments e ON c.id = e.course_id
        WHERE e.user_id = ?
        ORDER BY c.name ASC
    ');
    $stmt->execute([$studentId]);
    return $stmt->fetchAll();
}

// Get assignments for a course
function getCourseAssignments($pdo, $courseId) {
    $stmt = $pdo->prepare('
        SELECT * FROM assignments 
        WHERE course_id = ?
        ORDER BY due_date ASC
    ');
    $stmt->execute([$courseId]);
    return $stmt->fetchAll();
}

// Get assignment by ID
function getAssignmentById($pdo, $assignmentId) {
    $stmt = $pdo->prepare('SELECT * FROM assignments WHERE assignment_id = ?');
    $stmt->execute([$assignmentId]);
    return $stmt->fetch();
}

// Get student submissions for assignment
function getAssignmentSubmissions($pdo, $assignmentId) {
    $stmt = $pdo->prepare('
        SELECT s.*, u.name as student_name 
        FROM submissions s
        JOIN users u ON s.user_id = u.userid
        WHERE s.assignment_id = ?
        ORDER BY s.submitted_date DESC
    ');
    $stmt->execute([$assignmentId]);
    return $stmt->fetchAll();
}

// Get student submission for assignment
function getStudentSubmission($pdo, $assignmentId, $studentId) {
    $stmt = $pdo->prepare('
        SELECT * FROM submissions 
        WHERE assignment_id = ? AND user_id = ?
    ');
    $stmt->execute([$assignmentId, $studentId]);
    return $stmt->fetch();
}

// Get student assignments for a course
function getStudentCourseAssignments($pdo, $courseId, $studentId) {
    $stmt = $pdo->prepare('
        SELECT a.* FROM assignments a
        WHERE a.course_id = ?
        ORDER BY a.due_date ASC
    ');
    $stmt->execute([$courseId]);
    return $stmt->fetchAll();
}

// Check if student is enrolled in course
function isStudentEnrolled($pdo, $studentId, $courseId) {
    $stmt = $pdo->prepare('
        SELECT COUNT(*) as count FROM enrollments 
        WHERE user_id = ? AND course_id = ?
    ');
    $stmt->execute([$studentId, $courseId]);
    $result = $stmt->fetch();
    return $result['count'] > 0;
}

// Get all enrollments in a course
function getCourseEnrollments($pdo, $courseId) {
    $stmt = $pdo->prepare('
        SELECT u.*, e.enrollment_date FROM users u
        INNER JOIN enrollments e ON u.userid = e.user_id
        WHERE e.course_id = ?
        ORDER BY u.name ASC
    ');
    $stmt->execute([$courseId]);
    return $stmt->fetchAll();
}

// Format file size
function formatFileSize($bytes) {
    // Debug safety
    if (!isset($bytes) || !is_numeric($bytes)) {
        return '0 Bytes';
    }

    $bytes = (int)$bytes;

    if ($bytes == 0) return '0 Bytes';

    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' Bytes';
    }
}

// Check if uploaded file is valid
function isValidFile($file, $allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'zip'], $maxSize = 10485760) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExtensions)) {
        return false;
    }
    
    if ($file['size'] > $maxSize) {
        return false;
    }
    
    return true;
}

// Save uploaded file
function saveUploadedFile($file, $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
    
    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._]/', '_', $file['name']);
    $filepath = $directory . '/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filepath;
    }
    
    return false;
}

// Delete file
function deleteFile($filepath) {
    if (file_exists($filepath) && is_file($filepath)) {
        return unlink($filepath);
    }
    return false;
}

// Get course materials (notes)
function getCourseMaterials($pdo, $courseId) {
    $stmt = $pdo->prepare('SELECT * FROM notes WHERE course_id = ? ORDER BY created_at DESC');
    $stmt->execute([$courseId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>