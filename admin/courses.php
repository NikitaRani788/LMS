<?php
/**
 * ADMIN - MANAGE COURSES
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
$pageTitle = 'Manage Courses';
require_once '../includes/header.php';

$message = '';
$error = '';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $course_id = intval($_POST['course_id']);
    try {
        $stmt = $pdo->prepare('DELETE FROM courses WHERE course_id = ?');
        $stmt->execute([$course_id]);
        $message = 'Course deleted successfully';
    } catch (Exception $e) {
        $error = 'Error deleting course';
    }
}

// Handle Add Course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = sanitize($_POST['name'] ?? '');
    $code = sanitize($_POST['code'] ?? '');
    $credits = intval($_POST['credits'] ?? 3);
    $dept_id = intval($_POST['department_id'] ?? 1);
    $description = sanitize($_POST['description'] ?? '');
    
    if ($name && $code && $credits > 0) {
        try {
            $stmt = $pdo->prepare('INSERT INTO courses (title, course_id, credits, department_id, description) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$name, $code, $credits, $dept_id, $description]);
            $message = 'Course added successfully';
        } catch (Exception $e) {
           $error = "Actual Error: " . $e->getMessage();
        }
    } else {
        $error = 'Please fill all required fields';
    }
}

$courses = getAllCourses($pdo);
$departments = getAllDepartments($pdo);
?>

<div class="page-header">
    <h1>📚 Manage Courses</h1>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
    
    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0;">Add New Course</h2>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="action" value="add">
                <div class="col-md-6">
                    <label class="form-label">Course Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Course Code</label>
                    <input type="text" name="code" class="form-control" placeholder="e.g. CS101" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Credits</label>
                    <input type="number" name="credits" class="form-control" value="3" min="1" max="5" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select" required>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo $dept['id']; ?>"><?php echo sanitize($dept['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Add Course</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0;">Courses List</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Credits</th>
                            <th>Department</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?php echo $course['id']; ?></td>
                                <td><?php echo sanitize($course['name']); ?></td>
                                <td><span class="badge bg-info"><?php echo sanitize($course['code']); ?></span></td>
                                <td><?php echo $course['credits']; ?></td>
                                <td><?php 
                                    $dept = getDepartmentById($pdo, $course['department_id']); 
                                    echo $dept ? sanitize($dept['name']) : 'N/A'; 
                                ?></td>
                                <td><?php echo formatDate($course['created_at']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete course?');">Delete</button>
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

