<?php
/**
 * HEADER TEMPLATE
 * Included on all pages
 */
// Load application configuration first
require_once __DIR__ . '/../config/app.php';

// Only include db.php if not already loaded
if (!isset($pdo)) {
    require_once __DIR__ . '/../config/db.php';
}
require_once __DIR__ . '/functions.php';

// Get current role (should be defined in the calling page)
$current_role = isset($current_role) ? $current_role : 'admin';
$current_user_id = isset($current_user_id) ? $current_user_id : 1; // Admin user by default

// Get current user info
$current_user = getUserById($pdo, $current_user_id);
$user_name = $current_user['name'] ?? 'User';
$user_email = $current_user['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? sanitize($pageTitle) . ' - if0_41817906_lms' : 'if0_41817906_lms'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
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
            --header-height: 64px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #333;
            overflow-x: hidden;
        }

        /* ==================== TOP NAVBAR ==================== */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-brand {
            font-size: 1.4rem;
            font-weight: 700;
            color: white !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            font-size: 1.2rem;
        }

        .role-indicator {
            background: rgba(255,255,255,0.2);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            color: white;
            font-size: 0.85rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: none;
            background: rgba(255,255,255,0.15);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-icon-btn:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }

        .nav-icon-btn .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ==================== PROFILE DROPDOWN ==================== */
        .profile-dropdown {
            position: relative;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.4rem 0.8rem;
            background: rgba(255,255,255,0.15);
            border: none;
            border-radius: 10px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-btn:hover {
            background: rgba(255,255,255,0.25);
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .profile-info {
            text-align: left;
            display: none;
        }

        @media (min-width: 992px) {
            .profile-info {
                display: block;
            }
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.9rem;
            line-height: 1.2;
        }

        .profile-role {
            font-size: 0.75rem;
            opacity: 0.85;
        }

        .profile-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 220px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .profile-dropdown:hover .profile-menu,
        .profile-dropdown.active .profile-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-menu-header {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-light);
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .profile-menu-header h4 {
            margin: 0;
            font-size: 0.95rem;
            color: var(--dark);
        }

        .profile-menu-header p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--gray);
        }

        .profile-menu-items {
            padding: 0.5rem;
        }

        .profile-menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--dark);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .profile-menu-item:hover {
            background: var(--light);
            color: var(--primary);
        }

        .profile-menu-item i {
            width: 20px;
            color: var(--gray);
        }

        .profile-menu-item:hover i {
            color: var(--primary);
        }

        .profile-menu-divider {
            height: 1px;
            background: var(--gray-light);
            margin: 0.5rem 0;
        }

        .profile-menu-item.logout {
            color: var(--danger);
        }

        .profile-menu-item.logout i {
            color: var(--danger);
        }

        .profile-menu-item.logout:hover {
            background: #fee2e2;
        }

        /* ==================== NOTIFICATIONS ==================== */
        .notification-dropdown {
            position: relative;
        }

        .notification-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 360px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            overflow: hidden;
            z-index: 1001;
        }

        .notification-dropdown:hover .notification-menu,
        .notification-dropdown.active .notification-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid var(--gray-light);
            background: #f8fafc;
        }

        .notification-header h4 {
            margin: 0;
            font-size: 0.95rem;
            color: var(--dark);
        }

        .mark-all-btn {
            background: none;
            border: none;
            color: var(--primary);
            font-size: 0.8rem;
            cursor: pointer;
            padding: 0;
        }

        .mark-all-btn:hover {
            text-decoration: underline;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem;
            border-bottom: 1px solid var(--gray-light);
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .notification-item:hover {
            background: var(--light);
        }

        .notification-item.unread {
            background: #eff6ff;
        }

        .notification-item.unread:hover {
            background: #dbeafe;
        }

        .notification-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-icon.info { background: #dbeafe; color: #2563eb; }
        .notification-icon.success { background: #d1fae5; color: #10b981; }
        .notification-icon.warning { background: #fef3c7; color: #f59e0b; }
        .notification-icon.danger { background: #fee2e2; color: #ef4444; }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .notification-message {
            font-size: 0.8rem;
            color: var(--gray);
            margin-bottom: 0.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-time {
            font-size: 0.7rem;
            color: var(--gray);
        }

        .notification-empty {
            padding: 2rem;
            text-align: center;
            color: var(--gray);
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            position: fixed;
            left: 0;
            top: var(--header-height);
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: white;
            border-right: 1px solid var(--gray-light);
            overflow-y: auto;
            z-index: 100;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
        }

        .sidebar-menu-item {
            margin: 0.25rem 0.75rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--gray);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
        }

        .sidebar-menu a i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar-menu a:hover {
            background: var(--light);
            color: var(--primary);
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .sidebar-menu a.active i {
            color: white;
        }

        .sidebar-section-title {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray);
            font-weight: 600;
        }

        /* ==================== MAIN CONTENT ==================== */
        .main-container {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 1.5rem;
            min-height: calc(100vh - var(--header-height));
        }

        /* ==================== CARDS ==================== */
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border-radius: 12px;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--gray-light);
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: var(--dark);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* ==================== STATS CARDS ==================== */
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary);
            border-radius: 12px 0 0 12px;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .stat-card h3 {
            font-size: 0.85rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .stat-card .number {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1;
        }

        .stat-card .stat-icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            opacity: 0.15;
        }

        .stat-card .stat-icon i {
            font-size: inherit;
        }

        /* Color variants for stat cards */
        .stat-card.primary::before { background: var(--primary); }
        .stat-card.primary .stat-icon { background: #dbeafe; color: var(--primary); }
        
        .stat-card.success::before { background: var(--success); }
        .stat-card.success .stat-icon { background: #d1fae5; color: var(--success); }
        
        .stat-card.warning::before { background: var(--warning); }
        .stat-card.warning .stat-icon { background: #fef3c7; color: var(--warning); }
        
        .stat-card.danger::before { background: var(--danger); }
        .stat-card.danger .stat-icon { background: #fee2e2; color: var(--danger); }

        /* ==================== BUTTONS ==================== */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            padding: 0.6rem 1.2rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 2px 10px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1e3a8a 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
            box-shadow: 0 2px 10px rgba(16, 185, 129, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
            color: white;
        }

        /* ==================== FORMS ==================== */
        .form-control, .form-select {
            border: 1px solid var(--gray-light);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* ==================== TABLES ==================== */
        .table {
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead th {
            background: var(--light);
            border-bottom: none;
            padding: 1rem;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        /* ==================== ALERTS ==================== */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.25rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }

        /* ==================== FOOTER ==================== */
        .footer {
            background: var(--dark);
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        /* ==================== PAGE HEADINGS ==================== */
        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .page-header .breadcrumb {
            margin: 0;
            padding: 0;
            background: none;
        }

        .page-header .breadcrumb-item {
            font-size: 0.9rem;
            color: var(--gray);
        }

        .page-header .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
        }

        .page-header .breadcrumb-item.active {
            color: var(--dark);
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 992px) {
            :root {
                --sidebar-width: 0px;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
                width: 260px;
            }

            .main-container {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block !important;
            }
        }

        @media (min-width: 993px) {
            .mobile-toggle {
                display: none !important;
            }
        }

        /* ==================== ANIMATIONS ==================== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease forwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="navbar-left">
            <button class="nav-icon-btn mobile-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <a href="/index.php" class="navbar-brand">
                <i class="fas fa-graduation-cap"></i>
                if0_41817906_lms
            </a>
            <span class="role-indicator">
                <i class="fas fa-user-shield me-1"></i>
                <?php echo ucfirst($current_role); ?>
            </span>
        </div>
        
        <div class="navbar-right">
            <!-- Notifications Dropdown -->
            <div class="notification-dropdown">
                <button class="nav-icon-btn" id="notificationBtn" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="badge" id="notificationBadge" style="display: none;">0</span>
                </button>
                <div class="notification-menu" id="notificationMenu">
                    <div class="notification-header">
                        <h4>Notifications</h4>
                        <button class="mark-all-btn" onclick="markAllRead(event)">Mark all read</button>
                    </div>
                    <div class="notification-list" id="notificationList">
                        <div class="notification-empty">Loading...</div>
                    </div>
                </div>
            </div>
            
            <!-- Profile Dropdown -->
            <div class="profile-dropdown">
                <button class="profile-btn">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                    </div>
                    <div class="profile-info">
                        <div class="profile-name"><?php echo htmlspecialchars($user_name); ?></div>
                        <div class="profile-role"><?php echo ucfirst($current_role); ?></div>
                    </div>
                    <i class="fas fa-chevron-down ms-2" style="font-size: 0.7rem;"></i>
                </button>
                
                <div class="profile-menu">
                    <div class="profile-menu-header">
                        <h4><?php echo htmlspecialchars($user_name); ?></h4>
                        <p><?php echo htmlspecialchars($user_email); ?></p>
                    </div>
                    <div class="profile-menu-items">
                        <a href="/profile.php" class="profile-menu-item">
                            <i class="fas fa-user"></i>
                            View Profile
                        </a>
                        <a href="/settings.php" class="profile-menu-item">
                            <i class="fas fa-cog"></i>
                            Settings
                        </a>
                        <div class="profile-menu-divider"></div>
                        <a href="/logout.php" class="profile-menu-item logout">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="/<?php echo $current_role; ?>_dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === $current_role . '_dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </li>
            <?php if ($current_role === 'admin'): ?>
            <li class="sidebar-menu-item">
                <a href="/admin/users.php">
                    <i class="fas fa-users"></i>
                    Manage Users
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/admin/courses.php">
                    <i class="fas fa-book"></i>
                    Manage Courses
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/admin/semesters.php">
                    <i class="fas fa-calendar"></i>
                    Manage Semesters
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/admin/announcements.php">
                    <i class="fas fa-bullhorn"></i>
                    Announcements
                </a>
            </li>
            <?php elseif ($current_role === 'faculty'): ?>
            <li class="sidebar-menu-item">
                <a href="/faculty/courses.php">
                    <i class="fas fa-book"></i>
                    My Courses
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/faculty/assignments.php">
                    <i class="fas fa-file-alt"></i>
                    Assignments
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/faculty/materials.php">
                    <i class="fas fa-folder"></i>
                    Study Materials
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/faculty/submissions.php">
                    <i class="fas fa-check-circle"></i>
                    Student Submissions
                </a>
            </li>
            <?php elseif ($current_role === 'student'): ?>
            <li class="sidebar-menu-item">
                <a href="/student/courses.php">
                    <i class="fas fa-book"></i>
                    My Courses
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/student/assignments.php">
                    <i class="fas fa-file-alt"></i>
                    Assignments
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/student/materials.php">
                    <i class="fas fa-folder"></i>
                    Study Materials
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/student/grades.php">
                    <i class="fas fa-chart-line"></i>
                    My Grades
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Common Navigation Items -->
            <li class="sidebar-menu-item">
                <a href="/my_files.php">
                    <i class="fas fa-folder-open"></i>
                    My Files
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="/settings.php">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-container">

