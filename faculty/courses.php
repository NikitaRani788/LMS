<?php
/**
 * FACULTY - MY COURSES
 */
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

// Verify faculty access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header('Location: /index.php');
    exit;
}

$current_role = 'faculty';
$current_user_id = $_SESSION['user_id'] ?? 2;
$pageTitle = 'My Courses';
require_once '../includes/header.php';

$courses = getFacultyCourses($pdo, $current_user_id);
?>

<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="/faculty/dashboard.php">🏠 Dashboard</a></li>
        <li><a href="/faculty/courses.php" class="active">📚 Courses</a></li>
        <li><a href="/faculty/assignments.php">📝 Assignments</a></li>
        <li><a href="/faculty/submissions.php">📥 Submissions</a></li>
        <li><a href="/index.php" style="color: #f59e0b;">🔄 Switch</a></li>
    </ul>
</div>

<div class="main-container">
    <h1>📚 My Courses</h1>

    <?php if (!empty($courses)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Credits</th>
                        <th>Department</th>
                        <th>Students</th>
                        <th>Assignments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): 
                        $enrollments = getCourseEnrollments($pdo, $course['id']);
                        $assignments = getCourseAssignments($pdo, $course['id']);
                    ?>
                        <tr>
                            <td><span class="badge bg-info"><?php echo sanitize($course['code']); ?></span></td>
                            <td><?php echo sanitize($course['name']); ?></td>
                            <td><?php echo $course['credits']; ?></td>
                            <td><?php 
                                $dept = getDepartmentById($pdo, $course['department_id']); 
                                echo $dept ? sanitize($dept['name']) : 'N/A'; 
                            ?></td>
                            <td><?php echo count($enrollments); ?></td>
                            <td><?php echo count($assignments); ?></td>
                            <td>
                                <a href="/faculty/assignments.php?course_id=<?php echo $course['id']; ?>" class="btn btn-sm btn-primary">Manage</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <p class="text-muted text-center" style="padding: 40px;">No courses assigned to you.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
