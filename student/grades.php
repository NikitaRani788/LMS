<?php
/**
 * STUDENT - VIEW GRADES
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
$pageTitle = 'My Grades';
require_once '../includes/header.php';

// Get all student submissions with grades
$stmt = $pdo->prepare('
    SELECT s.*, a.title as assignment_title, a.due_date, c.name as course_name, c.code
    FROM submissions s
    JOIN assignments a ON s.assignment_id = a.id
    JOIN courses c ON a.course_id = c.id
    WHERE s.user_id = ?
    ORDER BY c.id, a.due_date DESC
');
$stmt->execute([$current_user_id]);
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by course
$graded_submissions = [];
foreach ($submissions as $sub) {
    if (!isset($graded_submissions[$sub['course_name']])) {
        $graded_submissions[$sub['course_name']] = [];
    }
    $graded_submissions[$sub['course_name']][] = $sub;
}
?>

<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="/student/dashboard.php">🏠 Dashboard</a></li>
        <li><a href="/student/courses.php">📚 Courses</a></li>
        <li><a href="/student/assignments.php">📝 Assignments</a></li>
        <li><a href="/student/submit_assignment.php">📤 Submit Work</a></li>
        <li><a href="/student/grades.php" class="active">⭐ Grades</a></li>
        <li><a href="/student/materials.php">📚 Materials</a></li>
        <li><a href="/index.php" style="color: #f59e0b;">🔄 Switch</a></li>
    </ul>
</div>

<div class="main-container">
    <h1>⭐ My Grades & Feedback</h1>

    <?php if (!empty($graded_submissions)): ?>
        <?php foreach ($graded_submissions as $course_name => $subs): ?>
            <div class="card">
                <div class="card-header">
                    <h2 style="margin: 0;"><?php echo sanitize($course_name); ?></h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Assignment</th>
                                    <th>Due Date</th>
                                    <th>Submitted</th>
                                    <th>Grade</th>
                                    <th>Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subs as $sub): ?>
                                    <tr>
                                        <td><?php echo sanitize($sub['assignment_title']); ?></td>
                                        <td><?php echo formatDateTime($sub['due_date']); ?></td>
                                        <td><?php echo formatDateTime($sub['submitted_date']); ?></td>
                                        <td>
                                            <?php if ($sub['grade'] !== null): ?>
                                                <span class="badge bg-success"><?php echo $sub['grade']; ?>%</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Not Graded</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo sanitize($sub['feedback']) ?: 'N/A'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <p class="text-muted text-center" style="padding: 40px;">No grades available yet.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
