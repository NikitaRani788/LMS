<?php
/**
 * INDEX.PHP - IMPROVED if0_41817906_lms ENTRY POINT WITH INTEGRATED LOGIN
 * 
 * Features:
 * - Modern horizontal navbar with proper Bootstrap styling
 * - Carousel hero section with 3 if0_41817906_lms-focused slides
 * - Updated if0_41817906_lms features section (6 features)
 * - Integrated login section with role cards (Admin/Faculty/Student)
 * - Professional footer with 3 columns
 * - Session handling with role-based redirection
 * 
 * This file handles:
 * 1. Role selection via POST forms
 * 2. Session variable setting ($_SESSION['role'], $_SESSION['user_id'], $_SESSION['user_name'])
 * 3. Automatic redirection to appropriate dashboard
 */

session_start();

// Default user IDs for each role (demo purposes)
$roleUsers = [
    'admin' => 1,    // Admin User
    'faculty' => 2,  // Faculty Member
    'student' => 3   // Student
];

// Handle role selection (POST from login section)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    $role = $_POST['role'];
    if (in_array($role, ['admin', 'faculty', 'student'])) {
        // Set session variables - consistent names with dashboards
        $_SESSION['role'] = $role;
        $_SESSION['user_id'] = $roleUsers[$role];
        $_SESSION['user_name'] = ucfirst($role) . ' User';
        
        // Redirect to appropriate dashboard
        $redirects = [
            'admin' => '/admin/dashboard.php',
            'faculty' => '/faculty/dashboard.php',
            'student' => '/student/dashboard.php'
        ];
        
        header('Location: ' . $redirects[$role]);
        exit;
    }
}

// Get current role for display (if already selected)
$current_role = $_SESSION['role'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Management System - Welcome</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #f59e0b;
        }

        /* ========================================
           NAVBAR STYLING
           ======================================== */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9) !important;
            margin: 0 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: var(--accent-color) !important;
        }

        .navbar-nav .nav-link.active {
            color: var(--accent-color) !important;
        }

        .btn-login {
            background-color: var(--accent-color) !important;
            color: white !important;
            padding: 8px 20px !important;
            border-radius: 25px;
            margin-left: 10px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-login:hover {
            background-color: #d97706 !important;
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(245, 158, 11, 0.4);
        }

        /* ========================================
           LOGIN SECTION STYLING
           ======================================== */
        .login-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .login-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .login-title {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 50px;
            color: var(--primary-color);
            position: relative;
            padding-bottom: 20px;
        }

        .login-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--primary-color));
            border-radius: 2px;
        }

        .role-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .role-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary-color);
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.15);
        }

        .role-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .role-card h3 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }

        .role-card p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 25px;
            line-height: 1.6;
            min-height: 60px;
        }

        .role-card form {
            margin: 0;
        }

        .btn-role {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 1rem;
        }

        .btn-role:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
        }

        /* ========================================
           HERO CAROUSEL STYLING
           ======================================== */
        .carousel-section {
            position: relative;
            height: 600px;
            overflow: hidden;
        }

        .carousel-item {
            height: 600px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .carousel-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
        }

        .carousel-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            width: 90%;
            max-width: 600px;
            z-index: 10;
            animation: slideInUp 0.8s ease;
        }

        .carousel-content h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        }

        .carousel-content p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translate(-50%, -40%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .carousel-control-prev, .carousel-control-next {
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .carousel-control-prev:hover, .carousel-control-next:hover {
            opacity: 1;
            background-color: var(--accent-color);
        }

        /* ========================================
           FEATURES SECTION STYLING
           ======================================== */
        .features-section {
            padding: 80px 20px;
            background: #f8fafc;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 50px;
            color: var(--primary-color);
            position: relative;
            padding-bottom: 20px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--primary-color));
            border-radius: 2px;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.2);
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }

        .feature-card p {
            color: #666;
            line-height: 1.8;
        }

        /* ========================================
           CONTACT SECTION STYLING
           ======================================== */
        .contact-section {
            padding: 80px 20px;
            background: white;
        }

        .contact-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-primary-cta {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-block;
            cursor: pointer;
        }

        .btn-primary-cta:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
            color: white;
            text-decoration: none;
        }

        /* ========================================
           FOOTER STYLING
           ======================================== */
        footer {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            padding: 40px 20px 20px;
            text-align: center;
        }

        footer p {
            margin: 10px 0;
        }

        footer a {
            color: var(--accent-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        footer a:hover {
            text-decoration: underline;
        }

        footer h5 {
            font-weight: bold;
            margin-bottom: 15px;
        }

        /* ========================================
           RESPONSIVE DESIGN
           ======================================== */
        @media (max-width: 768px) {
            .carousel-content h1 {
                font-size: 2rem;
            }

            .carousel-content p {
                font-size: 1rem;
            }

            .carousel-item {
                height: 400px;
            }

            .carousel-section {
                height: 400px;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .role-card {
                padding: 30px 20px;
            }

            .role-icon {
                font-size: 2.5rem;
            }

            .role-card h3 {
                font-size: 1.4rem;
            }

            .login-title {
                font-size: 1.8rem;
            }

            .navbar {
                padding: 0.8rem 1rem;
            }

            .navbar-nav .nav-link {
                margin: 0 8px;
            }
        }
    </style>
</head>
<body>
    <!-- ========================================
         NAVIGATION BAR
         ======================================== -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/index.php">
                <i class="bi bi-book"></i> if0_41817906_lms Platform
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-login" href="#login">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ========================================
         HERO CAROUSEL
         ======================================== -->
    <div id="heroCarousel" class="carousel slide carousel-section" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=1200&h=600&fit=crop');">
                <div class="carousel-content">
                    <h1>University Learning Management System</h1>
                    <p>A complete platform for managing courses, assignments, and academic excellence</p>
                    <a href="#login" class="btn-primary-cta">Get Started</a>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200&h=600&fit=crop');">
                <div class="carousel-content">
                    <h1>Track Your Academic Progress</h1>
                    <p>Monitor grades, submissions, and performance with real-time analytics</p>
                    <a href="#login" class="btn-primary-cta">Access Dashboard</a>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200&h=600&fit=crop');">
                <div class="carousel-content">
                    <h1>Collaborate & Learn Together</h1>
                    <p>Connect with instructors and peers in an interactive learning environment</p>
                    <a href="#login" class="btn-primary-cta">Login Now</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>

    <!-- ========================================
         FEATURES SECTION
         ======================================== -->
    <section class="features-section" id="features">
        <div class="container">
            <h2 class="section-title">if0_41817906_lms Features</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-book-half"></i>
                        </div>
                        <h3>Course Management</h3>
                        <p>Create, organize, and manage courses with rich multimedia content, syllabus, and learning objectives.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <h3>Assignment System</h3>
                        <p>Assign work, set deadlines, collect submissions, and provide detailed feedback to students.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <h3>Student Dashboard</h3>
                        <p>View enrolled courses, track academic progress, access materials, and submit assignments.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h3>Faculty Tools</h3>
                        <p>Manage courses, grade submissions, track student progress, and communicate with students.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-calendar2-range"></i>
                        </div>
                        <h3>Semester Management</h3>
                        <p>Organize courses by semester, manage enrollment periods, and handle academic calendars.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h3>Role-Based Access</h3>
                        <p>Secure access control for Admin, Faculty, and Students with role-specific features.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================
         LOGIN SECTION WITH ROLE SELECTION
         ======================================== -->
    <section id="login" class="login-section">
        <div class="login-container">
            <h2 class="login-title">Select Your Role to Login</h2>
            <div class="row g-4">
                <!-- Admin Card -->
                <div class="col-md-4">
                    <div class="role-card">
                        <div class="role-icon">👨‍💼</div>
                        <h3>Admin</h3>
                        <p>Manage users, courses, semesters, announcements, and system settings.</p>
                        <form method="POST">
                            <input type="hidden" name="role" value="admin">
                            <button type="submit" class="btn-role">Access Admin</button>
                        </form>
                    </div>
                </div>

                <!-- Faculty Card -->
                <div class="col-md-4">
                    <div class="role-card">
                        <div class="role-icon">👨‍🏫</div>
                        <h3>Faculty</h3>
                        <p>Manage courses, assignments, student submissions, and grades.</p>
                        <form method="POST">
                            <input type="hidden" name="role" value="faculty">
                            <button type="submit" class="btn-role">Access Faculty</button>
                        </form>
                    </div>
                </div>

                <!-- Student Card -->
                <div class="col-md-4">
                    <div class="role-card">
                        <div class="role-icon">👨‍🎓</div>
                        <h3>Student</h3>
                        <p>View courses, submit assignments, track progress, and access materials.</p>
                        <form method="POST">
                            <input type="hidden" name="role" value="student">
                            <button type="submit" class="btn-role">Access Student</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================
         CONTACT SECTION
         ======================================== -->
    <section class="contact-section" id="contact">
        <div class="container">
            <h2 class="section-title">Get In Touch</h2>
            <form class="contact-form" action="#" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Subject" required>
                </div>
                <div class="form-group">
                    <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                </div>
                <button type="submit" class="btn-primary-cta w-100">Send Message</button>
            </form>
        </div>
    </section>

    <!-- ========================================
         FOOTER
         ======================================== -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About if0_41817906_lms</h5>
                    <p>A modern learning management system designed for educational excellence and student success.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <p>
                        <a href="#features">Features</a> | 
                        <a href="#contact">Contact</a> | 
                        <a href="#login">Login</a>
                    </p>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <p>Email: <a href="mailto:info@if0_41817906_lms.edu">info@if0_41817906_lms.edu</a></p>
                    <p>Phone: <a href="tel:+15551234567">+1 (555) 123-4567</a></p>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <p>&copy; 2024 if0_41817906_lms Platform. All rights reserved.</p>
        </div>
    </footer>

    <!-- ========================================
         SCRIPTS
         ======================================== -->
    <script src="/bootstrap5/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Update active nav link on scroll
        window.addEventListener('scroll', () => {
            let current = '';
            const sections = document.querySelectorAll('section, .carousel-section');
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').substring(1) === current) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
