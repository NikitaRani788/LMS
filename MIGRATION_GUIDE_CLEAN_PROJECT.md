# 📋 LMS Migration Guide: Step-by-Step File Copy Instructions

**Goal:** Create a clean new LMS project with only necessary files, removing duplication and architectural confusion.

---

## 🎯 Copy Priority Order

Copy files in this exact order. Each phase depends on the previous one.

---

# PHASE 1: CORE INFRASTRUCTURE (Foundation Layer)
> Copy these FIRST - everything else depends on these files

---

## STEP 1: Configuration Files (3 files)

### ✅ 1.1 `config/db.php`
| Property | Value |
|----------|-------|
| **Status** | COPY AS-IS |
| **Location** | `new_project/config/db.php` |
| **Function** | Database connection using PDO |
| **Contains** | DB_HOST, DB_USER, DB_PASS, DB_NAME constants |
| **Size** | ~30 lines |
| **Depends on** | .env file (for credentials) |

**What it does:**
```php
// Loads database credentials from config/db.php
// Creates PDO connection object $pdo
// Catches connection errors safely
// Used by: ALL database queries in the system
```

---

### ⚠️ 1.2 `config/app.php`
| Property | Value |
|----------|-------|
| **Status** | COPY + MAKE 3 CHANGES |
| **Location** | `new_project/config/app.php` |
| **Function** | Application settings & security configuration |
| **Size** | ~60 lines |

**Changes Required:**
```diff
- Change #1: APP_URL
  OLD: APP_URL=https://sql205.infinityfree.com
  NEW: APP_URL=http://localhost:80/new_lms

- Change #2: APP_DEBUG (for development)
  OLD: APP_DEBUG=false
  NEW: APP_DEBUG=true

- Change #3: SESSION_SECURE (for localhost development)
  OLD: SESSION_SECURE=true
  NEW: SESSION_SECURE=false
  NOTE: Change back to true when deploying to production
```

**What it does:**
- Sets APP_NAME, APP_URL, APP_ENV (development/production)
- Configures session security settings
- Sets upload size limits & allowed file types
- Controls error reporting behavior
- Enforces HTTPS in production

---

### ❌ 1.3 `config/services.php`
| Property | Value |
|----------|-------|
| **Status** | **DO NOT COPY** |
| **Reason** | Symfony dependency injection code (bloated, unused) |
| **Size** | 400+ lines of framework config |

**Why skip it?** This is from a different framework layer (likely auto-generated). Your project uses plain PHP, not Symfony DI.

---

## STEP 2: Helper Functions (1 file)

### ⚠️ 2.1 `includes/functions.php`
| Property | Value |
|----------|-------|
| **Status** | COPY + FIX 1 BUG |
| **Location** | `new_project/includes/functions.php` |
| **Function** | Utility functions used throughout the project |
| **Size** | ~150 lines |

**Bug to Fix:**
```diff
Function: getUserById($pdo, $userId)
- OLD: WHERE userid = ?
+ NEW: WHERE id = ?

REASON: Database table 'users' column is 'id', not 'userid'
This is the actual column name in your lms_setup.sql
```

**Contains Functions:**
- `sanitize()` - Prevent XSS attacks
- `formatDate()` - Format dates for display
- `formatDateTime()` - Format timestamps
- `getAllUsers()`, `getUserById()`, `getUsersByRole()` - User queries
- `getAllCourses()`, `getCourseById()` - Course queries
- `getAllDepartments()`, `getDepartmentById()` - Department queries
- `getAnnouncements()` - News/announcements
- `getAllSemesters()`, `getSemesterById()` - Semester queries

**Used by:** Almost every dashboard page

---

## STEP 3: Authentication System (1 file)

### ✅ 3.1 `auth.php`
| Property | Value |
|----------|-------|
| **Status** | COPY AS-IS |
| **Location** | `new_project/auth.php` |
| **Function** | Role-based access control |
| **Size** | ~20 lines |

**Contains:**
```php
function requireLogin()          // Redirect to login if not authenticated
function requireRole($role)      // Redirect if user doesn't have required role
```

**Used by:** Every protected dashboard page (admin/faculty/student)

**Example usage:**
```php
<?php
require 'auth.php';
requireRole('admin');  // Only admin can access this page
// Rest of page code...
```

---

## STEP 4: Authentication Pages (3 files)

### ⚠️ 4.1 `login.php`
| Property | Value |
|----------|-------|
| **Status** | COPY + FIX 1 BUG |
| **Location** | `new_project/login.php` |
| **Function** | Login form & authentication handler |
| **Size** | ~80 lines |

**Bug to Fix:**
```diff
In the login handler section:
- OLD: $_SESSION['user_id'] = $user['userid'];
+ NEW: $_SESSION['user_id'] = $user['id'];

REASON: Table column is 'id', not 'userid'
```

**Flow:**
1. Shows login form (email + password fields)
2. On POST: Validates input → searches database by email → verifies password
3. Success: Creates session → redirects to role-specific dashboard
4. Failure: Shows error message

---

### ✅ 4.2 `logout.php`
| Property | Value |
|----------|-------|
| **Status** | COPY AS-IS |
| **Location** | `new_project/logout.php` |
| **Function** | Destroy session & redirect |
| **Size** | 5 lines |

**Content:**
```php
<?php
session_start();
session_destroy();
header('Location: /login.php');
exit;
?>
```

---

### ⚠️ 4.3 `index.php`
| Property | Value |
|----------|-------|
| **Status** | COPY + REVIEW |
| **Location** | `new_project/index.php` |
| **Function** | Homepage / Role selector |
| **Size** | ~150 lines |

**Current Function:** Shows demo role selector buttons (for testing without login)

**Options:**
- **Option A:** Keep as-is for testing (shows role selector)
- **Option B:** Modify to redirect to login automatically
- **Option C:** Make it a real homepage/dashboard redirect

**Recommendation:** Keep as-is for testing, but update CSS link paths

---

## STEP 5: HTML Templates (3 items)

### ⚠️ 5.1 Choose ONE Header Template
| Item | Header 1 | Header 2 |
|------|----------|----------|
| **File** | `includes/header.php` | `includes/unified_header.php` |
| **Status** | Older | Newer ✅ RECOMMENDED |
| **Style** | Basic | Better structure, role-aware |

**Action:**
1. **Copy** `includes/unified_header.php`
2. **Rename to** `includes/header.php` (delete old one)
3. **Update** all page includes to use: `require_once __DIR__ . '/../includes/header.php'`

---

### ✅ 5.2 `includes/footer.php`
| Property | Value |
|----------|-------|
| **Status** | COPY AS-IS |
| **Location** | `new_project/includes/footer.php` |
| **Function** | Page footer with scripts |
| **Size** | ~50 lines |

**Contains:**
- Closing `</main>` and footer HTML
- Bootstrap JavaScript bundle
- Navigation highlighting script
- Notification loading logic
- Role display

---

### ⚠️ 5.3 Sidebar Navigation Files (3 files → 1 file)
| File | Status |
|------|--------|
| `includes/sidebar_admin.php` | 🔄 CONSOLIDATE |
| `includes/sidebar_faculty.php` | 🔄 CONSOLIDATE |
| `includes/sidebar_student.php` | 🔄 CONSOLIDATE |

**Problem:** Three identical files with only menu items different

**Solution:** Create ONE role-aware sidebar
```php
// NEW FILE: includes/sidebar.php
<?php
// $current_role is passed from header.php
switch($current_role) {
    case 'admin':
        // Admin menu items
        break;
    case 'faculty':
        // Faculty menu items
        break;
    case 'student':
        // Student menu items
        break;
}
?>
```

**Action:**
1. Examine all three sidebar files
2. Extract common HTML structure
3. Create one file with conditional menu items
4. Delete original three files
5. Include in header.php once

---

# PHASE 2: BUSINESS LOGIC (Application Layer)
> Copy these after Phase 1 is complete and tested

---

## STEP 6: Controllers Folder (14 files)
| Property | Value |
|----------|-------|
| **Status** | COPY ENTIRE FOLDER AS-IS |
| **Location** | `new_project/controllers/` |
| **Size** | 14 PHP files |

**Controller Files & Their Functions:**

| File | Purpose |
|------|---------|
| `AnnouncementsController.php` | Create/Read/Delete announcements |
| `ApiController.php` | General API logic |
| `AppController.php` | Global app operations |
| `AssignmentsController.php` | Assignment CRUD operations |
| `CoursesController.php` | Course management |
| `DepartmentsController.php` | Department management |
| `EnrollmentsController.php` | Student course enrollment |
| `McqAnswersController.php` | MCQ answer handling |
| `McqQuestionsController.php` | MCQ quiz management |
| `NotesController.php` | Study notes management |
| `SemesterCoursesController.php` | Assign courses to semesters |
| `SubmissionsController.php` | Student assignment submissions |
| `SystemsettingsController.php` | Application settings |
| `UsersController.php` | User management (CRUD) |

**What they do:**
- Contain business logic (data validation, calculations)
- Interact with database via prepared statements
- Return data for pages/API endpoints
- Don't directly output HTML (separation of concerns)

---

## STEP 7: API Endpoints Folder (10 files)
| Property | Value |
|----------|-------|
| **Status** | COPY ENTIRE FOLDER AS-IS |
| **Location** | `new_project/api/` |
| **Size** | 10 PHP files |

**API Files & Their Functions:**

| File | Purpose |
|------|---------|
| `create_notification.php` | Send notifications to users |
| `get_assignments.php` | Fetch assignments (AJAX) |
| `get_materials.php` | Fetch course materials (AJAX) |
| `get_notifications.php` | Fetch user notifications (AJAX) |
| `get_profile.php` | Get logged-in user profile (AJAX) |
| `mark_notification_read.php` | Mark notification as read |
| `submit_assignment.php` | Handle assignment submission form |
| `update_profile.php` | Update user profile data (AJAX) |
| `upload_assignment.php` | Process assignment file upload |
| `upload_material.php` | Process material file upload |

**What they do:**
- Return JSON responses
- Used by AJAX calls from dashboard pages
- Handle form submissions asynchronously
- Check authentication before responding
- All return format: `{ "success": true/false, "data": {...}, "message": "..." }`

---

# PHASE 3: ROLE-BASED VIEWS (User Interface Layer)
> Copy these after Phase 1-2 are complete

---

## STEP 8: Admin Pages (5 files)
| Property | Value |
|----------|-------|
| **Status** | COPY ENTIRE FOLDER AS-IS |
| **Location** | `new_project/admin/` |
| **Size** | 5 PHP files |

**Admin Page Files & Their Functions:**

| File | Purpose | Contains |
|------|---------|----------|
| `dashboard.php` | Admin overview | Stats (users, courses, students, faculty, announcements, enrollments) |
| `users.php` | User management | List users, create/edit/delete users, assign roles |
| `courses.php` | Course management | List courses, create/edit/delete courses |
| `announcements.php` | Announcement management | Post/edit/delete announcements |
| `semesters.php` | Semester management | Create semesters, assign courses to semesters |

**Page Structure (Same for all):**
```
1. <?php session_start(); require auth, config, functions ?>
2. Verify admin role (requireRole('admin'))
3. Get data from database
4. Include header template
5. Display content (tables, forms)
6. Include footer template
```

---

## STEP 9: Faculty Pages (5 files)
| Property | Value |
|----------|-------|
| **Status** | COPY ENTIRE FOLDER AS-IS |
| **Location** | `new_project/faculty/` |
| **Size** | 5 PHP files |

**Faculty Page Files & Their Functions:**

| File | Purpose | Contains |
|------|---------|----------|
| `dashboard.php` | Faculty overview | Stats about their courses, assignments, submissions |
| `courses.php` | Faculty courses | List courses they teach, enroll students |
| `assignments.php` | Manage assignments | Create/edit/delete assignments for their courses |
| `materials.php` | Course materials | Upload course materials (PDFs, docs, etc.) |
| `submissions.php` | Grade submissions | View student submissions, add grades & feedback |

**Special Note:** Faculty can only see/edit their own courses (filtered by `faculty_id = $_SESSION['user_id']`)

---

## STEP 10: Student Pages (6 files)
| Property | Value |
|----------|-------|
| **Status** | COPY ENTIRE FOLDER AS-IS |
| **Location** | `new_project/student/` |
| **Size** | 6 PHP files |

**Student Page Files & Their Functions:**

| File | Purpose | Contains |
|------|---------|----------|
| `dashboard.php` | Student overview | Enrolled courses, upcoming assignments, grades summary |
| `courses.php` | Enrolled courses | List of courses student is enrolled in |
| `assignments.php` | Assigned work | View assignments for enrolled courses |
| `submit_assignment.php` | Submit work | File upload form for assignment submission |
| `grades.php` | View grades | See grades & feedback for submitted assignments |
| `materials.php` | Course resources | Access course materials uploaded by faculty |

**Special Note:** Student can only see courses they're enrolled in + their own submissions

---

# PHASE 4: DATABASE
> Set up after Phase 3

---

## STEP 11: Database Schema File

### ✅ `lms_setup.sql`
| Property | Value |
|----------|-------|
| **Status** | COPY AS-IS |
| **Location** | `new_project/lms_setup.sql` |
| **Function** | SQL script to create database & tables |
| **Size** | ~300 lines |

**Creates 14 Tables:**

| Table | Purpose |
|-------|---------|
| `users` | Student, Faculty, Admin accounts |
| `departments` | Department/discipline info |
| `courses` | Course definitions |
| `semesters` | Academic semesters |
| `semester_courses` | Courses offered in each semester |
| `enrollments` | Student enrollment in courses |
| `assignments` | Assignment/homework definitions |
| `submissions` | Student submission records |
| `mcq_questions` | Multiple choice questions |
| `mcq_answers` | MCQ answer attempts |
| `notes` | Study notes |
| `study_materials` | Course materials (PDFs, docs) |
| `announcements` | System announcements |
| `systemsettings` | Application configuration |

**To Use:**
```bash
# 1. Create new database
mysql> CREATE DATABASE new_lms;

# 2. Import schema
mysql> USE new_lms;
mysql> source lms_setup.sql;

# 3. Verify (should see 14 tables)
mysql> SHOW TABLES;
```

---

# PHASE 5: STYLING (Visual Layer)
> Copy after Phase 4

---

## STEP 12: CSS Files

### 📊 CSS Audit Required First
**Problem:** ~30 CSS files, unclear which are essential

**Recommended Keep List:**

| File | Status | Function |
|------|--------|----------|
| `css/style.css` | ✅ KEEP | Main application styles |
| `css/layout.css` | ✅ KEEP | Layout & grid system |
| `css/global-layout.css` | ✅ KEEP | Global utilities |
| `css/project1.css` | ⚠️ CHECK | Custom project styles (if used) |

**Recommended Delete List (Likely Unused):**

| File | Reason |
|------|--------|
| `css/cropper.css` | Image cropper library (if not used) |
| `css/ewpdf.css` | PDF export styling (if not used) |
| `css/query-builder.css` | Query builder (not in your app) |
| `css/smart_wizard.css` | Wizard component (if not used) |
| `css/sweetalert2.css` | Alert box (already in CDN) |
| `css/select2.css` | Select dropdown (already in CDN) |
| `css/tabulator_bootstrap5.css` | Table plugin (if not used) |
| `css/tempus-dominus.css` | Date picker (already in CDN) |
| `css/tippy.css` | Tooltip library (if not used) |

**Action Plan:**
1. Copy only: `style.css`, `layout.css`, `global-layout.css`
2. Update header.php to load from CDN for Bootstrap, FontAwesome, etc.
3. Test each page to verify styling is correct
4. If something is missing, identify which CSS file it needs and copy

---

## STEP 13: External Libraries (Load from CDN, don't copy)

**Bootstrap 5:**
```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
```

**Font Awesome:**
```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
```

**jQuery:**
```html
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
```

**Skip copying these files:**
- `bootstrap5/` folder
- `jquery/` folder
- `fullcalendar/` folder

---

# PHASE 6: ASSETS
> Copy after Phase 5

---

## STEP 14: Images & Fonts

### `images/` folder
| Status | Action |
|--------|--------|
| ✅ COPY IF EXISTS | Copy if you have custom images in this folder |

### `font/` folder
| Status | Action |
|--------|--------|
| ⚠️ CHECK USAGE | Only copy if custom fonts are referenced in your CSS |

### `uploads/` folder (Special - Create Empty)
| Status | Action |
|--------|--------|
| 🆕 CREATE NEW | Create empty directory, will be populated at runtime by file uploads |

---

# PHASE 7: CLEANUP & DEPLOYMENT CONFIG
> Final phase

---

## STEP 15: Files to EXCLUDE (Don't copy)

### Documentation Files (Reference but don't migrate)
```
❌ ARCHITECTURE.md
❌ DEBUG_FIXES_APPLIED.md
❌ LAYOUT_MIGRATION_GUIDE.md
❌ INDEX_IMPROVEMENTS_SUMMARY.md
❌ REFACTORING_COMPLETE.md
❌ FINAL_CHECKLIST.md
❌ CLEANUP_UNUSED_FILES.md
[All other *.md except README.md]
```

**Why:** These document the OLD project. Create new documentation for clean project.

---

### Test/Debug Files (Delete entirely)
```
❌ test_*.php (all test files)
❌ check_*.php (all check files)
❌ debug_*.php (all debug files)
❌ simple_test.php
❌ ADMIN_DASHBOARD_EXAMPLE.php
❌ INDEX_EXAMPLE.php
❌ INDEX_UPDATED_REFERENCE.php
```

**Why:** Development artifacts, not for clean project.

---

### One-Time Utility Scripts (Delete)
```
❌ hash.php
❌ gen_hash.php
❌ update_passwords.php
```

**Why:** Used once for setup, not needed ongoing.

---

## STEP 16: New Files to CREATE

### ✨ `.env.example`
**Location:** `new_project/.env.example`
**Purpose:** Template for environment variables

**Content:**
```env
# Database Configuration
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=new_lms

# Application Settings
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost/new_lms

# Security (Change on production)
SESSION_SECURE=false
SESSION_HTTPONLY=true
SESSION_SAMESITE=Lax
```

---

### ✨ `.gitignore`
**Location:** `new_project/.gitignore`
**Purpose:** Prevent sensitive files from being committed

**Content:**
```
# Environment
.env
.env.local

# Uploads
uploads/*
!uploads/.gitkeep

# Logs
logs/*
!logs/.gitkeep

# IDE
.vscode/
.idea/
*.swp
*.swo

# OS
.DS_Store
Thumbs.db

# Vendor (if using Composer)
vendor/
```

---

### ✨ `README.md` (New, Not from old project)
**Location:** `new_project/README.md`
**Content:**

```markdown
# Learning Management System (LMS)

A clean, beginner-friendly PHP + MySQL learning management system.

## Quick Start

1. **Copy `.env.example` to `.env`** and update database credentials
2. **Import database:** `mysql -u root < lms_setup.sql`
3. **Start XAMPP** (Apache + MySQL)
4. **Navigate:** `http://localhost/new_lms`

## Default Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@lms.edu | password |
| Faculty | faculty@lms.edu | password |
| Student | student@lms.edu | password |

## Folder Structure

- `config/` - Database & app configuration
- `includes/` - Shared templates & functions
- `admin/` - Admin dashboard pages
- `faculty/` - Faculty dashboard pages
- `student/` - Student dashboard pages
- `controllers/` - Business logic
- `api/` - JSON endpoints
- `assets/` - CSS, JS, images

## Architecture

- **Layer 1:** Authentication (auth.php, login.php)
- **Layer 2:** Business Logic (controllers/)
- **Layer 3:** API Endpoints (api/)
- **Layer 4:** User Interface (admin/, faculty/, student/)
- **Layer 5:** Database (lms_setup.sql)
```

---

# 📋 COPY CHECKLIST

Mark as complete as you copy each file/folder:

## Phase 1: Foundation
- [ ] `config/db.php` - COPY AS-IS
- [ ] `config/app.php` - COPY + FIX PATHS
- [ ] `includes/functions.php` - COPY + FIX userid→id BUG
- [ ] `auth.php` - COPY AS-IS
- [ ] `login.php` - COPY + FIX userid→id BUG
- [ ] `logout.php` - COPY AS-IS
- [ ] `index.php` - COPY + UPDATE PATHS
- [ ] `includes/unified_header.php` → rename to `header.php`
- [ ] `includes/footer.php` - COPY AS-IS
- [ ] `includes/sidebars` - CONSOLIDATE 3 INTO 1

## Phase 2: Business Logic
- [ ] `controllers/` - COPY ENTIRE FOLDER
- [ ] `api/` - COPY ENTIRE FOLDER

## Phase 3: Role Views
- [ ] `admin/` - COPY ENTIRE FOLDER
- [ ] `faculty/` - COPY ENTIRE FOLDER
- [ ] `student/` - COPY ENTIRE FOLDER

## Phase 4: Database
- [ ] `lms_setup.sql` - COPY + IMPORT
- [ ] Create test accounts

## Phase 5: Styling
- [ ] `css/style.css` - COPY
- [ ] `css/layout.css` - COPY
- [ ] `css/global-layout.css` - COPY
- [ ] Test CSS on pages

## Phase 6: Assets
- [ ] `images/` - COPY IF EXISTS
- [ ] `fonts/` - COPY IF EXISTS
- [ ] Create `uploads/` folder (empty)

## Phase 7: Cleanup
- [ ] Delete documentation from old project
- [ ] Delete test/debug files
- [ ] Create `.env.example`
- [ ] Create `.gitignore`
- [ ] Create new `README.md`

---

## ✅ Ready to Start?

Once you complete all steps above, you'll have:
- ✅ Clean, organized project structure
- ✅ No duplicate CSS/templates
- ✅ No test/debug files
- ✅ All necessary files for deployment
- ✅ Easy to explain to others (for viva/practicals)
- ✅ Professional-grade architecture
