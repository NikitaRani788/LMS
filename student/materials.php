<?php
/**
 * STUDENT - VIEW STUDY MATERIALS
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
$pageTitle = 'Study Materials';
require_once '../includes/header.php';

// Get all materials for enrolled courses
$stmt = $pdo->prepare('
    SELECT n.*, c.name as course_name, c.code
    FROM notes n
    JOIN courses c ON n.course_id = c.id
    JOIN enrollments e ON c.id = e.course_id
    WHERE e.user_id = ?
    ORDER BY n.created_at DESC
');
$stmt->execute([$current_user_id]);
$materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by course
$materials_by_course = [];
foreach ($materials as $mat) {
    if (!isset($materials_by_course[$mat['course_name']])) {
        $materials_by_course[$mat['course_name']] = [];
    }
    $materials_by_course[$mat['course_name']][] = $mat;
}
?>

<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="/student/dashboard.php">🏠 Dashboard</a></li>
        <li><a href="/student/courses.php">📚 Courses</a></li>
        <li><a href="/student/assignments.php">📝 Assignments</a></li>
        <li><a href="/student/submit_assignment.php">📤 Submit Work</a></li>
        <li><a href="/student/grades.php">⭐ Grades</a></li>
        <li><a href="/student/materials.php" class="active">📚 Materials</a></li>
        <li><a href="/index.php" style="color: #f59e0b;">🔄 Switch</a></li>
    </ul>
</div>

<div class="main-container">
    <h1>📚 Study Materials</h1>

    <?php if (!empty($materials_by_course)): ?>
        <?php foreach ($materials_by_course as $course_name => $mats): ?>
            <div class="card">
                <div class="card-header">
                    <h2 style="margin: 0;"><?php echo sanitize($course_name); ?></h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Material Title</th>
                                    <th>Upload Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mats as $mat): ?>
                                    <tr>
                                        <td><?php echo sanitize($mat['title']); ?></td>
                                        <td><?php echo formatDate($mat['created_at']); ?></td>
                                        <td>
                                            <a href="<?php echo $mat['file_path']; ?>" class="btn btn-sm btn-primary" download>Download</a>
                                        </td>
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
                <p class="text-muted text-center" style="padding: 40px;">No study materials available yet.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
