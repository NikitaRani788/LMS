# 🎯 QUICK REFERENCE: What to Copy & What to Change

## One-Page Summary for Migration

---

## 📊 FILE COPY STATUS OVERVIEW

```
PHASE 1: FOUNDATION (Copy these FIRST)
├── ✅ auth.php                           COPY AS-IS
├── ⚠️  config/app.php                    COPY + FIX (paths, debug settings)
├── ✅ config/db.php                      COPY AS-IS
├── ⚠️  includes/footer.php               COPY + UPDATE CSS LINKS
├── ⚠️  includes/functions.php            COPY + FIX BUG (userid → id)
├── ⚠️  includes/header.php               CONSOLIDATE (use unified_header.php)
├── ⚠️  includes/sidebar_*.php            CONSOLIDATE 3 FILES INTO 1
├── ✅ logout.php                         COPY AS-IS
└── ⚠️  login.php                         COPY + FIX BUG (userid → id)

PHASE 2: BUSINESS LOGIC (Copy after Phase 1)
├── ✅ controllers/                       COPY ENTIRE FOLDER
└── ✅ api/                               COPY ENTIRE FOLDER

PHASE 3: ROLE VIEWS (Copy after Phase 2)
├── ✅ admin/                             COPY ENTIRE FOLDER
├── ✅ faculty/                           COPY ENTIRE FOLDER
└── ✅ student/                           COPY ENTIRE FOLDER

PHASE 4: DATABASE (After Phase 3)
└── ✅ lms_setup.sql                      COPY + IMPORT INTO MYSQL

PHASE 5: STYLING (After Phase 4)
├── ✅ css/layout.css                     COPY
├── ✅ css/global-layout.css              COPY
├── ✅ css/style.css                      COPY
└── ⚠️  Other CSS files                   CHECK USAGE, COPY IF NEEDED

PHASE 6: ASSETS (After Phase 5)
├── ✅ images/                            COPY IF EXISTS
├── ✅ fonts/                             COPY IF EXISTS
└── 🆕 uploads/                           CREATE EMPTY FOLDER

CLEANUP
├── 🆕 .env.example                       CREATE NEW
├── 🆕 .gitignore                         CREATE NEW
├── 🆕 README.md                          CREATE NEW
└── ❌ *.md files (old docs)              DELETE (documentation only)
```

---

## 🔴 CRITICAL BUGS TO FIX

### Bug #1: `includes/functions.php` Line 42
```diff
function getUserById($pdo, $userId) {
-   $stmt = $pdo->prepare('SELECT * FROM users WHERE userid = ?');
+   $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    return $stmt->fetch();
}
```

### Bug #2: `login.php` Line ~20
```diff
if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
-   $_SESSION['user_id'] = $user['userid'];
+   $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    header("Location: /" . $user['role'] . "/dashboard.php");
    exit;
}
```

---

## ⚙️ CONFIGURATION CHANGES

### `config/app.php` - Update These Values

```php
// Line 5-10: Development settings
- define('APP_ENV', $env['APP_ENV'] ?? 'production');
+ define('APP_ENV', $env['APP_ENV'] ?? 'development');

- define('APP_DEBUG', filter_var($env['APP_DEBUG'] ?? false, ...));
+ define('APP_DEBUG', filter_var($env['APP_DEBUG'] ?? true, ...));

- define('APP_URL', $env['APP_URL'] ?? 'https://sql205.infinityfree.com');
+ define('APP_URL', $env['APP_URL'] ?? 'http://localhost/new_lms');

// Around line 22: Session security for localhost
- define('SESSION_SECURE', filter_var($env['SESSION_SECURE'] ?? true, ...));
+ define('SESSION_SECURE', filter_var($env['SESSION_SECURE'] ?? false, ...));
```

---

## 📁 FOLDER STRUCTURE FOR NEW PROJECT

```
new_lms/
│
├── public/                    [Web-accessible files]
│   ├── index.php             [Homepage/role selector]
│   ├── login.php             [Login page]
│   ├── logout.php            [Logout endpoint]
│   ├── auth.php              [Auth functions]
│   │
│   ├── admin/                [Admin pages]
│   ├── faculty/              [Faculty pages]
│   ├── student/              [Student pages]
│   │
│   ├── api/                  [JSON endpoints]
│   ├── controllers/          [Business logic]
│   │
│   └── assets/               [Static files]
│       ├── css/              [Stylesheets]
│       ├── js/               [JavaScript]
│       ├── images/           [Images]
│       └── fonts/            [Font files]
│
├── src/                       [Not web-accessible]
│   ├── config/               [Configuration files]
│   │   ├── app.php
│   │   └── db.php
│   │
│   └── includes/             [Shared templates]
│       ├── header.php
│       ├── footer.php
│       ├── sidebar.php
│       └── functions.php
│
├── uploads/                   [User uploaded files - runtime]
├── logs/                      [Error logs - runtime]
│
├── lms_setup.sql             [Database schema]
├── .env.example              [Environment template]
├── .gitignore                [Git ignore rules]
└── README.md                 [Documentation]
```

---

## ✅ STEP-BY-STEP CHECKLIST

### Step 1: Foundation Layer (TEST BEFORE MOVING ON)
- [ ] Copy config/db.php
- [ ] Copy config/app.php + UPDATE VALUES
- [ ] Copy includes/functions.php + FIX userid BUG
- [ ] Copy includes/footer.php
- [ ] Copy unified_header.php → rename to includes/header.php
- [ ] Copy auth.php
- [ ] Copy login.php + FIX userid BUG
- [ ] Copy logout.php
- [ ] Test: Login page loads & login works

### Step 2: Consolidation
- [ ] Analyze sidebar_admin.php, sidebar_faculty.php, sidebar_student.php
- [ ] Create new includes/sidebar.php (role-aware)
- [ ] Update includes/header.php to include new sidebar.php
- [ ] Delete old sidebar_*.php files references

### Step 3: Business Logic
- [ ] Copy entire controllers/ folder
- [ ] Copy entire api/ folder

### Step 4: Role Views
- [ ] Copy entire admin/ folder
- [ ] Copy entire faculty/ folder
- [ ] Copy entire student/ folder
- [ ] Test: Each dashboard loads and shows correct role menu

### Step 5: Database
- [ ] Copy lms_setup.sql
- [ ] Create new MySQL database: `CREATE DATABASE new_lms;`
- [ ] Import: `SOURCE lms_setup.sql;`
- [ ] Insert test accounts or import from old database
- [ ] Test: Login with all 3 accounts works

### Step 6: Styling & Assets
- [ ] Copy css/style.css, layout.css, global-layout.css
- [ ] Copy images/ folder (if exists)
- [ ] Copy fonts/ folder (if exists)
- [ ] Create empty uploads/ folder
- [ ] Test: Pages display with correct styling
- [ ] Update header.php to use CDN for Bootstrap, FontAwesome

### Step 7: Configuration
- [ ] Create .env.example with template values
- [ ] Create .gitignore (exclude .env, uploads/, logs/)
- [ ] Create README.md with quick start guide
- [ ] Create .env with actual values (don't commit)

### Step 8: Final Testing
- [ ] Test all login accounts (admin, faculty, student)
- [ ] Test each role dashboard loads
- [ ] Test sidebar shows correct role menu
- [ ] Test API endpoints work
- [ ] Test file uploads to uploads/ folder
- [ ] Test CSS/styling on all pages

---

## 🗑️ FILES TO DELETE/IGNORE

```
❌ DON'T COPY THESE (Old project bloat):
├── All *.md files except README.md
├── test_*.php
├── check_*.php
├── debug_*.php
├── hash.php, gen_hash.php, update_passwords.php
├── config/services.php
├── bootstrap5/ (use CDN instead)
├── jquery/ (use CDN instead)
└── 30+ CSS files (only copy: style.css, layout.css, global-layout.css)
```

---

## 📋 FILE COUNT SUMMARY

**TOTAL FILES TO COPY:**
- Config files: 2
- Include/template files: 4
- Main entry points: 3
- Controller files: 14
- API endpoint files: 10
- Admin pages: 5
- Faculty pages: 5
- Student pages: 6
- CSS files: 3 (main only)
- Database schema: 1
- **Total: ~53 files (clean and minimal)**

**OLD PROJECT HAS:**
- 40+ CSS files (mostly unused)
- 20+ test/debug files
- 15+ documentation files
- 3 duplicate sidebar files
- **Total: 150+ files (bloated)**

---

## 🎓 WHAT TO EXPLAIN IN VIVA

**With your NEW clean project, you can simply explain:**

> "My LMS has 4 architectural layers:
> 
> 1. **Presentation Layer** (admin/, faculty/, student/) - User interface pages
> 2. **Business Logic** (controllers/, api/) - Application logic & database operations  
> 3. **Data Access** (includes/functions.php) - Shared database query helpers
> 4. **Database** (MySQL with 14 related tables)
>
> Authentication is handled by auth.php which checks sessions and enforces role-based access.
> 
> The config/ folder contains all application settings.
> The includes/ folder contains reusable templates for consistent styling.
>
> CSS is centralized in 3 main files for consistency.
> 
> This architecture makes it easy to maintain, test, and scale."

**That's clear, professional, and easy to explain.** ✨

---

## 🚀 WHEN YOU'RE READY

Once all steps complete:
1. You have a clean, professional project
2. No duplication or confusion
3. Easy to explain to others
4. Ready for deployment
5. Perfect for demonstrating in practicals/viva

**Total time estimate:** 2-3 hours for careful migration

Good luck! 🎉
