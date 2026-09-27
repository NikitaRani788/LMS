# 🔗 FILE DEPENDENCY MAP

Understanding which files depend on which is crucial for the migration order.

---

## DEPENDENCY FLOW DIAGRAM

```
┌────────────────────────────────────────────────────────────┐
│                     Entry Point                            │
├────────────────────────────────────────────────────────────┤
│ login.php  OR  index.php  OR  /admin/dashboard.php etc    │
└────────────────────────────────────────────────────────────┘
                              ↓
                    ┌─────────────────────┐
                    │   Session Check     │
                    │   auth.php          │
                    └─────────────────────┘
                              ↓
        ┌─────────────────────┴─────────────────────┐
        ↓                                           ↓
   ┌──────────────────┐              ┌──────────────────────┐
   │ config/db.php    │              │  config/app.php      │
   │ Database         │              │ Constants & Settings │
   │ Connection       │              └──────────────────────┘
   └──────────────────┘                      ↓
        ↓                          (Error handling, Security)
   ($pdo object)                           ↓
        ↓                          Used by: All files
        ↓
   ┌──────────────────────────┐
   │ includes/functions.php   │
   │ Helper Functions         │
   │ - sanitize()             │
   │ - formatDate()           │
   │ - getAllUsers()          │
   │ - getCourseById()        │
   │ - etc.                   │
   └──────────────────────────┘
        ↓
   Used by: Dashboard pages, controllers, API
        ↓
   ┌──────────────────────────────────┐
   │ Page Rendering Layer             │
   ├──────────────────────────────────┤
   │ includes/header.php              │
   │ includes/sidebar.php             │
   │ includes/footer.php              │
   └──────────────────────────────────┘
        ↓
   Used by: All admin/, faculty/, student/ pages
        ↓
   ┌────────────────────────────────────────────┐
   │    Role-Based Dashboard Pages              │
   ├────────────────────────────────────────────┤
   │ /admin/dashboard.php                       │
   │ /admin/users.php, courses.php, etc.        │
   │                                             │
   │ /faculty/dashboard.php                     │
   │ /faculty/courses.php, assignments.php, etc │
   │                                             │
   │ /student/dashboard.php                     │
   │ /student/courses.php, grades.php, etc      │
   └────────────────────────────────────────────┘
        ↓
   May call via AJAX:
        ↓
   ┌──────────────────────────────────┐
   │ api/*.php Endpoints              │
   │ Return JSON responses            │
   │ - get_profile.php                │
   │ - get_assignments.php            │
   │ - update_profile.php             │
   │ - etc.                           │
   └──────────────────────────────────┘
        ↓
   Use business logic from:
        ↓
   ┌──────────────────────────────────┐
   │ controllers/*.php                │
   │ Business logic & data validation │
   │ - UsersController                │
   │ - CoursesController              │
   │ - etc.                           │
   └──────────────────────────────────┘
        ↓
   All ultimately use:
        ↓
        $pdo (Database connection from config/db.php)
```

---

## COPY ORDER (Why This Sequence?)

### TIER 1: Foundation (Everything depends on these)

```
1. config/db.php
   └─ REASON: $pdo object needed by ALL files
   └─ Must be first because it's the database connection

2. config/app.php
   └─ REASON: Constants for app configuration
   └─ Used by error handling, security settings

3. includes/functions.php
   └─ REASON: Helper functions used by ALL dashboard pages
   └─ No point copying dashboard pages without these utilities
```

✅ **Test after TIER 1:** Can you connect to database without errors?

---

### TIER 2: Authentication (Protects the system)

```
4. auth.php
   └─ REASON: Every protected page needs this
   └─ Checks $_SESSION and enforces roles

5. login.php
   └─ REASON: Entry point for users
   └─ Creates sessions used by auth.php
   
6. logout.php
   └─ REASON: Destroys sessions
```

✅ **Test after TIER 2:** Can you login and logout?

---

### TIER 3: Templates (Visual consistency)

```
7. includes/header.php
   └─ REASON: Used by ALL 16 dashboard pages
   └─ Provides navbar, includes config/app.php, auth.php, functions.php
   └─ Depends on: config/db.php, config/app.php, includes/functions.php

8. includes/sidebar.php (consolidated from 3 files)
   └─ REASON: Used by ALL 16 dashboard pages
   └─ Shows role-specific navigation
   
9. includes/footer.php
   └─ REASON: Used by ALL 16 dashboard pages
   └─ Closes HTML, includes scripts
```

✅ **Test after TIER 3:** Does header/footer display correctly?

---

### TIER 4: Controllers & API

```
10. controllers/ (entire folder, 14 files)
    └─ REASON: Business logic needed by pages
    └─ Queries database (uses config/db.php)
    └─ Validates data
    
11. api/ (entire folder, 10 files)
    └─ REASON: AJAX endpoints called by dashboard pages
    └─ Must exist before dashboard pages fully work
    └─ Depends on: config/db.php, includes/functions.php
```

✅ **Test after TIER 4:** Do API endpoints return JSON?

---

### TIER 5: Dashboard Pages

```
12. admin/ (5 files)
13. faculty/ (5 files)
14. student/ (6 files)

    └─ REASON: Now that all their dependencies exist, pages will work
    └─ Each page includes: auth.php → config/db.php → functions.php → header.php → sidebar.php → footer.php
    └─ They call API endpoints for async operations
    └─ They use controllers for business logic
```

✅ **Test after TIER 5:** Can you view each dashboard?

---

### TIER 6: Database

```
15. lms_setup.sql
    └─ REASON: Now test with real database
    └─ Create new MySQL database
    └─ Import schema
    └─ Insert test accounts
```

✅ **Test after TIER 6:** Can you login with test accounts?

---

### TIER 7: Styling

```
16. css/style.css, layout.css, global-layout.css
    └─ REASON: Pages work functionally, now make them look good
    └─ Update header.php to load CSS files
```

✅ **Test after TIER 7:** Do pages display with correct styling?

---

### TIER 8: Assets

```
17. images/, fonts/ folders
18. Create uploads/ folder
```

✅ **Test after TIER 8:** Do images display? Do file uploads work?

---

## 🔴 DEPENDENCY CHAINS (Order Matters!)

### Chain 1: Login Flow
```
user visits login.php
    ↓ requires ↓
config/app.php (error reporting settings)
config/db.php (PDO connection)
includes/functions.php (sanitize function)
    ↓ on POST ↓
Password verified, session created
    ↓ redirect to ↓
/admin/dashboard.php OR /faculty/dashboard.php OR /student/dashboard.php
```

**If any of these are missing or broken, login fails.**

---

### Chain 2: Admin Dashboard Load
```
user navigates to admin/dashboard.php
    ↓ requires ↓
auth.php (checks $_SESSION['user_id'] exists)
config/db.php (for $pdo connection)
config/app.php (for constants)
includes/functions.php (for getAllUsers(), etc.)
includes/header.php (displays navbar)
    ↓ header.php itself requires ↓
config/db.php (to get user info from database)
includes/functions.php (to call getUserById)
    ↓ then ↓
includes/sidebar.php (displays role menu)
includes/footer.php (closes HTML, includes scripts)
```

**If ANY of these are missing, the page breaks.**

---

### Chain 3: AJAX API Call
```
User clicks "Load Courses" button on dashboard
    ↓ JavaScript sends request ↓
/api/get_assignments.php
    ↓ which requires ↓
session_start() (check user is authenticated)
config/db.php (for $pdo)
includes/functions.php (for database helpers)
    ↓ which queries database ↓
assignments table
    ↓ returns ↓
JSON response: { "success": true, "data": [...] }
    ↓ JavaScript processes ↓
Updates page UI with assignments
```

**If config/db.php or functions.php are missing, API returns error.**

---

## 🎯 Why Copy in This Order?

**Reason 1: Testing**
- After TIER 1: Can test database connection
- After TIER 2: Can test authentication
- After TIER 3: Can test page rendering
- After TIER 4: Can test API endpoints
- After TIER 5: Can test full application flow
- After TIER 6: Can test with real data

**Reason 2: Debugging**
- If dashboard doesn't load, you know it's not about CSS (not copied yet)
- If login fails, you know it's not about controllers (not copied yet)
- If API returns error, check config/db.php first (copied early)

**Reason 3: Catching Bugs Early**
- Bugs in functions.php affect everything → fix ASAP
- Bugs in config files affect everything → fix ASAP
- Bugs in dashboard pages only affect those pages → fix later

---

## 🚨 CONSEQUENCES OF WRONG ORDER

### ❌ If you skip Tier 2 and copy Tier 5 first:
```
You copy admin/dashboard.php
But auth.php doesn't exist yet
Page includes auth.php (which doesn't exist)
Result: Fatal error
You get confused: "Why is admin page broken?"
```

### ❌ If you skip Tier 3:
```
You copy dashboard pages
But header.php doesn't exist
Page includes header.php (which doesn't exist)
Result: No navbar, footer, styling
You think CSS is broken, but it's actually missing template
```

### ❌ If you skip Tier 4:
```
You copy dashboard pages
But controllers/ not copied yet
Dashboard page tries to include course list
But controllers/CoursesController.php doesn't exist
Result: Blank list, no error shown to user
You think data is missing from database
```

---

## ✅ KEY INSIGHT FOR YOUR PROJECT

**This is why your current project feels "confusing":**

You have files mixed from different tiers in the same folder:
- Test files (development tier) mixed with production code
- Documentation scattered everywhere
- CSS from different eras/purposes in same folder
- Sidebars duplicated instead of consolidated

**Your new clean project:**
- Clear tier-based organization
- Clear dependencies
- Easy to test incrementally
- Easy to debug (each tier can be tested independently)

---

## 🎓 WHAT THIS TEACHES FOR FUTURE PROJECTS

**Good Architecture = Clear Dependencies:**

```
✅ GOOD:
Database Config ← Used by everything
Functions ← Used by controllers & pages
Controllers ← Used by pages & API
Pages ← Use functions & controllers
API ← Uses functions & controllers

❌ BAD:
Everything mixed together
Everything depends on everything
Can't add/remove features without breaking
Can't test one piece in isolation
```

**Your clean project follows the GOOD pattern.** ✨

---

## 📋 DEPENDENCY SUMMARY TABLE

| File | Depends On | Used By |
|------|-----------|---------|
| config/db.php | (none) | Everything |
| config/app.php | (none) | Everything |
| includes/functions.php | config/db.php | All pages, controllers, API |
| auth.php | session_start() | All protected pages |
| login.php | config/db.php, functions.php | Users accessing /login.php |
| logout.php | (none) | Users clicking logout |
| includes/header.php | config/db.php, functions.php | All dashboard pages |
| includes/sidebar.php | (none) | header.php |
| includes/footer.php | (none) | All dashboard pages |
| controllers/* | config/db.php, functions.php | Pages, API endpoints |
| api/* | config/db.php, functions.php | AJAX calls from pages |
| admin/dashboard.php | auth.php, header.php, footer.php, functions.php | Admins accessing /admin/dashboard.php |
| faculty/dashboard.php | auth.php, header.php, footer.php, functions.php | Faculty accessing /faculty/dashboard.php |
| student/dashboard.php | auth.php, header.php, footer.php, functions.php | Students accessing /student/dashboard.php |
| lms_setup.sql | MySQL server | Pages querying database |
| CSS files | (none) | Browser rendering |
| JS files | (none) | Browser execution |

---

**Now you understand why the 8-tier copy order is critical.**

Follow it exactly, test after each tier, and your new project will be clean and professional. 🎉
