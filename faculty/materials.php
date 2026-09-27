<?php
/**
 * FACULTY - STUDY MATERIALS
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
$pageTitle = 'Study Materials';
require_once '../includes/header.php';

$message = '';
$error = '';
$upload_dir = '../uploads/materials/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$courses = getFacultyCourses($pdo, $current_user_id);
$selected_course_id = intval($_GET['course_id'] ?? $_POST['course_id'] ?? 0);
$materials = [];

if ($selected_course_id) {
    $materials = getCourseMaterials($pdo, $selected_course_id);
}

// Handle Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload') {
    $course_id = intval($_POST['course_id']);
    $title = sanitize($_POST['title'] ?? '');
    
    if (!$title) {
        $error = 'Please enter material title';
    } elseif (!isset($_FILES['material_file']) || $_FILES['material_file']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = 'Please select a file';
    } else {
        $file = $_FILES['material_file'];
        if (isValidFile($file, ['pdf', 'doc', 'docx', 'txt', 'pptx', 'xlsx'], 52428800)) { // 50MB for materials
            $filepath = saveUploadedFile($file, $upload_dir);
            
            if ($filepath) {
                try {
                    $stmt = $pdo->prepare('INSERT INTO notes (course_id, faculty_id, title, file_path) VALUES (?, ?, ?, ?)');
                    $stmt->execute([$course_id, $current_user_id, $title, $filepath]);
                    $message = 'Material uploaded successfully';
                    $materials = getCourseMaterials($pdo, $course_id);
                } catch (Exception $e) {
                    deleteFile($filepath);
                    $error = 'Error saving material';
                }
            } else {
                $error = 'Error uploading file';
            }
        } else {
            $error = 'Invalid file format or size (max 50MB)';
        }
    }
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $material_id = intval($_POST['material_id']);
    try {
        $result = $pdo->prepare('SELECT file_path FROM notes WHERE id = ?')->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            deleteFile($result[0]['file_path']);
        }
        $pdo->prepare('DELETE FROM notes WHERE id = ?')->execute([$material_id]);
        $message = 'Material deleted';
        $materials = getCourseMaterials($pdo, $selected_course_id);
    } catch (Exception $e) {
        $error = 'Error deleting material';
    }
}
?>
<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="/faculty/dashboard.php">🏠 Dashboard</a></li>
        <li><a href="/faculty/courses.php">📚 Courses</a></li>
        <li><a href="/faculty/assignments.php">📝 Assignments</a></li>
        <li><a href="/faculty/submissions.php">📥 Submissions</a></li>
        <li><a href="/faculty/materials.php" class="active">📚 Materials</a></li>
        <li><a href="/index.php" style="color: #f59e0b;">🔄 Switch</a></li>
    </ul>
</div>

<div class="main-container">
    <h1>📚 Study Materials</h1>

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
                <h2 style="margin: 0;">Upload Material</h2>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" class="row g-3">
                    <input type="hidden" name="action" value="upload">
                    <input type="hidden" name="course_id" value="<?php echo $selected_course_id; ?>">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g., Chapter 1 Notes" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">File (PDF, DOC, DOCX, PPTX, Excel - Max 50MB)</label>
                        <input type="file" name="material_file" class="form-control" accept=".pdf,.doc,.docx,.pptx,.xlsx" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">Upload Material</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($materials)): ?>
            <div class="card">
                <div class="card-header">
                    <h2 style="margin: 0;">Materials (<?php echo count($materials); ?>)</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Uploaded</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materials as $mat): ?>
                                    <tr>
                                        <td><?php echo sanitize($mat['title']); ?></td>
                                        <td><?php echo formatDate($mat['created_at']); ?></td>
                                        <td>
                                            <a href="<?php echo $mat['file_path']; ?>" class="btn btn-sm btn-info" download>Download</a>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="material_id" value="<?php echo $mat['id']; ?>">
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
                    <p class="text-muted text-center" style="padding: 20px;">No materials uploaded yet.</p>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
