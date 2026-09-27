<?php
/**
 * STUDENT DASHBOARD
 */
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Verify student access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header('Location: /index.php');
    exit;
}

$current_role = 'student';
$current_user_id = $_SESSION['user_id'] ?? 3;
$pageTitle = 'Student Dashboard';
require_once __DIR__ . '/../includes/header.php';

try {
    $student_courses = getStudentCourses($pdo, $current_user_id);
    $course_count = count($student_courses);
    
    $stmt = $pdo->prepare('
        SELECT COUNT(DISTINCT a.id) as assignments, COUNT(DISTINCT s.id) as submissions
        FROM assignments a
        LEFT JOIN submissions s ON a.id = s.assignment_id AND s.user_id = ?
        INNER JOIN courses c ON a.course_id = c.id
        INNER JOIN enrollments e ON c.id = e.course_id AND e.user_id = ?
    ');
    $stmt->execute([$current_user_id, $current_user_id]);
    $stats = $stmt->fetch();
    
    $total_assignments = $stats['assignments'] ?? 0;
    $total_submissions = $stats['submissions'] ?? 0;
} catch (Exception $e) {
    $student_courses = [];
    $course_count = $total_assignments = $total_submissions = 0;
}
?>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="welcome-content">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <p>Track your courses, assignments, and progress</p>
    </div>
</div>

<!-- Dashboard Stats -->
<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-info">
            <h3>Enrolled Courses</h3>
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
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #047857);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3>Submitted</h3>
            <div class="number"><?php echo $total_submissions; ?></div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h2 style="margin: 0;"><i class="fas fa-book me-2"></i>My Enrolled Courses</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($student_courses)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Course Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($student_courses as $course): ?>
                                    <tr>
                                        <td><span class="badge bg-info"><?php echo sanitize($course['code']); ?></span></td>
                                        <td><?php echo sanitize($course['name']); ?></td>
                                        <td>
                                            <a href="/student/assignments.php?course_id=<?php echo $course['id']; ?>" 
                                            class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">You are not enrolled in any courses yet.</p>
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
                    <a href="/student/courses.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-book me-2"></i>Browse Courses
                    </a>
                    <a href="/student/assignments.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-tasks me-2"></i>View Assignments
                    </a>
                    <a href="/student/submit_assignment.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-upload me-2"></i>Submit Assignment
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Study Materials Section -->
<div class="card">
    <div class="card-header">
        <h2 style="margin: 0;"><i class="fas fa-folder-open me-2"></i>Study Materials</h2>
    </div>
    <div class="card-body">
        <div id="materialsContainer">
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard-specific styles */
.welcome-banner {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

/* Materials list */
.material-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin-bottom: 0.75rem;
    transition: all 0.2s ease;
}

.material-item:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

.material-icon {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.material-icon i {
    font-size: 1.25rem;
    color: white;
}

.material-info {
    flex: 1;
    min-width: 0;
}

.material-title {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.material-meta {
    font-size: 0.8rem;
    color: #6b7280;
}

.material-actions {
    flex-shrink: 0;
}
</style>

<script>
// Load study materials
async function loadMaterials() {
    const container = document.getElementById('materialsContainer');
    
    try {
        const response = await fetch('../api/get_materials.php');
        const data = await response.json();
        
        if (data.success && data.data.length > 0) {
            container.innerHTML = data.data.map(material => `
                <div class="material-item">
                    <div class="material-icon">
                        <i class="fas fa-file-${getFileIcon(material.file_type)}"></i>
                    </div>
                    <div class="material-info">
                        <div class="material-title">${escapeHtml(material.title)}</div>
                        <div class="material-meta">${material.course_name} • ${material.file_size_formatted} • ${material.created_at_formatted}</div>
                    </div>
                    <div class="material-actions">
                        <a href="${material.file_path}" class="btn btn-sm btn-outline-primary" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<p class="text-muted">No study materials available yet.</p>';
        }
    } catch (error) {
        console.error('Error loading materials:', error);
        container.innerHTML = '<p class="text-danger">Error loading materials.</p>';
    }
}

function getFileIcon(fileType) {
    if (!fileType) return 'alt';
    if (fileType.includes('pdf')) return 'pdf';
    if (fileType.includes('word')) return 'word';
    if (fileType.includes('excel') || fileType.includes('spreadsheet')) return 'excel';
    if (fileType.includes('powerpoint') || fileType.includes('presentation')) return 'powerpoint';
    if (fileType.includes('image')) return 'image';
    return 'alt';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', loadMaterials);
</script>

<?php require_once '../includes/footer.php'; ?>

