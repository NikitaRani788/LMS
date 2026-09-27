<?php
/**
 * Student Sidebar
 * Fixed sidebar for student role - consistent across all pages
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item">
            <a href="/student_dashboard.php" class="<?php echo $current_page === 'student_dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="/student/courses.php" class="<?php echo $current_page === 'courses.php' ? 'active' : ''; ?>">
                <i class="fas fa-book"></i>
                My Courses
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="/student/assignments.php" class="<?php echo $current_page === 'assignments.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt"></i>
                Assignments
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="/student/materials.php" class="<?php echo $current_page === 'materials.php' ? 'active' : ''; ?>">
                <i class="fas fa-folder"></i>
                Study Materials
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="/student/grades.php" class="<?php echo $current_page === 'grades.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i>
                My Grades
            </a>
        </li>
    </ul>
</aside>