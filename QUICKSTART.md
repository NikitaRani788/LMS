# Quick Start Guide

## 1. Database Setup (Required First)

### Import SQL File:
```bash
# Using MySQL command line
mysql -u if0_41817906 -p < if0_41817906_lms_setup.sql

# Or through phpMyAdmin:
# 1. Open http://sql205.infinityfree.com/phpmyadmin
# 2. Click "Import"
# 3. Select if0_41817906_lms_setup.sql
# 4. Click "Go"
```

This creates:
- Database: `if0_41817906_lms`
- All required tables
- Sample data and test users

## 2. Access the Application

1. Start XAMPP (Apache + MySQL)
2. Navigate to: `http://sql205.infinityfree.com/if0_41817906_lms`
3. You'll be redirected to login page

## 3. Test Login

Use any of these accounts:

```
Admin:
  Email: admin@if0_41817906_lms.com
  Password: password

Faculty:
  Email: faculty@if0_41817906_lms.com
  Password: password

Student:
  Email: student@if0_41817906_lms.com
  Password: password
```

## 4. File Structure Overview

```
d:\xampp\htdocs\if0_41817906_lms\
│
├── config/db.php                 ← Database connection settings
├── includes/                      ← Shared components
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── admin/                         ← Admin pages
├── faculty/                       ← Faculty pages
├── student/                       ← Student pages
├── css/style.css                 ← Main styling
├── uploads/                       ← File storage
│   ├── assignments/              ← Student submissions
│   └── materials/                ← Course materials
├── login.php                      ← Login page
├── logout.php                     ← Logout script
├── index.php                      ← Main routing
├── if0_41817906_lms_setup.sql                 ← Database schema
└── README.md                      ← Full documentation
```

## 5. Creating Test Data

### Add New User (Admin Panel):
1. Login as admin@if0_41817906_lms.com
2. Go to "Manage Users"
3. Fill in the form and submit

### Add New Course (Admin Panel):
1. Go to "Manage Courses"
2. Fill in course details
3. Courses are immediately available

### Enroll Student (Manual Database):
```sql
INSERT INTO enrollments (course_id, user_id) VALUES (1, 3);
```

### Create Assignment (Faculty Panel):
1. Login as faculty@if0_41817906_lms.com
2. Go to "Assignments"
3. Create new assignment
4. Set due date

## 6. Key Pages

### Admin:
- `/admin/dashboard.php` - Statistics and announcements
- `/admin/users.php` - Manage all users
- `/admin/courses.php` - Manage courses
- `/admin/semesters.php` - Manage semesters
- `/admin/announcements.php` - Post system announcements

### Faculty:
- `/faculty/dashboard.php` - Overview
- `/faculty/courses.php` - View students in courses
- `/faculty/assignments.php` - Create/manage assignments
- `/faculty/materials.php` - Upload study materials
- `/faculty/submissions.php` - Grade submissions

### Student:
- `/student/dashboard.php` - Overview
- `/student/courses.php` - View enrolled courses
- `/student/assignments.php` - View assignments
- `/student/submit_assignment.php` - Submit work
- `/student/materials.php` - Download resources
- `/student/grades.php` - View grades

## 7. Common Tasks

### Change Admin Email/Password:
```sql
UPDATE users SET email='newemail@if0_41817906_lms.com', 
password='$2y$10$...' WHERE id=1;
```

### Reset Test User Passwords:
```sql
UPDATE users SET password='$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/NqK';
```

### Delete All Data and Reset:
```sql
DROP DATABASE if0_41817906_lms;
SOURCE if0_41817906_lms_setup.sql;
```

## 8. Troubleshooting

| Issue | Solution |
|-------|----------|
| "Database Connection Failed" | Check MySQL is running and credentials in config/db.php |
| "Login failed" | Verify database was imported successfully |
| "File upload not working" | Check /uploads/ folder exists and has 755 permissions |
| "Page not found" | Verify URL format: http://sql205.infinityfree.com/page.php |
| "Session expired" | Login again, clear browser cache |

## 9. Password Hash Reference

All test users use this hashed password:
```
Hash: $2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/NqK
Plain: password
```

To create new password hashes in PHP:
```php
echo password_hash('yourpassword', PASSWORD_DEFAULT);
```

## 10. Next Steps

1. ✓ Import database
2. ✓ Start application
3. ✓ Test all roles
4. → Add real users
5. → Create courses
6. → Enroll students
7. → Create assignments
8. → Upload materials
9. → Start teaching!

---

**Ready to use!** Start with admin account to set up your courses and users.
