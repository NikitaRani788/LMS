<?php
/**
 * FACULTY DASHBOARD
 */
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Verify faculty access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header('Location: /index.php');
    exit;
}

$current_role = 'faculty';
$current_user_id = $_SESSION['user_id'] ?? 2;
$pageTitle = 'Faculty Dashboard';
require_once __DIR__ . '/../includes/header.php';

// Get faculty statistics
try {
    $faculty_courses = getFacultyCourses($pdo, $current_user_id);
    $course_count = count($faculty_courses);
    
    // Get total assignments and submissions
    $stmt = $pdo->prepare('
        SELECT COUNT(DISTINCT a.id) as total_assignments, COUNT(DISTINCT s.id) as total_submissions
        FROM assignments a
        LEFT JOIN submissions s ON a.id = s.assignment_id
        INNER JOIN courses c ON a.course_id = c.id
        INNER JOIN semester_courses sc ON c.id = sc.course_id
        WHERE sc.faculty_id = ?
    ');
    $stmt->execute([$current_user_id]);
    $stats = $stmt->fetch();
    
    $total_assignments = $stats['total_assignments'] ?? 0;
    $total_submissions = $stats['total_submissions'] ?? 0;
} catch (Exception $e) {
    $faculty_courses = [];
    $course_count = $total_assignments = $total_submissions = 0;
}

?>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="welcome-content">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <p>Manage your courses and student submissions</p>
    </div>
</div>

<!-- Dashboard Stats -->
<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-info">
            <h3>My Courses</h3>
            <div class="number"><?php echo $course_count; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="stat-info">
            <h3>Assignments</h3>
            <div class="number"><?php echo $total_assignments; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #b45309);">
            <i class="fas fa-file-upload"></i>
        </div>
        <div class="stat-info">
            <h3>Submissions</h3>
            <div class="number"><?php echo $total_submissions; ?></div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h2 style="margin: 0;"><i class="fas fa-book me-2"></i>My Courses</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($faculty_courses)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Students</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($faculty_courses as $course): 
                                    $enrollments = getCourseEnrollments($pdo, $course['id']);
                                    $enrollment_count = count($enrollments);
                                ?>
                                    <tr>
                                        <td><span class="badge bg-info"><?php echo sanitize($course['code']); ?></span></td>
                                        <td><?php echo sanitize($course['name']); ?></td>
                                        <td><?php echo $enrollment_count; ?></td>
                                        <td>
                                            <a href="/faculty/assignments.php?course_id=<?php echo $course['id']; ?>" class="btn btn-sm btn-primary">Manage</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No courses assigned yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h2 style="margin: 0;"><i class="fas fa-bolt me-2"></i>Quick Actions</h2>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/faculty/assignments.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Create Assignment
                    </a>
                    <a href="/faculty/submissions.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-file-import me-2"></i>View Submissions
                    </a>
                    <a href="/faculty/courses.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-book me-2"></i>Manage Courses
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Study Material Section -->
<div class="card">
    <div class="card-header">
        <h2 style="margin: 0;"><i class="fas fa-upload me-2"></i>Upload Study Material</h2>
    </div>
    <div class="card-body">
        <form id="uploadMaterialForm" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Course</label>
                    <select name="course_id" class="form-select" required>
                        <option value="">Select Course</option>
                        <?php foreach ($faculty_courses as $course): ?>
                            <option value="<?php echo $course['id']; ?>"><?php echo sanitize($course['code'] . ' - ' . $course['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required placeholder="Material title">
                </div>
                <div class="col-md-4">
                    <label class="form-label">File</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Brief description"></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Upload Material
                    </button>
                </div>
            </div>
        </form>
        <div id="uploadMessage"></div>
    </div>
</div>

<style>
/* Dashboard-specific styles */
.welcome-banner {
    background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    color: white;
}

.welcome-banner .welcome-content h1 {
    margin: 0 0 0.5rem 0;
    font-size: 1.75rem;
    font-weight: 700;
}

.welcome-banner .welcome-content p {
    margin: 0;
    opacity: 0.9;
    font-size: 1rem;
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon i {
    font-size: 1.5rem;
    color: white;
}

.stat-info h3 {
    margin: 0 0 0.25rem 0;
    font-size: 0.85rem;
    color: #6b7280;
    font-weight: 500;
}

.stat-info .number {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -0.75rem;
}

.col-lg-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding: 0 0.75rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 992px) {
    .col-lg-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

.d-grid {
    display: grid;
    gap: 0.75rem;
}

.btn-lg {
    padding: 0.875rem 1.25rem;
    font-size: 1rem;
}
</style>

<script>
// Handle material upload
document.getElementById('uploadMaterialForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageDiv = document.getElementById('uploadMessage');
    
    try {
        const response = await fetch('../api/upload_material.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            messageDiv.innerHTML = '<div class="alert alert-success mt-3"><i class="fas fa-check-circle me-2"></i>' + data.message + '</div>';
            this.reset();
        } else {
            messageDiv.innerHTML = '<div class="alert alert-danger mt-3"><i class="fas fa-exclamation-circle me-2"></i>' + data.message + '</div>';
        }
    } catch (error) {
        messageDiv.innerHTML = '<div class="alert alert-danger mt-3"><i class="fas fa-exclamation-circle me-2"></i>Error uploading file</div>';
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
