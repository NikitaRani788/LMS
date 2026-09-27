<?php
/**
 * ADMIN DASHBOARD
 */
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Verify admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}

$current_role = 'admin';
$current_user_id = $_SESSION['user_id'] ?? 1;
$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../includes/header.php';

try {
    $totalUsers = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $totalCourses = $pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn();
    $totalStudents = $pdo->query('SELECT COUNT(*) FROM users WHERE role = "student"')->fetchColumn();
    $totalFaculty = $pdo->query('SELECT COUNT(*) FROM users WHERE role = "faculty"')->fetchColumn();
    $totalAnnouncements = $pdo->query('SELECT COUNT(*) FROM announcements')->fetchColumn();
    $totalEnrollments = $pdo->query('SELECT COUNT(*) FROM enrollments')->fetchColumn();
} catch (Exception $e) {
    $totalUsers = $totalCourses = $totalStudents = $totalFaculty = $totalAnnouncements = $totalEnrollments = 0;
}
?>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="welcome-content">
        <h1>Welcome to Admin Dashboard</h1>
        <p>Manage your Learning Management System</p>
    </div>
</div>

<!-- Dashboard Stats -->
<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>Total Users</h3>
            <div class="number"><?php echo $totalUsers; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-info">
            <h3>Courses</h3>
            <div class="number"><?php echo $totalCourses; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #047857);">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-info">
            <h3>Students</h3>
            <div class="number"><?php echo $totalStudents; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #b45309);">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-info">
            <h3>Faculty</h3>
            <div class="number"><?php echo $totalFaculty; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
            <i class="fas fa-user-plus"></i>
        </div>
        <div class="stat-info">
            <h3>Enrollments</h3>
            <div class="number"><?php echo $totalEnrollments; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #b91c1c);">
            <i class="fas fa-bullhorn"></i>
        </div>
        <div class="stat-info">
            <h3>Announcements</h3>
            <div class="number"><?php echo $totalAnnouncements; ?></div>
        </div>
    </div>
</div>

<!-- Content Cards -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h2 style="margin: 0;"><i class="fas fa-bullhorn me-2"></i>Recent Announcements</h2>
            </div>
            <div class="card-body">
                <?php
                $announcements = getAnnouncements($pdo, 5);
                if (!empty($announcements)):
                ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr><th>Title</th><th>Author</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($announcements as $ann): ?>
                                    <tr>
                                        <td><?php echo sanitize($ann['title']); ?></td>
                                        <td><?php echo sanitize($ann['user_id']); ?></td>
                                        <td><?php echo formatDate($ann['created_at']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No announcements yet.</p>
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
                    <a href="/admin/users.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-users me-2"></i>Manage Users
                    </a>
                    <a href="/admin/courses.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-book me-2"></i>Manage Courses
                    </a>
                    <a href="/admin/semesters.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar me-2"></i>Manage Semesters
                    </a>
                    <a href="/admin/announcements.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-bullhorn me-2"></i>Post Announcement
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard-specific styles */
.welcome-banner {
    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
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

.card .card-body .btn {
    margin-bottom: 0.5rem;
}

.card .card-body .btn:last-child {
    margin-bottom: 0;
}
</style>

<?php require_once '../includes/footer.php'; ?>
