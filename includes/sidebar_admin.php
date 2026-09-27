<?php
/**
 * Admin Sidebar
 * Fixed sidebar for admin role - consistent across all pages
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item">
            <a href="/admin_dashboard.php" class="<?php echo $current_page === 'admin_dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="/admin/users.php" class="<?php echo $current_page === 'users.php' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                Manage Users
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="/admin/courses.php" class="<?php echo $current_page === 'courses.php' ? 'active' : ''; ?>">
                <i class="fas fa-book"></i>
                Manage Courses
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="/admin/semesters.php" class="<?php echo $current_page === 'semesters.php' ? 'active' : ''; ?>">
                <i class="fas fa-calendar"></i>
                Manage Semesters
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="/admin/announcements.php" class="<?php echo $current_page === 'announcements.php' ? 'active' : ''; ?>">
                <i class="fas fa-bullhorn"></i>
                Announcements
            </a>
        </li>
    </ul>
</aside>