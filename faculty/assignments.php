<?php
/**
 * FACULTY - MANAGE ASSIGNMENTS
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
$pageTitle = 'Manage Assignments';
require_once '../includes/header.php';

$message = '';
$error = '';

// Get faculty courses
$courses = getFacultyCourses($pdo, $current_user_id);
$selected_course_id = intval($_GET['course_id'] ?? $_POST['course_id'] ?? 0);
$assignments = [];

if ($selected_course_id) {
    $assignments = getCourseAssignments($pdo, $selected_course_id);
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $assign_id = intval($_POST['assignment_id']);
    try {
        // Delete related submissions first
        $pdo->prepare('DELETE FROM submissions WHERE assignment_id = ?')->execute([$assign_id]);
        // Delete assignment
        $pdo->prepare('DELETE FROM assignments WHERE id = ?')->execute([$assign_id]);
        $message = 'Assignment deleted';
        $assignments = getCourseAssignments($pdo, $selected_course_id);
    } catch (Exception $e) {
        $error = 'Error deleting assignment';
    }
}

// Handle Add Assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $course_id = intval($_POST['course_id']);
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $due_date = sanitize($_POST['due_date'] ?? '');
    
    if (!$title || !$due_date) {
        $error = 'Please fill all required fields';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO assignments (course_id, title, description, due_date) VALUES (?, ?, ?, ?)');
            $stmt->execute([$course_id, $title, $description, $due_date]);
            $message = 'Assignment created';
            $assignments = getCourseAssignments($pdo, $course_id);
        } catch (Exception $e) {
            $error = 'Error creating assignment';
        }
    }
}
?>

<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="/faculty/dashboard.php">🏠 Dashboard</a></li>
        <li><a href="/faculty/courses.php">📚 Courses</a></li>
        <li><a href="/faculty/assignments.php" class="active">📝 Assignments</a></li>
        <li><a href="/faculty/submissions.php">📥 Submissions</a></li>
        <li><a href="/faculty/materials.php">📚 Materials</a></li>
        <li><a href="/index.php" style="color: #f59e0b;">🔄 Switch</a></li>
    </ul>
</div>

<div class="main-container">
    <h1>📝 Manage Assignments</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0;">Select Course</h2>
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
            </form>
        </div>
    </div>

    <?php if ($selected_course_id): ?>
        <div class="card">
            <div class="card-header">
                <h2 style="margin: 0;">Create New Assignment</h2>
            </div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="course_id" value="<?php echo $selected_course_id; ?>">
                    <div class="col-md-6">
                        <label class="form-label">Assignment Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g., Essay on Chapter 5" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Due Date</label>
                        <input type="datetime-local" name="due_date" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Assignment instructions..."></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">Create Assignment</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($assignments)): ?>
            <div class="card">
                <div class="card-header">
                    <h2 style="margin: 0;">Assignments (<?php echo count($assignments); ?>)</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Due Date</th>
                                    <th>Submissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignments as $assign): 
                                    $submissions = getAssignmentSubmissions($pdo, $assign['id']);
                                ?>
                                    <tr>
                                        <td><?php echo sanitize($assign['title']); ?></td>
                                        <td><?php echo formatDateTime($assign['due_date']); ?></td>
                                        <td><span class="badge bg-info"><?php echo count($submissions); ?></span></td>
                                        <td>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="assignment_id" value="<?php echo $assign['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?');">Delete</button>
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
                    <p class="text-muted text-center" style="padding: 20px;">No assignments yet.</p>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
