<?php
/**
 * STUDENT - SUBMIT ASSIGNMENT
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
$pageTitle = 'Submit Assignment';
require_once '../includes/header.php';

$message = '';
$error = '';
$upload_dir = '../uploads/submissions/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Handle submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit') {
    $assignment_id = intval($_POST['assignment_id']);
    $assignment = getAssignmentById($pdo, $assignment_id);
    
    if (!$assignment) {
        $error = 'Assignment not found';
    } elseif (!isset($_FILES['submission_file']) || $_FILES['submission_file']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = 'Please select a file';
    } else {
        $file = $_FILES['submission_file'];
        if (isValidFile($file, ['pdf', 'doc', 'docx', 'txt', 'xlsx', 'pptx'], 10485760)) {
            $filepath = saveUploadedFile($file, $upload_dir);
            
            if ($filepath) {
                try {
                    // Check if already submitted
                    $existing = getStudentSubmission($pdo, $assignment_id, $current_user_id);
                    
                    if ($existing) {
                        // Delete old file
                        deleteFile($existing['file_path']);
                        // Update
                        $stmt = $pdo->prepare('UPDATE submissions SET file_path = ?, submitted_date = NOW() WHERE assignment_id = ? AND user_id = ?');
                        $stmt->execute([$filepath, $assignment_id, $current_user_id]);
                        $message = 'Assignment updated successfully';
                    } else {
                        // Insert new
                        $stmt = $pdo->prepare('INSERT INTO submissions (assignment_id, user_id, file_path) VALUES (?, ?, ?)');
                        $stmt->execute([$assignment_id, $current_user_id, $filepath]);
                        $message = 'Assignment submitted successfully';
                    }
                } catch (Exception $e) {
                    deleteFile($filepath);
                    $error = 'Error saving submission';
                }
            } else {
                $error = 'Error uploading file';
            }
        } else {
            $error = 'Invalid file format or size (max 10MB)';
        }
    }
}

$courses = getStudentCourses($pdo, $current_user_id);
$assignments = [];
$selected_course_id = intval($_GET['course_id'] ?? $_POST['course_id'] ?? 0);

if ($selected_course_id) {
    $assignments = getStudentCourseAssignments($pdo, $selected_course_id, $current_user_id);
}
?>

<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="/student/dashboard.php">🏠 Dashboard</a></li>
        <li><a href="/student/courses.php">📚 Courses</a></li>
        <li><a href="/student/assignments.php">📝 Assignments</a></li>
        <li><a href="/student/submit_assignment.php" class="active">📤 Submit</a></li>
        <li><a href="/index.php" style="color: #f59e0b;">🔄 Switch</a></li>
    </ul>
</div>

<div class="main-container">
    <h1>📤 Submit Assignment</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0;">Select Assignment</h2>
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

    <?php if (!empty($assignments)): ?>
        <div class="card">
            <div class="card-header">
                <h2 style="margin: 0;">Upload Submission</h2>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" class="row g-3">
                    <input type="hidden" name="action" value="submit">
                    <div class="col-12">
                        <label class="form-label">Assignment</label>
                        <select name="assignment_id" class="form-select" required>
                            <option value="">-- Select Assignment --</option>
                            <?php foreach ($assignments as $assign): 
                                $submission = getStudentSubmission($pdo, $assign['id'], $current_user_id);
                            ?>
                                <option value="<?php echo $assign['id']; ?>">
                                    <?php echo sanitize($assign['title']) . ' (Due: ' . formatDateTime($assign['due_date']) . ')'; ?>
                                    <?php echo $submission ? ' [Already Submitted]' : ''; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Upload File (PDF, DOC, DOCX, TXT, Excel, PowerPoint - Max 10MB)</label>
                        <input type="file" name="submission_file" class="form-control" accept=".pdf,.doc,.docx,.txt,.xlsx,.pptx" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">Submit Assignment</button>
                    </div>
                </form>
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
