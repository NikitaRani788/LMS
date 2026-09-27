# if0_41817906_lms Structure Fix - Implementation Guide

## Overview
This document describes the fixed if0_41817906_lms structure after consolidating duplicate dashboard files.

## Key Changes

### 1. Dashboard Files (KEEP ONLY IN ROLE FOLDERS)
**Keep these files:**
- `/admin/dashboard.php` - Admin dashboard
- `/faculty/dashboard.php` - Faculty dashboard  
- `/student/dashboard.php` - Student dashboard

**DELETE/REMOVE these files:**
- `admin_dashboard.php` (if0_41817906 level)
- `faculty_dashboard.php` (if0_41817906 level)
- `student_dashboard.php` (if0_41817906 level)

### 2. Session Variable Standardization
All dashboards now use consistent session variables:
- `$_SESSION['role']` - Current user role ('admin', 'faculty', 'student')
- `$_SESSION['user_id']` - Current user ID from database
- `$_SESSION['user_name']` - Current user name

### 3. Database Connection Include Pattern
All dashboards now use the proper relative path pattern:
```php
require_once __DIR__ . '/../config/db.php';
```

This ensures the database connection works from any nested directory.

### 4. Entry Points
- **index.php** - Role selector/demo entry point (redirects to dashboards)
- **login.php** - Authentication entry point (in production)

### 5. Access Control
Each dashboard verifies the user's role:
```php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}
```

## Folder Structure

```
/
├── index.php                    [ROLE SELECTOR]
├── login.php                    [AUTHENTICATION]
├── logout.php
├── config/
│   ├── db.php                  [DATABASE CONNECTION]
│   └── ...
├── includes/
│   ├── header.php              [SHARED HEADER]
│   ├── footer.php              [SHARED FOOTER]
│   ├── functions.php           [UTILITY FUNCTIONS]
│   └── ...
├── admin/
│   ├── dashboard.php           [ADMIN DASHBOARD - MAIN]
│   ├── users.php
│   ├── courses.php
│   └── ...
├── faculty/
│   ├── dashboard.php           [FACULTY DASHBOARD - MAIN]
│   ├── courses.php
│   ├── assignments.php
│   └── ...
├── student/
│   ├── dashboard.php           [STUDENT DASHBOARD - MAIN]
│   ├── courses.php
│   ├── assignments.php
│   └── ...
└── [Other folders]
```

## Session Flow

1. User visits `/index.php` (demo) or `/login.php` (production)
2. User selects/authenticates with a role
3. Session variables set:
   - `$_SESSION['role'] = 'admin'|'faculty'|'student'`
   - `$_SESSION['user_id'] = <user_id>`
   - `$_SESSION['user_name'] = <name>`
4. User redirected to appropriate dashboard:
   - `/admin/dashboard.php`
   - `/faculty/dashboard.php`
   - `/student/dashboard.php`
5. Dashboard verifies role and displays role-specific content

## Navigation Pattern

All navigation links use absolute paths with `/` prefix:
```php
<a href="/admin/users.php">Users</a>
<a href="/admin/courses.php">Courses</a>
<a href="/index.php">Switch Role</a>
```

## Database Connection Availability

- `$pdo` global variable available in all dashboards via `header.php`
- Safe for queries after `header.php` is included
- Use prepared statements to prevent SQL injection

## CSS & Static Assets

All CSS files reference uses absolute paths:
```php
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/style.css">
```

CSS should be inside `<style>` tags or external CSS files, never loose/plain text.

## Files Modified

1. `/admin/dashboard.php` - Updated includes and session handling
2. `/faculty/dashboard.php` - Updated includes and session handling
3. `/student/dashboard.php` - Updated includes and session handling
4. `/index.php` - Updated session variable names for consistency

## Files to Remove

1. `admin_dashboard.php` - DELETE
2. `faculty_dashboard.php` - DELETE
3. `student_dashboard.php` - DELETE

## Testing Checklist

- [ ] index.php loads and displays role selector
- [ ] Clicking role button redirects to correct dashboard
- [ ] Session variables are set correctly
- [ ] Dashboard displays user data from database
- [ ] Navigation links work within dashboard
- [ ] CSS loads properly (not as plain text)
- [ ] Switching roles works correctly
- [ ] All database queries execute without errors
