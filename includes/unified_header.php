<?php
/**
 * UNIFIED HEADER TEMPLATE
 * =====================================================
 * 
 * Used by all dashboard pages (Admin, Faculty, Student)
 * Provides:
 * - Single navbar across all pages
 * - Role-based sidebar navigation
 * - Persistent layout (navbar + sidebar fixed)
 * - Responsive design
 * 
 * USAGE:
 * ------
 * At the TOP of your page, BEFORE any HTML:
 * 
 *   <?php
 *   session_start();
 *   require_once __DIR__ . '/../config/db.php';
 *   require_once __DIR__ . '/../includes/functions.php';
 *   
 *   $current_role = 'admin';  // Set to: admin, faculty, or student
 *   $current_user_id = $_SESSION['user_id'] ?? 1;
 *   $pageTitle = 'Page Title Here';
 *   
 *   require_once __DIR__ . '/../includes/unified_header.php';
 *   ?>
 * 
 * Then add your page content inside .app-content
 * Finally, at the BOTTOM, include the footer:
 * 
 *   <?php require_once __DIR__ . '/../includes/unified_footer.php'; ?>
 */

// Verify database connection exists
if (!isset($pdo)) {
    require_once __DIR__ . '/../config/db.php';
}

// Load helper functions
require_once __DIR__ . '/functions.php';

// Get current role and user info (these should be set by calling page)
$current_role = isset($current_role) ? $current_role : 'admin';
$current_user_id = isset($current_user_id) ? $current_user_id : 1;
$pageTitle = isset($pageTitle) ? $pageTitle : 'Dashboard';

// Get current user information
try {
    $stmt = $pdo->prepare('SELECT id, name, email, role FROM users WHERE id = ?');
    $stmt->execute([$current_user_id]);
    $current_user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$current_user) {
        $current_user = [
            'id' => $current_user_id,
            'name' => ucfirst($current_role) . ' User',
            'email' => strtolower($current_role) . '@if0_41817906_lms.edu',
            'role' => $current_role
        ];
    }
} catch (Exception $e) {
    $current_user = [
        'id' => $current_user_id,
        'name' => ucfirst($current_role) . ' User',
        'email' => strtolower($current_role) . '@if0_41817906_lms.edu',
        'role' => $current_role
    ];
}

$user_name = $current_user['name'] ?? 'User';
$user_email = $current_user['email'] ?? '';
$user_initials = strtoupper(substr($user_name, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo sanitize($pageTitle); ?> - Learning Management System</title>
    <style>
        :root{
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #3b82f6;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --light: #f3f4f6;
            --dark: #1f2937;
            --gray: #6b7280;
            --gray-light: #e5e7eb;
            --white: #ffffff;
            --sidebar-width: 260px;
            --navbar-height: 64px;
        }
    </style>
    <!-- Bootstrap 5 CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    <!-- Global Layout System CSS -->
    <link rel="stylesheet" href="/css/global-layout.css">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Additional Styles (page-specific can be added here via link tags) -->
    <style>
        /* Allow pages to inject custom styles here if needed */
    </style>
</head>
<body>
    <!-- Skip to content link for accessibility -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>
    
    <!-- App Container (Flexbox Layout) -->
    <div class="app-container">
        
        <!-- SIDEBAR - Fixed Left Navigation -->
        <aside class="app-sidebar" id="sidebar">
            <ul class="sidebar-menu" role="navigation">
                
                <!-- Dashboard Link (Common to all roles) -->
                <li class="sidebar-menu-item">
                    <a href="/<?php echo $current_role; ?>/dashboard.php" 
                       title="Go to Dashboard"
                       class="<?php echo (basename($_SERVER['PHP_SELF']) === 'dashboard.php') ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <!-- Role-Specific Menu Items -->
                <?php if ($current_role === 'admin'): ?>
                    
                    <!-- Admin-Only Items -->
                    <li class="sidebar-menu-item">
                        <a href="/admin/users.php"
                           title="Manage Users"
                           class="<?php echo (basename($_SERVER['PHP_SELF']) === 'users.php') ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i>
                            <span>Manage Users</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-menu-item">
                        <a href="/admin/courses.php"
                           title="Manage Courses"
                           class="<?php echo (basename($_SERVER['PHP_SELF']) === 'courses.php') ? 'active' : ''; ?>">
                            <i class="fas fa-book"></i>
                            <span>Manage Courses</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-menu-item">
                        <a href="/admin/semesters.php"
                           title="Manage Semesters"
                           class="<?php echo (basename($_SERVER['PHP_SELF']) === 'semesters.php') ? 'active' : ''; ?>">
                            <i class="fas fa-calendar"></i>
                            <span>Manage Semesters</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-menu-item">
                        <a href="/admin/announcements.php"
                           title="Post Announcements"
                           class="<?php echo (basename($_SERVER['PHP_SELF']) === 'announcements.php') ? 'active' : ''; ?>">
                            <i class="fas fa-bullhorn"></i>
                            <span>Announcements</span>
                        </a>
                    </li>
                    
                <?php elseif ($current_role === 'faculty'): ?>
                    
                    <!-- Faculty-Only Items -->
                    <li class="sidebar-menu-item">
                        <a href="/faculty/courses.php"
                           title="My Courses"
                           class="<?php echo (basename($_SERVER['PHP_SELF']) === 'courses.php') ? 'active' : ''; ?>">
                            <i class="fas fa-book"></i>
                            <span>My Courses</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-menu-item">
                        <a href="/faculty/assignments.php"
                           title="Create & Manage Assignments"
                           class="<?php echo (basename($_SERVER['PHP_SELF']) === 'assignments.php') ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt"></i>
                            <span>Assignments</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-menu-item">
                        <a href="/faculty/submissions.php"
                           title="Student Submissions"
                           class="<?php echo (basename($_SERVER['PHP_SELF']) === 'submissions.php') ? 'active' : ''; ?>">
                            <i class="fas fa-check-circle"></i>
                            <span>Submissions</span>
                        </a>
                    </li>
                    
                <?php elseif ($current_role === 'student'): ?>
                    
                    <!-- Student-Only Items -->
                    <li class="sidebar-menu-item">
                        <a href="/student/courses.php"
                           title="My Courses"
                           class="<?php echo (basename($_SERVER['PHP_SELF']) === 'courses.php') ? 'active' : ''; ?>">
                            <i class="fas fa-book"></i>
                            <span>My Courses</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-menu-item">
                        <a href="/student/assignments.php"
                           title="My Assignments"
                           class="<?php echo (basename($_SERVER['PHP_SELF']) === 'assignments.php') ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt"></i>
                            <span>Assignments</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-menu-item">
                        <a href="/student/grades.php"
                           title="My Grades"
                           class="<?php echo (basename($_SERVER['PHP_SELF']) === 'grades.php') ? 'active' : ''; ?>">
                            <i class="fas fa-chart-line"></i>
                            <span>My Grades</span>
                        </a>
                    </li>
                    
                <?php endif; ?>
            </ul>
        </aside>
        
        <!-- MAIN CONTENT AREA -->
        <main class="app-main">
            
            <!-- NAVBAR - Fixed Top Navigation -->
            <header class="app-header" role="banner">
                
                <!-- Navbar Left: Brand & Role -->
                <div class="navbar-left">
                    <!-- Mobile Sidebar Toggle -->
                    <button class="nav-icon-btn mobile-toggle" 
                            onclick="toggleSidebar()" 
                            aria-label="Toggle sidebar"
                            title="Toggle navigation menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <!-- Brand/Logo -->
                    <a href="/index.php" class="navbar-brand" title="Go to Home">
                        <i class="fas fa-graduation-cap"></i>
                        <span>if0_41817906_lms</span>
                    </a>
                    
                    <!-- Role Indicator -->
                    <span class="role-indicator">
                        <i class="fas fa-user-shield"></i>
                        <span><?php echo ucfirst($current_role); ?></span>
                    </span>
                </div>
                
                <!-- Navbar Right: Actions & Profile -->
                <div class="navbar-right">
                    
                    <!-- Notifications Dropdown -->
                    <div class="notification-dropdown">
                        <button class="nav-icon-btn" 
                                id="notificationBtn"
                                onclick="toggleNotifications(event)"
                                aria-label="Notifications"
                                title="View notifications">
                            <i class="fas fa-bell"></i>
                            <span class="badge" id="notificationBadge" style="display: none;">0</span>
                        </button>
                        
                        <div class="notification-menu" id="notificationMenu">
                            <div class="notification-header">
                                <h4>Notifications</h4>
                                <button class="mark-all-btn" onclick="markAllNotificationsRead()">Mark all read</button>
                            </div>
                            <div class="notification-list" id="notificationList">
                                <div class="notification-empty">Loading notifications...</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile Dropdown -->
                    <div class="profile-dropdown">
                        <button class="profile-btn"
                                onclick="toggleProfile(event)"
                                aria-label="User profile menu"
                                title="Open profile menu">
                            <div class="profile-avatar" title="<?php echo htmlspecialchars($user_name); ?>">
                                <?php echo htmlspecialchars($user_initials); ?>
                            </div>
                            <div class="profile-info">
                                <div class="profile-name"><?php echo htmlspecialchars($user_name); ?></div>
                                <div class="profile-role"><?php echo ucfirst($current_role); ?></div>
                            </div>
                        </button>
                        
                        <div class="profile-menu">
                            <div class="profile-menu-header">
                                <h4><?php echo htmlspecialchars($user_name); ?></h4>
                                <p><?php echo htmlspecialchars($user_email); ?></p>
                            </div>
                            <div class="profile-menu-items">
                                <a href="/api/update_profile.php" class="profile-menu-item" title="Edit your profile">
                                    <i class="fas fa-user-edit"></i>
                                    <span>Edit Profile</span>
                                </a>
                                <a href="/api/get_profile.php" class="profile-menu-item" title="View your profile">
                                    <i class="fas fa-user-circle"></i>
                                    <span>My Profile</span>
                                </a>
                                <a href="/index.php" class="profile-menu-item" title="Switch roles">
                                    <i class="fas fa-exchange-alt"></i>
                                    <span>Switch Role</span>
                                </a>
                                <hr style="margin: 0.5rem 0; border: none; border-top: 1px solid var(--gray-light);">
                                <a href="/logout.php" class="profile-menu-item text-danger" title="Sign out">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- PAGE CONTENT AREA (Scrollable) -->
            <section class="app-content" id="main-content" role="main">
                <div class="content-wrapper">
                    <!-- Page content goes here -->