<?php
/**
 * STUDENT - VIEW ASSIGNMENTS
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
$pageTitle = 'My Assignments';
require_once '../includes/header.php';

$courses = getStudentCourses($pdo, $current_user_id);
$selected_course_id = intval($_GET['course_id'] ?? 0);
$assignments = [];

if ($selected_course_id) {
    $assignments = getStudentCourseAssignments($pdo, $selected_course_id, $current_user_id);
}
?>

<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="/student/dashboard.php">🏠 Dashboard</a></li>
        <li><a href="/student/courses.php">📚 Courses</a></li>
        <li><a href="/student/assignments.php" class="active">📝 Assignments</a></li>
        <li><a href="/student/submit_assignment.php">📤 Submit Work</a></li>
        <li><a href="/index.php" style="color: #f59e0b;">🔄 Switch</a></li>
    </ul>
</div>

<div class="main-container">
    <h1>📝 My Assignments</h1>

    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0;">Select Course</h2>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Course</label>
                    <select name="course_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- All Courses --</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo $course['id']; ?>" <?php echo $selected_course_id == $course['id'] ? 'selected' : ''; ?>>
                                <?php echo sanitize($course['code']) . ' - ' . sanitize($course['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($assignments)): ?>
        <div class="card">
            <div class="card-header">
                <h2 style="margin: 0;">Assignments</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assignments as $assign): 
                                $submission = getStudentSubmission($pdo, $assign['id'], $current_user_id);
                                $status = $submission ? 'Submitted' : 'Pending';
                                $status_color = $submission ? 'bg-success' : 'bg-warning';
                            ?>
                                <tr>
                                    <td><?php echo sanitize($assign['title']); ?></td>
                                    <td><?php echo formatDateTime($assign['due_date']); ?></td>
                                    <td><span class="badge <?php echo $status_color; ?>"><?php echo $status; ?></span></td>
                                    <td>
                                        <a href="/student/submit_assignment.php?assignment_id=<?php echo $assign['id']; ?>" class="btn btn-sm btn-primary">
                                            <?php echo $submission ? 'Update' : 'Submit'; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <p class="text-muted text-center" style="padding: 40px;">Select a course to view assignments.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
