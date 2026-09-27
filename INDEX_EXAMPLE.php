<?php
/**
 * INDEX.PHP - ROLE SELECTOR EXAMPLE (FOR DEMO/TESTING)
 * Location: /index.php
 * 
 * In production, replace with login.php for real authentication
 * This file demonstrates proper role selection and redirection
 */

// 1. START SESSION
session_start();

// 2. DEMO USER IDs FOR EACH ROLE (replace with actual login logic in production)
$roleUsers = [
    'admin' => 1,    // Admin User ID
    'faculty' => 2,  // Faculty Member User ID
    'student' => 3   // Student User ID
];

// 3. HANDLE ROLE SELECTION (POST request from form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    $role = $_POST['role'];
    
    // Validate role
    if (in_array($role, ['admin', 'faculty', 'student'])) {
        // 4. SET SESSION VARIABLES (using consistent names across system)
        $_SESSION['role'] = $role;
        $_SESSION['user_id'] = $roleUsers[$role];
        $_SESSION['user_name'] = ucfirst($role) . ' User';
        
        // 5. REDIRECT TO APPROPRIATE DASHBOARD
        $redirects = [
            'admin' => '/admin/dashboard.php',
            'faculty' => '/faculty/dashboard.php',
            'student' => '/student/dashboard.php'
        ];
        
        header('Location: ' . $redirects[$role]);
        exit;
    }
}

// 6. GET CURRENT ROLE FOR DISPLAY (if already selected)
$current_role = $_SESSION['role'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Management System - Role Selector</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #1e40af;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container-role {
            max-width: 900px;
            width: 100%;
            padding: 20px;
        }

        .card-header-role {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
            border-radius: 12px 12px 0 0;
        }

        .card-header-role h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .role-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 40px;
            background: white;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .role-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .role-card:hover {
            border-color: var(--primary);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);
            transform: translateY(-5px);
        }

        .role-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .role-card h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .role-card p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .btn-role {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-role:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
        }
    </style>
</head>
<body>
    <div class="container-role">
        <div class="card-header-role">
            <h1>📚 if0_41817906_lms Dashboard</h1>
            <p>Select your role to access the system</p>
        </div>

        <div class="role-grid">
            <!-- ADMIN ROLE -->
            <div class="role-card">
                <div class="role-icon">👨‍💼</div>
                <h3>Admin</h3>
                <p>Manage users, courses, semesters, and system settings</p>
                <form method="POST">
                    <input type="hidden" name="role" value="admin">
                    <button type="submit" class="btn-role">Access Admin Dashboard</button>
                </form>
            </div>

            <!-- FACULTY ROLE -->
            <div class="role-card">
                <div class="role-icon">👨‍🏫</div>
                <h3>Faculty</h3>
                <p>Manage courses, assignments, grades, and student submissions</p>
                <form method="POST">
                    <input type="hidden" name="role" value="faculty">
                    <button type="submit" class="btn-role">Access Faculty Dashboard</button>
                </form>
            </div>

            <!-- STUDENT ROLE -->
            <div class="role-card">
                <div class="role-icon">👨‍🎓</div>
                <h3>Student</h3>
                <p>View courses, assignments, grades, and course materials</p>
                <form method="POST">
                    <input type="hidden" name="role" value="student">
                    <button type="submit" class="btn-role">Access Student Dashboard</button>
                </form>
            </div>
        </div>
    </div>

    <script src="/bootstrap5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
