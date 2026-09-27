<?php
/**
 * ADMIN DASHBOARD - COMPLETE CORRECTED EXAMPLE
 * Location: /admin/dashboard.php
 * 
 * This file demonstrates proper:
 * - Session handling with role verification
 * - Database connection using proper relative paths
 * - Navigation links using absolute paths
 * - CSS management
 */

// 1. START SESSION FIRST
session_start();

// 2. INCLUDE DATABASE CONNECTION (using __DIR__ for portability)
require_once __DIR__ . '/../config/db.php';

// 3. INCLUDE UTILITY FUNCTIONS
require_once __DIR__ . '/../includes/functions.php';

// 4. SET PAGE VARIABLES BEFORE HEADER (used in header.php)
$current_role = 'admin';
$current_user_id = $_SESSION['user_id'] ?? 1;
$pageTitle = 'Admin Dashboard';

// 5. VERIFY ROLE AND ACCESS CONTROL
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect to role selector if not logged in or wrong role
    header('Location: ' . ($_SESSION['role'] ?? null ? '/index.php' : '/login.php'));
    exit;
}

// 6. QUERY DATABASE (connection $pdo is available after db.php include)
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

// 7. INCLUDE HEADER (includes HTML, navigation, etc.)
require_once __DIR__ . '/../includes/header.php';
?>

<!-- MAIN CONTENT STARTS HERE -->
<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="/admin/dashboard.php" class="active">🏠 Dashboard</a></li>
        <li><a href="/admin/users.php">👥 Users</a></li>
        <li><a href="/admin/courses.php">📚 Courses</a></li>
        <li><a href="/admin/semesters.php">📅 Semesters</a></li>
        <li><a href="/admin/announcements.php">📢 Announcements</a></li>
        <li><a href="/index.php" style="color: #f59e0b;">🔄 Switch Role</a></li>
    </ul>
</div>

<div class="main-container">
    <h1>📊 Admin Dashboard</h1>

    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>Total Users</h3>
            <div class="number"><?php echo $totalUsers; ?></div>
        </div>
        <div class="stat-card">
            <h3>Courses</h3>
            <div class="number"><?php echo $totalCourses; ?></div>
        </div>
        <div class="stat-card">
            <h3>Students</h3>
            <div class="number"><?php echo $totalStudents; ?></div>
        </div>
        <div class="stat-card">
            <h3>Faculty</h3>
            <div class="number"><?php echo $totalFaculty; ?></div>
        </div>
        <div class="stat-card">
            <h3>Enrollments</h3>
            <div class="number"><?php echo $totalEnrollments; ?></div>
        </div>
        <div class="stat-card">
            <h3>Announcements</h3>
            <div class="number"><?php echo $totalAnnouncements; ?></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0;">📰 Recent Announcements</h2>
        </div>
        <div class="card-body">
            <?php
            $announcements = getAnnouncements($pdo, 5);
            if (!empty($announcements)):
            ?>
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($announcements as $ann): ?>
                            <tr>
                                <td><?php echo sanitize($ann['title']); ?></td>
                                <td><?php echo sanitize($ann['author_name']); ?></td>
                                <td><?php echo formatDate($ann['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No announcements yet.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php
// 8. INCLUDE FOOTER (closes HTML, includes scripts)
require_once __DIR__ . '/../includes/footer.php';
?>
