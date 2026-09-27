# 🎨 if0_41817906_lms Dashboard - Unified Layout System Guide

## Overview

This guide explains the **new unified layout architecture** for the if0_41817906_lms dashboard. All pages now use a single, consistent layout system with:

- ✅ **Fixed Navbar** (top, full-width, persistent)
- ✅ **Fixed Sidebar** (left side, scrollable, role-based)
- ✅ **Scrollable Content Area** (main page content)
- ✅ **Responsive Design** (mobile-friendly)
- ✅ **Consistent Styling** (single CSS source of truth)

---

## 📁 New File Structure

```
css/
├── global-layout.css          ← Main layout CSS (NEW - USE THIS!)
└── layout.css                 ← (DEPRECATED - Stop using this)

includes/
├── unified_header.php         ← (IMPROVED - Use this for all pages)
├── unified_footer.php         ← (IMPROVED - Use this for all pages)
├── header.php                 ← (DEPRECATED - Don't use this)
└── footer.php                 ← (DEPRECATED - Don't use this)

admin/
├── announcements.php          ← (REFACTORED EXAMPLE - See this!)
├── dashboard.php              ← (Needs refactoring)
├── users.php                  ← (Needs refactoring)
├── courses.php                ← (Needs refactoring)
└── semesters.php              ← (Needs refactoring)

faculty/
├── dashboard.php              ← (Needs refactoring)
└── ...

student/
├── dashboard.php              ← (Needs refactoring)
└── ...
```

---

## 🚀 How to Refactor Your Pages

### Step 1: Remove Old Layout Code

**BEFORE (Old Way - ❌ DON'T DO THIS):**
```php
<?php
require_once 'includes/header.php';
require_once 'includes/sidebar_admin.php';
?>

<div class="main-container">
    <h1>Page Title</h1>
    <!-- Your content -->
</div>

<?php require_once 'includes/footer.php'; ?>
```

### Step 2: Use New Unified Layout

**AFTER (New Way - ✅ DO THIS):**
```php
<?php
// 1. SESSION & DATABASE SETUP
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// 2. VERIFY ACCESS
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}

// 3. SET PAGE CONTEXT (REQUIRED)
$current_role = 'admin';              // 'admin', 'faculty', or 'student'
$current_user_id = $_SESSION['user_id'] ?? 1;
$pageTitle = 'Your Page Title';

// 4. INCLUDE UNIFIED HEADER (provides: <html>, <head>, navbar, sidebar, starts .app-content)
require_once __DIR__ . '/../includes/unified_header.php';

// 5. YOUR PAGE-SPECIFIC CODE & LOGIC HERE
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
}

// Fetch data for your page
$data = [];
?>

<!-- 6. YOUR PAGE CONTENT (automatically inside .app-content scrollable area) -->
<h1>Page Title</h1>
<p>Your content goes here</p>

<?php
// 7. INCLUDE UNIFIED FOOTER (closes .app-content, .app-main, .app-container, </body>, </html>)
require_once __DIR__ . '/../includes/unified_footer.php';
?>
```

---

## 📋 Key Features of the New System

### 1. **Fixed Navbar (Top)**
- Always visible at the top
- Contains: Logo, Role Indicator, Notifications, Profile Menu
- Height: 64px (`--navbar-height`)
- Shows on all pages automatically

### 2. **Fixed Sidebar (Left)**
- Always visible on the left
- Width: 260px (`--sidebar-width`)
- Mobile: 200px (`--sidebar-width-mobile`)
- **Role-based items** - automatically shows correct menu for each role:
  - **Admin**: Dashboard, Manage Users, Manage Courses, Manage Semesters, Announcements
  - **Faculty**: Dashboard, My Courses, Assignments, Submissions
  - **Student**: Dashboard, My Courses, Assignments, My Grades
- Active link highlighting
- Smooth scrolling

### 3. **Scrollable Content Area**
- Flex-grow to fill remaining space
- Overflow-y auto for scrolling
- Padding: 1.5rem (`--container-padding`)
- Max-width: 1440px (`--container-max-width`)

### 4. **Responsive Design**
- **Desktop** (> 768px): Sidebar always visible
- **Tablet** (481px - 768px): Sidebar hidden, mobile toggle button shows
- **Mobile** (< 480px): Further optimizations

### 5. **Global CSS Variables** (in `css/global-layout.css`)

```css
/* Brand Colors */
--primary: #2563eb
--success: #10b981
--danger: #ef4444
--warning: #f59e0b

/* Layout Dimensions */
--navbar-height: 64px
--sidebar-width: 260px
--container-max-width: 1440px

/* Spacing */
--spacing-xs: 0.25rem
--spacing-sm: 0.5rem
--spacing-md: 1rem
--spacing-lg: 1.5rem
--spacing-xl: 2rem

/* Shadows & Borders */
--border-radius: 10px
--shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1)
--shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1)
```

---

## 🔧 Important Classes & Structure

### HTML Structure
```html
<div class="app-container">              <!-- Main flex container -->
    <aside class="app-sidebar">           <!-- Fixed sidebar -->
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="...">Item</a>
            </li>
        </ul>
    </aside>
    
    <main class="app-main">               <!-- Flex column for header + content -->
        <header class="app-header">       <!-- Fixed navbar -->
            <div class="navbar-left">
                <button class="mobile-toggle">...</button>
                <a class="navbar-brand">...</a>
                <span class="role-indicator">...</span>
            </div>
            <div class="navbar-right">
                <!-- Notifications, Profile -->
            </div>
        </header>
        
        <section class="app-content">     <!-- Scrollable content area -->
            <div class="content-wrapper">
                <!-- YOUR PAGE CONTENT GOES HERE -->
            </div>
        </section>
    </main>
</div>
```

### CSS Classes You Can Use

```css
/* Flexbox */
.flex-row              /* flex, flex-direction: row */
.flex-col              /* flex, flex-direction: column */
.flex-center           /* flex, centered */

/* Spacing */
.gap-sm, .gap-md, .gap-lg         /* Gap between flex items */
.p-md, .p-lg, .p-xl               /* Padding */
.m-md, .m-lg, .m-xl               /* Margin */
.mb-sm, .mb-md, .mb-lg            /* Margin-bottom */

/* Text */
.text-primary, .text-danger, .text-success   /* Text colors */
.font-sm, .font-md, .font-lg                 /* Font sizes */
.font-weight-600, .font-weight-700           /* Font weights */

/* Backgrounds */
.bg-primary, .bg-danger, .bg-light          /* Background colors */

/* Borders */
.border, .border-bottom, .border-top         /* Borders */
.rounded, .rounded-sm, .rounded-lg           /* Border radius */

/* Display */
.hidden, .visible, .invisible                /* Display utilities */
```

---

## 🎯 Quick Refactoring Checklist

For each page, do this in order:

- [ ] **Step 1**: Add session start and requires at the TOP
  ```php
  session_start();
  require_once __DIR__ . '/../config/db.php';
  require_once __DIR__ . '/../includes/functions.php';
  ```

- [ ] **Step 2**: Add access verification (if needed)
  ```php
  if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
      header('Location: /index.php');
      exit;
  }
  ```

- [ ] **Step 3**: Set page context variables
  ```php
  $current_role = 'admin';
  $current_user_id = $_SESSION['user_id'] ?? 1;
  $pageTitle = 'Your Page Title';
  ```

- [ ] **Step 4**: Include unified_header.php
  ```php
  require_once __DIR__ . '/../includes/unified_header.php';
  ```

- [ ] **Step 5**: Move your page content (no HTML tags, no sidebar div)
  - Delete any hardcoded sidebar divs
  - Delete any old navbar code
  - Keep only your page-specific content

- [ ] **Step 6**: Include unified_footer.php at the BOTTOM
  ```php
  require_once __DIR__ . '/../includes/unified_footer.php';
  ```

- [ ] **Step 7**: Remove old includes
  - Delete: `require 'includes/header.php'`
  - Delete: `require 'includes/sidebar_*.php'`
  - Delete: `require 'includes/footer.php'`

- [ ] **Step 8**: Test in browser
  - Check navbar displays correctly
  - Check sidebar shows correct menu items for your role
  - Check page content scrolls independently
  - Check sidebar doesn't scroll with content
  - Test on mobile (sidebar toggle)

---

## 📊 Example: Admin Dashboard Refactoring

### Before (Old):
```php
<?php
$current_role = 'admin';
$current_user_id = 1;
require_once 'includes/header.php';
require_once 'includes/sidebar_admin.php';
?>
<div class="container">
    <h1>Dashboard</h1>
    <!-- Stats, charts, etc -->
</div>
<?php require_once 'includes/footer.php'; ?>
```

### After (New):
See: [admin/announcements.php](../../admin/announcements.php) for complete example!

---

## 🎨 Customizing Page Styles

Each page can have custom CSS. Add it in the header area:

```php
<?php
$current_role = 'admin';
$current_user_id = $_SESSION['user_id'] ?? 1;
$pageTitle = 'Your Page';
require_once __DIR__ . '/../includes/unified_header.php';
?>

<!-- Your content -->

<style>
    /* Page-specific styles */
    .my-custom-class {
        color: var(--primary);
        padding: var(--spacing-lg);
    }
</style>

<?php require_once __DIR__ . '/../includes/unified_footer.php'; ?>
```

---

## 🚨 Common Mistakes to Avoid

❌ **WRONG**: Mixing old and new includes
```php
require_once 'includes/header.php';
require_once 'includes/unified_header.php';  // CONFLICT!
```

❌ **WRONG**: Creating your own navbar/sidebar
```php
<?php require_once 'includes/unified_header.php'; ?>
<div class="my-navbar">...</div>  <!-- DUPLICATE! -->
```

❌ **WRONG**: Not setting page context variables
```php
// Missing these:
// $current_role = 'admin';
// $current_user_id = $_SESSION['user_id'];
// $pageTitle = 'Page Title';
```

❌ **WRONG**: Putting content OUTSIDE .app-content
```php
<?php require_once 'includes/unified_header.php'; ?>

<!-- Content OUTSIDE app-content - won't display correctly! -->

<?php require_once 'includes/unified_footer.php'; ?>
```

✅ **RIGHT**: Follow the structure exactly as shown
```php
<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$current_role = 'admin';
$current_user_id = $_SESSION['user_id'] ?? 1;
$pageTitle = 'Page Title';
require_once __DIR__ . '/../includes/unified_header.php';
?>

<!-- Page content HERE - inside .app-content automatically -->

<?php require_once __DIR__ . '/../includes/unified_footer.php'; ?>
```

---

## 📱 Mobile Responsive Features

### Mobile Toggle Button
Automatically shows on screens ≤ 768px:
```html
<button class="nav-icon-btn mobile-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>
```

### Sidebar Behavior
- **Desktop**: Always visible, left margin applied to content
- **Tablet/Mobile**: Hidden by default, slides in from left when toggled

### Layout Adjustments
- Padding reduces on mobile
- Font sizes adjust
- Role indicator hides on small screens

---

## 🔗 File References

| File | Purpose | Status |
|------|---------|--------|
| `css/global-layout.css` | Main layout & component styles | ✅ NEW |
| `includes/unified_header.php` | Provides navbar, sidebar, starts content area | ✅ IMPROVED |
| `includes/unified_footer.php` | Closes layout, includes scripts | ✅ IMPROVED |
| `admin/announcements.php` | Example of refactored page | ✅ EXAMPLE |
| `css/layout.css` | Old layout CSS | ⚠️ DEPRECATED |
| `includes/header.php` | Old header | ⚠️ DEPRECATED |
| `includes/footer.php` | Old footer | ⚠️ DEPRECATED |
| `includes/sidebar_admin.php` | Old sidebar | ⚠️ DEPRECATED |
| `includes/sidebar_faculty.php` | Old sidebar | ⚠️ DEPRECATED |
| `includes/sidebar_student.php` | Old sidebar | ⚠️ DEPRECATED |

---

## ✨ Benefits of the New System

1. **Single Source of Truth**
   - All CSS in `global-layout.css`
   - All HTML in `unified_header.php` & `unified_footer.php`
   - Consistency across all pages

2. **Persistent Layout**
   - Navbar and sidebar fixed
   - Content scrolls independently
   - Better UX for navigation

3. **Responsive Design**
   - Mobile-first approach
   - Automatic sidebar toggle on small screens
   - Optimized for all devices

4. **Easy Maintenance**
   - Change layout once, affects all pages
   - Role-based menus automatic
   - No duplicate code

5. **Better Performance**
   - Smaller CSS files (no duplication)
   - Faster page load
   - Less rendering work

6. **Accessibility**
   - Proper semantic HTML
   - ARIA labels and roles
   - Keyboard navigation support

---

## 🆘 Need Help?

See the complete working example: **[admin/announcements.php](../../admin/announcements.php)**

Compare it to other pages to see what needs to change!

---

## 📝 Migration Progress

Track which pages have been refactored:

- [ ] admin/dashboard.php
- [x] admin/announcements.php ← Example
- [ ] admin/users.php
- [ ] admin/courses.php
- [ ] admin/semesters.php
- [ ] faculty/dashboard.php
- [ ] faculty/courses.php
- [ ] faculty/assignments.php
- [ ] faculty/submissions.php
- [ ] student/dashboard.php
- [ ] student/courses.php
- [ ] student/assignments.php
- [ ] student/grades.php

---

**Last Updated**: May 2, 2026  
**System Version**: 1.0  
**Status**: ✅ Production Ready
