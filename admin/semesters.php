<?php
/**
 * ADMIN - MANAGE SEMESTERS
 */
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

// Verify admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}

$current_role = 'admin';
$current_user_id = $_SESSION['user_id'] ?? 1;
$pageTitle = 'Manage Semesters';
require_once '../includes/header.php';

$message = '';
$error = '';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $sem_id = intval($_POST['semester_id']);
    try {
        $pdo->prepare('DELETE FROM semester_courses WHERE semester_id = ?')->execute([$sem_id]);
        $pdo->prepare('DELETE FROM semesters WHERE id = ?')->execute([$sem_id]);
        $message = 'Semester deleted';
    } catch (Exception $e) {
        $error = 'Cannot delete semester with courses';
    }
}

// Handle Add Semester
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = sanitize($_POST['name'] ?? '');
    $year = intval($_POST['year'] ?? date('Y'));
    $start_date = sanitize($_POST['start_date'] ?? '');
    $end_date = sanitize($_POST['end_date'] ?? '');
    
    if ($name && $start_date && $end_date) {
        try {
            $stmt = $pdo->prepare('INSERT INTO semesters (name, year, start_date, end_date) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $year, $start_date, $end_date]);
            $message = 'Semester created';
        } catch (Exception $e) {
            $error = 'Error creating semester';
        }
    } else {
        $error = 'Please fill all fields';
    }
}

$semesters = getAllSemesters($pdo);
?>

<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="/admin/dashboard.php">🏠 Dashboard</a></li>
        <li><a href="/admin/users.php">👥 Users</a></li>
        <li><a href="/admin/courses.php">📚 Courses</a></li>
        <li><a href="/admin/semesters.php" class="active">📅 Semesters</a></li>
        <li><a href="/admin/announcements.php">📢 Announcements</a></li>
        <li><a href="/index.php" style="color: #f59e0b;">🔄 Switch</a></li>
    </ul>
</div>

<div class="main-container">
    <h1>📅 Manage Semesters</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0;">Create New Semester</h2>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="action" value="add">
                <div class="col-md-6">
                    <label class="form-label">Semester Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Spring" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Year</label>
                    <input type="number" name="year" class="form-control" value="<?php echo date('Y'); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Create Semester</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0;">Semesters</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Semester</th>
                            <th>Year</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($semesters as $sem): ?>
                            <tr>
                                <td><?php echo sanitize($sem['name']); ?></td>
                                <td><?php echo $sem['year']; ?></td>
                                <td><?php echo formatDate($sem['start_date']); ?></td>
                                <td><?php echo formatDate($sem['end_date']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="semester_id" value="<?php echo $sem['id']; ?>">
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
</div>

<?php require_once '../includes/footer.php'; ?>
