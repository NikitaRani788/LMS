<?php
/**
 * Faculty Sidebar
 * Fixed sidebar for faculty role - consistent across all pages
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item">
            <a href="/faculty_dashboard.php" class="<?php echo $current_page === 'faculty_dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="/faculty/courses.php" class="<?php echo $current_page === 'courses.php' ? 'active' : ''; ?>">
                <i class="fas fa-book"></i>
                My Courses
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="/faculty/assignments.php" class="<?php echo $current_page === 'assignments.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt"></i>
                Assignments
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="/faculty/materials.php" class="<?php echo $current_page === 'materials.php' ? 'active' : ''; ?>">
                <i class="fas fa-folder"></i>
                Study Materials
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="/faculty/submissions.php" class="<?php echo $current_page === 'submissions.php' ? 'active' : ''; ?>">
                <i class="fas fa-check-circle"></i>
                Student Submissions
            </a>
        </li>
    </ul>
</aside>