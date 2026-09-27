# ⚡ Quick Reference - Old vs New Layout

## 📋 File Structure Comparison

### OLD WAY (❌ Don't use)
```php
<?php
require_once 'includes/header.php';
require_once 'includes/sidebar_admin.php';
?>

<div class="main-container">
    <h1>Page Title</h1>
    <!-- Page content -->
</div>

<?php require_once 'includes/footer.php'; ?>
```

### NEW WAY (✅ Use this)
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

<!-- Page content goes here (inside .app-content automatically) -->

<?php require_once __DIR__ . '/../includes/unified_footer.php'; ?>
```

---

## 🔧 Quick Setup (Copy & Paste)

### Minimal Page Template
```php
<?php
// ===================== TOP OF FILE =====================
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Verify access (optional, but recommended)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}

// Set page context (REQUIRED)
$current_role = 'admin';              // 'admin', 'faculty', or 'student'
$current_user_id = $_SESSION['user_id'] ?? 1;
$pageTitle = 'Your Page Title';

// Include header (REQUIRED)
require_once __DIR__ . '/../includes/unified_header.php';

// Your page-specific code here
$data = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle forms
}
?>

<!-- ===================== YOUR PAGE CONTENT ===================== -->
<h1>Page Title</h1>
<p>Your content here</p>

<?php
// ===================== BOTTOM OF FILE =====================
// Include footer (REQUIRED)
require_once __DIR__ . '/../includes/unified_footer.php';
?>
```

---

## 🎨 Key CSS Changes

### Container Structure
| Old | New |
|-----|-----|
| Multiple divs | Flexbox `.app-container` |
| Sidebar hardcoded | Role-based `.app-sidebar` |
| Navbar in page | Fixed `.app-header` |
| Content with gaps | Flex `.app-content` |

### Spacing
| Old | New |
|-----|-----|
| Random margins | CSS variables: `--spacing-md`, `--spacing-lg` |
| Inline styles | Utility classes: `.gap-md`, `.p-lg` |
| Inconsistent | Consistent across all pages |

### Layout
| Old | New |
|-----|-----|
| Content scrolls behind navbar | Content scrolls independently |
| Sidebar scrolls with content | Sidebar fixed on left |
| Navbar changes per page | Navbar identical on all pages |

---

## 📦 What Each File Includes

### `unified_header.php` Provides:
- ✅ `<html>`, `<head>` tags
- ✅ CSS imports (Bootstrap, global-layout.css, Font Awesome)
- ✅ Complete navbar with:
  - Logo & brand
  - Role indicator
  - Notifications dropdown
  - Profile menu
- ✅ Complete sidebar with:
  - Role-based menu items
  - Active link highlighting
  - Scrolling
- ✅ Opens `.app-content` section
- ✅ **You add: Your page content**

### `unified_footer.php` Provides:
- ✅ Closes `.app-content`, `.app-main`, `.app-container`
- ✅ All JavaScript functions:
  - Sidebar toggle
  - Notifications
  - Dropdowns
  - Active link highlighting
- ✅ `</body>`, `</html>` tags
- ✅ **No action needed from you**

---

## 🎯 Common Use Cases

### Add Custom CSS to Page
```php
<?php require_once 'includes/unified_header.php'; ?>

<!-- Your content -->

<style>
    .my-custom-class {
        color: var(--primary);
        padding: var(--spacing-lg);
    }
</style>

<?php require_once 'includes/unified_footer.php'; ?>
```

### Add Custom JavaScript to Page
```php
<?php require_once 'includes/unified_footer.php'; ?>  <!-- Must be BEFORE script -->

<script>
    // Your JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded!');
    });
</script>
```

### Handle Form Submission
```php
<?php
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form
    $title = $_POST['title'] ?? '';
    if ($title) {
        // Do something
        $message = 'Success!';
    }
}
require_once 'includes/unified_header.php';
?>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<!-- Your form -->

<?php require_once 'includes/unified_footer.php'; ?>
```

---

## 🚨 Common Mistakes

### ❌ WRONG - Mixing old and new
```php
<?php
require_once 'includes/header.php';        // OLD
require_once 'includes/unified_header.php'; // NEW - CONFLICT!
?>
```

### ✅ RIGHT - Use only one
```php
<?php
require_once 'includes/unified_header.php'; // Only this
?>
```

### ❌ WRONG - Creating duplicate navbar
```php
<?php require_once 'includes/unified_header.php'; ?>

<!-- DUPLICATE - header already in unified_header.php! -->
<nav class="navbar">...</nav>

<?php require_once 'includes/unified_footer.php'; ?>
```

### ✅ RIGHT - Let unified_header provide navbar
```php
<?php require_once 'includes/unified_header.php'; ?>

<!-- Just add your page content, navbar is already there -->
<h1>My Page</h1>

<?php require_once 'includes/unified_footer.php'; ?>
```

### ❌ WRONG - Not setting page variables
```php
<?php
// Missing these:
// $current_role = 'admin';
// $current_user_id = $_SESSION['user_id'];
// $pageTitle = 'Page Title';

require_once 'includes/unified_header.php'; // Will fail
?>
```

### ✅ RIGHT - Set all three variables
```php
<?php
$current_role = 'admin';
$current_user_id = $_SESSION['user_id'] ?? 1;
$pageTitle = 'Page Title';

require_once 'includes/unified_header.php'; // Will work
?>
```

---

## 🎨 Using CSS Variables

### Available Variables
```css
/* Colors */
--primary: #2563eb
--success: #10b981
--danger: #ef4444
--warning: #f59e0b
--white: #ffffff
--dark: #1f2937
--gray: #6b7280
--gray-light: #e5e7eb

/* Sizes */
--navbar-height: 64px
--sidebar-width: 260px
--container-max-width: 1440px
--container-padding: 1.5rem

/* Spacing */
--spacing-sm: 0.5rem
--spacing-md: 1rem
--spacing-lg: 1.5rem
--spacing-xl: 2rem

/* Borders */
--border-radius: 10px
--shadow-lg: 0 10px 25px rgba(0,0,0,0.1)
```

### How to Use
```css
/* In your page-specific CSS */
.my-card {
    background: var(--white);
    color: var(--dark);
    padding: var(--spacing-lg);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-lg);
}
```

---

## 📱 Responsive Classes

### Use These in HTML
```html
<!-- Hide on mobile -->
<span class="d-none d-md-inline">Desktop only</span>

<!-- Show only on mobile -->
<div class="d-md-none">Mobile only</div>

<!-- Flexbox utilities -->
<div class="flex-row gap-md">
    <div>Item 1</div>
    <div>Item 2</div>
</div>

<!-- Spacing utilities -->
<div class="p-lg mb-md">Content with padding and margin</div>

<!-- Text utilities -->
<p class="text-primary font-weight-600">Important text</p>
```

---

## 🔗 Role-Based Sidebar (Automatic)

### Admin Menu
```
Dashboard
Manage Users
Manage Courses
Manage Semesters
Announcements
```

### Faculty Menu
```
Dashboard
My Courses
Assignments
Student Submissions
```

### Student Menu
```
Dashboard
My Courses
Assignments
My Grades
```

**No need to code this!** Just set `$current_role` and the sidebar builds automatically.

---

## ✨ Pre-Built Features (Automatic)

These come with `unified_header.php`:

- ✅ Navbar with logo & role badge
- ✅ Notification bell with counter
- ✅ Profile dropdown with logout
- ✅ Mobile sidebar toggle button
- ✅ Active link highlighting
- ✅ Responsive design
- ✅ Bootstrap integration
- ✅ Font Awesome icons
- ✅ User name & email display

---

## 📊 Layout Dimensions

```
┌─────────────────────────────────────────┐
│  Navbar (64px fixed at top)             │
├──────────┬─────────────────────────────┤
│ Sidebar  │ Content Area                │
│ 260px    │ (flex-grow, scrollable)     │
│ (fixed)  │ max-width: 1440px           │
│          │ padding: 1.5rem             │
│          │                              │
│          │ Margin-left: 260px          │
│ (scroll) │ (to account for sidebar)    │
└──────────┴─────────────────────────────┘

Mobile (<768px):
┌─────────────────────────────────────────┐
│  Navbar (56px, toggle button)           │
├─────────────────────────────────────────┤
│ Content Area                            │
│ (sidebar slides in from left when       │
│  user clicks toggle button)             │
│                                          │
│ max-width: 100%                         │
│ padding: 1rem                           │
└─────────────────────────────────────────┘
```

---

## 🚀 Refactoring Checklist (5 Minutes)

- [ ] Copy this template to your page
- [ ] Set `$current_role`, `$current_user_id`, `$pageTitle`
- [ ] Remove old `require 'includes/header.php'`
- [ ] Remove old `require 'includes/sidebar_*.php'`
- [ ] Remove old `require 'includes/footer.php'`
- [ ] Remove hardcoded navbar HTML
- [ ] Remove hardcoded sidebar HTML
- [ ] Test in browser

---

## 📚 Complete Working Example

**See**: [admin/announcements.php](admin/announcements.php)

This file demonstrates:
- ✅ Proper setup
- ✅ Form handling
- ✅ Database queries
- ✅ Error handling
- ✅ Success messages
- ✅ Bootstrap styling
- ✅ Table display
- ✅ Empty state

Use it as a template for other pages!

---

## 🎓 Key Concepts

### Flexbox Containers
```css
.app-container {
    display: flex;
    height: 100vh;  /* Full height */
}

.app-main {
    flex: 1;        /* Take remaining space */
    display: flex;
    flex-direction: column; /* Stack vertically */
}

.app-content {
    flex: 1;        /* Take remaining space */
    overflow-y: auto; /* Allow scrolling */
}
```

### Why Flexbox?
- Responsive without media queries
- Content grows/shrinks automatically
- Alignment and distribution built-in
- Modern browser support
- Better than floats/positioning

---

## ⏱️ Time to Refactor a Page

- Reading guide: 10 min
- Copying template: 2 min
- Adjusting content: 5 min
- Testing: 5 min
- **Total: ~20 minutes per page**

---

**Quick Start**: [admin/announcements.php](admin/announcements.php)  
**Full Guide**: [LAYOUT_MIGRATION_GUIDE.md](LAYOUT_MIGRATION_GUIDE.md)  
**Delivery Summary**: [REFACTORING_COMPLETE.md](REFACTORING_COMPLETE.md)
