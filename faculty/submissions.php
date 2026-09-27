<?php
/**
 * FACULTY - VIEW SUBMISSIONS
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
$pageTitle = 'Student Submissions';
require_once '../includes/header.php';

$courses = getFacultyCourses($pdo, $current_user_id);
$selected_course_id = intval($_GET['course_id'] ?? 0);
$assignments = [];
$submissions = [];

if ($selected_course_id) {
    $assignments = getCourseAssignments($pdo, $selected_course_id);
}

$selected_assignment_id = intval($_GET['assignment_id'] ?? 0);
if ($selected_assignment_id) {
    $submissions = getAssignmentSubmissions($pdo, $selected_assignment_id);
}

// Handle grading
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'grade') {
    $submission_id = intval($_POST['submission_id']);
    $grade = intval($_POST['grade']);
    $feedback = sanitize($_POST['feedback'] ?? '');
    
    if ($grade >= 0 && $grade <= 100) {
        try {
            $stmt = $pdo->prepare('UPDATE submissions SET grade = ?, feedback = ? WHERE id = ?');
            $stmt->execute([$grade, $feedback, $submission_id]);
            $message = 'Grade recorded successfully';
        } catch (Exception $e) {
            $message = 'Error saving grade';
        }
    }
}
?>

<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="/faculty/dashboard.php">🏠 Dashboard</a></li>
        <li><a href="/faculty/courses.php">📚 Courses</a></li>
        <li><a href="/faculty/assignments.php">📝 Assignments</a></li>
        <li><a href="/faculty/submissions.php" class="active">📥 Submissions</a></li>
        <li><a href="/index.php" style="color: #f59e0b;">🔄 Switch</a></li>
    </ul>
</div>

<div class="main-container">
    <h1>📥 Student Submissions</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0;">Select Course & Assignment</h2>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Course</label>
                    <select name="course_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Select Course --</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo $course['id']; ?>" <?php echo $selected_course_id == $course['id'] ? 'selected' : ''; ?>>
                                <?php echo sanitize($course['code']) . ' - ' . sanitize($course['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (!empty($assignments)): ?>
                <div class="col-md-6">
                    <label class="form-label">Assignment</label>
                    <select name="assignment_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Select Assignment --</option>
                        <?php foreach ($assignments as $assign): ?>
                            <option value="<?php echo $assign['id']; ?>" <?php echo $selected_assignment_id == $assign['id'] ? 'selected' : ''; ?>>
                                <?php echo sanitize($assign['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php if (!empty($submissions)): ?>
        <div class="card">
            <div class="card-header">
                <h2 style="margin: 0;">Submissions (<?php echo count($submissions); ?>)</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Submitted</th>
                                <th>Grade</th>
                                <th>Feedback</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($submissions as $sub): ?>
                                <tr>
                                    <td><?php echo sanitize($sub['student_name']); ?></td>
                                    <td><?php echo formatDateTime($sub['submitted_date']); ?></td>
                                    <td><?php echo $sub['grade'] !== null ? $sub['grade'] . '%' : '<span class="badge bg-warning">Not Graded</span>'; ?></td>
                                    <td><?php echo sanitize(substr($sub['feedback'], 0, 30)) ?? 'N/A'; ?></td>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="grade">
                                            <input type="hidden" name="submission_id" value="<?php echo $sub['id']; ?>">
                                            <input type="number" name="grade" class="form-control form-control-sm" value="<?php echo $sub['grade'] ?? ''; ?>" placeholder="Grade" min="0" max="100" style="width: 80px; display:inline; margin-right:5px;">
                                            <input type="text" name="feedback" class="form-control form-control-sm" value="<?php echo sanitize($sub['feedback']); ?>" placeholder="Feedback" style="width: 150px; display:inline; margin-right:5px;">
                                            <button type="submit" class="btn btn-sm btn-success">Save</button>
                                        </form>
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
                <p class="text-muted text-center" style="padding: 40px;">Select course and assignment to view submissions.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
