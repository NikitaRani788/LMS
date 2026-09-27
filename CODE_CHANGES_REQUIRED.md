# 🔧 CRITICAL CODE CHANGES REQUIRED

This file shows ONLY the files that need modifications before copying.

---

## ⚠️ CHANGE #1: `includes/functions.php`

**Bug Location:** Line ~42, in `getUserById()` function

**BEFORE (Wrong):**
```php
function getUserById($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE userid = ?');
    $stmt->execute([$userId]);
    return $stmt->fetch();
}
```

**AFTER (Fixed):**
```php
function getUserById($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');  // ← Changed: userid → id
    $stmt->execute([$userId]);
    return $stmt->fetch();
}
```

**Why:** The `users` table column is named `id`, not `userid`. See `lms_setup.sql` line 7.

---

## ⚠️ CHANGE #2: `login.php`

**Bug Location:** Line ~20, in the login handler

**BEFORE (Wrong):**
```php
if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
    // Start session and store data
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['userid'];  // ← WRONG: should be $user['id']
    $_SESSION['role'] = $user['role'];

    // Redirect based on role
    header("Location: /" . $user['role'] . "/dashboard.php");
    exit;
}
```

**AFTER (Fixed):**
```php
if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
    // Start session and store data
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];  // ← FIXED: Changed userid → id
    $_SESSION['role'] = $user['role'];

    // Redirect based on role
    header("Location: /" . $user['role'] . "/dashboard.php");
    exit;
}
```

**Why:** Same reason as above - table column is `id`

---

## ⚠️ CHANGE #3: `config/app.php`

**Location:** Lines 5-10 (Constants section)

**BEFORE (Old Project):**
```php
define('APP_ENV', $env['APP_ENV'] ?? 'production');
define('APP_DEBUG', filter_var($env['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));
define('APP_NAME', $env['APP_NAME'] ?? 'Learning Management System');
define('APP_URL', $env['APP_URL'] ?? 'https://sql205.infinityfree.com');
```

**AFTER (New Development):**
```php
define('APP_ENV', $env['APP_ENV'] ?? 'development');  // ← Change to: development
define('APP_DEBUG', filter_var($env['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN));  // ← Change to: true
define('APP_NAME', $env['APP_NAME'] ?? 'Learning Management System');
define('APP_URL', $env['APP_URL'] ?? 'http://localhost/new_lms');  // ← Change to your localhost URL
```

**And Later in the file:**

**BEFORE:**
```php
define('SESSION_SECURE', filter_var($env['SESSION_SECURE'] ?? true, FILTER_VALIDATE_BOOLEAN));
```

**AFTER:**
```php
define('SESSION_SECURE', filter_var($env['SESSION_SECURE'] ?? false, FILTER_VALIDATE_BOOLEAN));  // ← Set to false for localhost
```

**Why:** 
- Development environment needs APP_DEBUG = true (to see errors)
- Localhost doesn't have HTTPS, so SESSION_SECURE = false
- Update APP_URL to match your localhost setup

---

## ⚠️ CHANGE #4: Merge 3 Sidebar Files into 1

**Files to consolidate:**
- `includes/sidebar_admin.php`
- `includes/sidebar_faculty.php`
- `includes/sidebar_student.php`

**Create NEW FILE:** `includes/sidebar.php`

**Strategy:**
1. Open `sidebar_admin.php` - note the HTML structure & menu items
2. Open `sidebar_faculty.php` - note its menu items
3. Open `sidebar_student.php` - note its menu items
4. Create a new unified file using PHP `switch` statement:

**NEW FILE STRUCTURE:**
```php
<?php
/**
 * UNIFIED SIDEBAR
 * Shows different menu based on user role
 */

// $current_role is passed from header.php (should be: admin, faculty, or student)
$current_role = $current_role ?? 'admin';
?>

<nav class="sidebar">
    <!-- Common header for all roles -->
    <div class="sidebar-header">
        <h3>LMS Navigation</h3>
    </div>

    <!-- Role-specific menu items -->
    <ul class="sidebar-menu">
        <?php if ($current_role === 'admin'): ?>
            <!-- ADMIN MENU -->
            <li><a href="/admin/dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="/admin/users.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="/admin/courses.php"><i class="fas fa-book"></i> Courses</a></li>
            <li><a href="/admin/semesters.php"><i class="fas fa-calendar"></i> Semesters</a></li>
            <li><a href="/admin/announcements.php"><i class="fas fa-bullhorn"></i> Announcements</a></li>

        <?php elseif ($current_role === 'faculty'): ?>
            <!-- FACULTY MENU -->
            <li><a href="/faculty/dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="/faculty/courses.php"><i class="fas fa-book"></i> My Courses</a></li>
            <li><a href="/faculty/assignments.php"><i class="fas fa-tasks"></i> Assignments</a></li>
            <li><a href="/faculty/submissions.php"><i class="fas fa-file-upload"></i> Submissions</a></li>
            <li><a href="/faculty/materials.php"><i class="fas fa-file-pdf"></i> Materials</a></li>

        <?php elseif ($current_role === 'student'): ?>
            <!-- STUDENT MENU -->
            <li><a href="/student/dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="/student/courses.php"><i class="fas fa-book"></i> My Courses</a></li>
            <li><a href="/student/assignments.php"><i class="fas fa-tasks"></i> Assignments</a></li>
            <li><a href="/student/grades.php"><i class="fas fa-star"></i> Grades</a></li>
            <li><a href="/student/materials.php"><i class="fas fa-file-pdf"></i> Materials</a></li>

        <?php endif; ?>
    </ul>

    <!-- Common logout at bottom -->
    <div class="sidebar-footer">
        <a href="/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</nav>
```

**In header.php, change from:**
```php
<!-- Include old sidebars -->
if ($current_role === 'admin') {
    require 'sidebar_admin.php';
} elseif ($current_role === 'faculty') {
    require 'sidebar_faculty.php';
} else {
    require 'sidebar_student.php';
}
```

**To:**
```php
<!-- Include unified sidebar -->
<?php require 'sidebar.php'; ?>
```

---

## ⚠️ CHANGE #5: Path Updates in All Page Includes

**Problem:** Inconsistent path patterns
- Some use: `require '../config/db.php'`
- Some use: `require_once __DIR__ . '/../config/db.php'`

**Solution:** Use `__DIR__` format EVERYWHERE

**Pattern to use (for files in /admin/ folder):**
```php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../auth.php';
```

**Check ALL files in:**
- `/admin/` (5 files)
- `/faculty/` (5 files)
- `/student/` (6 files)
- `/api/` (10 files)
- `/controllers/` (14 files)

---

## 📝 Summary of Required Changes

| File | Change | Reason |
|------|--------|--------|
| `includes/functions.php` | `userid` → `id` | Wrong column name |
| `login.php` | `userid` → `id` | Wrong column name |
| `config/app.php` | APP_DEBUG, APP_ENV, SESSION_SECURE | Development settings |
| `sidebar_*.php` | Consolidate 3 into 1 | Remove duplication |
| All includes | Use `__DIR__` pattern | Path consistency |

---

## 🔍 Where to Find These Files

```
d:\xampp\htdocs\lms\
├── includes/
│   └── functions.php         ← CHANGE #1
│   └── sidebar_admin.php      ← CHANGE #4
│   └── sidebar_faculty.php    ← CHANGE #4
│   └── sidebar_student.php    ← CHANGE #4
│
├── login.php                  ← CHANGE #2
│
├── config/
│   └── app.php               ← CHANGE #3
│
├── admin/                     ← CHANGE #5
├── faculty/                   ← CHANGE #5
├── student/                   ← CHANGE #5
├── api/                       ← CHANGE #5
└── controllers/               ← CHANGE #5
```

---

## ✅ Verification After Changes

**Test #1: Login Page Works**
- Navigate to: `http://localhost/new_lms/login.php`
- Try login with: admin@lms.edu / password
- Should redirect to: `/admin/dashboard.php`

**Test #2: Faculty Login Works**
- Try login with: faculty@lms.edu / password
- Should redirect to: `/faculty/dashboard.php`

**Test #3: Student Login Works**
- Try login with: student@lms.edu / password
- Should redirect to: `/student/dashboard.php`

**Test #4: Sidebar Shows Correct Role Menu**
- Each dashboard should show role-specific menu items

---

## 🚀 After All Changes

1. Database credentials match `.env` file
2. All `userid` → `id` changes completed
3. All paths use `__DIR__` format
4. Single sidebar.php created and included
5. Test logins work for all 3 roles
6. Test page navigation works for each role

Then you have a clean, consistent project ready for deployment! ✨
