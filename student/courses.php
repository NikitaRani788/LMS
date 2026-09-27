<?php
/**
 * STUDENT - MY COURSES
 */
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

// Verify student access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header('Location: /index.php');
    exit;
}

$current_role = 'student';
$current_user_id = $_SESSION['user_id'] ?? 3;
$pageTitle = 'My Courses';
require_once '../includes/header.php';

$courses = getStudentCourses($pdo, $current_user_id);
?>

<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="/student/dashboard.php">🏠 Dashboard</a></li>
        <li><a href="/student/courses.php" class="active">📚 Courses</a></li>
        <li><a href="/student/assignments.php">📝 Assignments</a></li>
        <li><a href="/student/submit_assignment.php">📤 Submit Work</a></li>
        <li><a href="/index.php" style="color: #f59e0b;">🔄 Switch</a></li>
    </ul>
</div>

<div class="main-container">
    <h1>📚 My Enrolled Courses</h1>

    <?php if (!empty($courses)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Department</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><span class="badge bg-info"><?php echo sanitize($course['code']); ?></span></td>
                            <td><?php echo sanitize($course['name']); ?></td>
                            <td><?php echo $course['credits']; ?></td>
                            <td><?php 
                                $dept = getDepartmentById($pdo, $course['department_id']); 
                                echo $dept ? sanitize($dept['name']) : 'N/A'; 
                            ?></td>
                            <td><?php echo sanitize(substr($course['description'], 0, 50)) . '...'; ?></td>
                            <td>
                                <a href="/student/assignments.php?course_id=<?php echo $course['id']; ?>" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <p class="text-muted text-center" style="padding: 40px;">You are not enrolled in any courses yet.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
