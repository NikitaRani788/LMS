# if0_41817906_lms System Architecture

## User Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        LOGIN PAGE                              │
│                      login.php                                 │
│        (Email + Password Validation)                          │
└────────────────┬────────────────────────────────────────────────┘
                 │
        ┌────────┴────────┐
        │   Verify User   │
        │   in Database   │
        └────────┬────────┘
                 │
        ┌────────┴────────┐
        │  Check Role     │
        └────────┬────────┘
                 │
        ┌────────┴────────┬───────────┬──────────┐
        │                 │           │          │
        ▼                 ▼           ▼          ▼
    ┌─────────┐    ┌──────────┐  ┌────────┐  ┌───────┐
    │  ADMIN  │    │ FACULTY  │  │STUDENT │  │ ERROR │
    └────┬────┘    └────┬─────┘  └───┬────┘  └───────┘
         │              │            │
         ▼              ▼            ▼
   ┌──────────┐   ┌──────────┐  ┌──────────┐
   │Dashboard │   │Dashboard │  │Dashboard │
   │Users     │   │Courses   │  │Courses   │
   │Courses   │   │Assign.   │  │Assign.   │
   │Semester  │   │Materials │  │Materials │
   │Announce. │   │Submissions│  │Grades    │
   └──────────┘   └──────────┘  └──────────┘
```

## Database Relationship Diagram

```
                    ┌─────────────┐
                    │   USERS     │
                    │ id, name    │
                    │ role, email │
                    └──────┬──────┘
                           │
          ┌────────────────┼────────────────┐
          │                │                │
          ▼                ▼                ▼
    ┌──────────┐    ┌──────────┐    ┌────────────┐
    │ADMIN     │    │FACULTY   │    │STUDENTS    │
    │Manage    │    │Create    │    │Submit      │
    │all system│    │Assign.   │    │Assignments│
    └──────────┘    │Grade     │    │View grades │
                    └──────────┘    └────────────┘
                           │
          ┌────────────────┴────────────────┐
          │                                 │
          ▼                                 ▼
    ┌──────────────┐            ┌──────────────┐
    │  COURSES     │            │ ENROLLMENTS  │
    │  name, code  │◄───────────┤  user_id     │
    │  credits     │            │  course_id   │
    └──────────────┘            └──────────────┘
          │
          │
          ▼
    ┌──────────────┐
    │ASSIGNMENTS  │
    │title, due   │
    │description  │
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │SUBMISSIONS  │
    │user_id      │
    │grade        │
    │feedback     │
    └──────────────┘
```

## Data Flow in System

```
┌────────────────────────────────────────────────────────────────┐
│                      if0_41817906_lms ARCHITECTURE                          │
└────────────────────────────────────────────────────────────────┘

                         WEB BROWSER
                              │
                    HTTP REQUEST/RESPONSE
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
        ▼                     ▼                     ▼
   ┌────────────┐      ┌────────────┐      ┌────────────┐
   │ Login Page │      │  Dashboard │      │  Operations│
   │ (login.php)│      │(role-based)│      │  (CRUD)    │
   └──────┬─────┘      └──────┬─────┘      └──────┬─────┘
          │                   │                    │
          └───────────┬───────┴────────────┬───────┘
                      │                    │
              ┌───────▼────────┐    ┌──────▼──────┐
              │  $_SESSION     │    │ $_POST/GET  │
              │ (authentication)    │ (form data) │
              └─────────┬──────┘    └──────┬──────┘
                        │                  │
                        └────────┬─────────┘
                                 │
                    ┌────────────▼─────────────┐
                    │  Include/Functions.php   │
                    │  • sanitize()            │
                    │  • database queries      │
                    │  • role checking         │
                    └────────────┬─────────────┘
                                 │
                ┌────────────────▼────────────────┐
                │      config/db.php              │
                │  (PDO Connection)               │
                │  mysql://if0_41817906_lms database           │
                └────────────────┬────────────────┘
                                 │
                        ┌────────▼─────────┐
                        │  MySQL Database  │
                        │  (12 Tables)     │
                        └──────────────────┘
```

## File Relationships

```
index.php (Router)
    │
    ├─► login.php (Authentication)
    │    └─► config/db.php (Connect)
    │
    ├─► admin/ (Role: admin)
    │    ├─ dashboard.php
    │    ├─ users.php
    │    ├─ courses.php
    │    ├─ semesters.php
    │    └─ announcements.php
    │    └─► includes/header.php ◄─┐
    │    └─► includes/footer.php ◄─┤
    │    └─► includes/functions.php ├─► All pages
    │
    ├─► faculty/ (Role: faculty)
    │    ├─ dashboard.php
    │    ├─ courses.php
    │    ├─ assignments.php
    │    ├─ materials.php
    │    └─ submissions.php
    │    └─► includes/... ◄────────┤
    │
    ├─► student/ (Role: student)
    │    ├─ dashboard.php
    │    ├─ courses.php
    │    ├─ assignments.php
    │    ├─ submit_assignment.php
    │    ├─ materials.php
    │    └─ grades.php
    │    └─► includes/... ◄────────┤
    │
    └─► css/style.css ◄────────────┴─► All pages (linked in header)

logout.php
    └─► Destroys session
    └─► Redirects to login.php
```

## Role-Based Access Control

```
┌──────────────────────────────────────────────────────┐
│                    Login Request                     │
└────────────────────┬─────────────────────────────────┘
                     │
                     ▼
           ┌─────────────────────┐
           │  Database Query:    │
           │  SELECT * FROM      │
           │  users WHERE        │
           │  email = ?          │
           │  AND password = ?   │
           └─────────┬───────────┘
                     │
        ┌────────────┴────────────┐
        │                         │
        ▼                         ▼
  ┌──────────┐            ┌─────────────┐
  │ Found    │            │  Not Found  │
  │ Validate │            │  → Login    │
  └────┬─────┘            │  Error      │
       │                  └─────────────┘
       ▼
 ┌───────────────────────┐
 │ $_SESSION['role']     │
 │ $_SESSION['user_id']  │
 │ $_SESSION['user_name']│
 └───────┬───────────────┘
         │
    ┌────┴──────┬──────────┬──────────┐
    │           │          │          │
    ▼           ▼          ▼          ▼
 ┌──────┐  ┌──────────┐  ┌────────┐  ┌────────┐
 │admin │  │  faculty │  │student │  │ other  │
 │      │  │          │  │        │  │ Error  │
 │/admin│  │ /faculty │  │/student│  │ 403    │
 │dash  │  │   dash   │  │  dash  │  │        │
 └──────┘  └──────────┘  └────────┘  └────────┘
```

## Request Processing Flow

```
HTTP REQUEST (login.php)
    │
    ▼
METHOD CHECK ($_SERVER['REQUEST_METHOD'])
    │
    ├─► GET → Display form
    │
    └─► POST → Process form
         │
         ▼
    VALIDATE INPUT
    • Check email not empty
    • Check password not empty
    │
    ├─► Invalid → Show error
    │
    └─► Valid
         │
         ▼
    DATABASE QUERY
    • Prepare statement
    • Execute query
    │
    ├─► No user → Show error
    │
    └─► User found
         │
         ▼
    VERIFY PASSWORD
    • password_verify()
    │
    ├─► Wrong password → Show error
    │
    └─► Correct password
         │
         ▼
    SET SESSION
    $_SESSION['user_id']
    $_SESSION['role']
    $_SESSION['user_name']
         │
         ▼
    REDIRECT BY ROLE
    • Admin → /admin/dashboard.php
    • Faculty → /faculty/dashboard.php
    • Student → /student/dashboard.php
```

## Database Query Pattern (PDO)

```
┌────────────────────────────────────────┐
│     Using Prepared Statements          │
│         (SQL Injection Safe)           │
└────────────────────────────────────────┘

1. PREPARE
   $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');

2. BIND (Automatic with parameters)
   $stmt->execute([$userId]);

3. FETCH
   $user = $stmt->fetch();

4. RESULT
   echo $user['name'];

Example:
   // Instead of: SELECT * FROM users WHERE id = $id
   // This prevents SQL injection attacks
```

## Security Layers

```
┌──────────────────────────────────────┐
│      Input → Application → Database   │
└──────────────────────────────────────┘
    │           │              │
    │           │              │
    ▼           ▼              ▼
┌─────────┐ ┌────────┐    ┌─────────┐
│Sanitize │ │Validate│    │Prepared │
│Input    │ │Role    │    │Statement│
│html     │ │Access  │    │Prevent  │
│special  │ │Control │    │SQL Inj. │
│chars    │ │        │    │         │
└─────────┘ └────────┘    └─────────┘
    │           │              │
    └───────────┴──────────────┘
            │
            ▼
    ┌──────────────────┐
    │  Safe to Use     │
    │  in Application  │
    └──────────────────┘
```

---

**if0_41817906_lms System is fully documented and ready to use!**
