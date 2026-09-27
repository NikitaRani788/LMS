<?php
require_once 'config/app.php';
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        // Fetch user by email only
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
            // Start session and store data
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['userid'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            header("Location: /" . $user['role'] . "/dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - if0_41817906_lms</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 400px;
        }

        .btn-login {
            width: 100%;
            background: #2563eb;
            color: white;
        }

        .btn-login:hover {
            background: #1e40af;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h3 class="text-center mb-3">if0_41817906_lms Login</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <!-- Role -->
        <div class="mb-3">
            <label class="form-label">Login as</label><br>

            <input type="radio" name="role" value="student" <?php echo $selectedRole=='student'?'checked':''; ?>> Student
            <input type="radio" name="role" value="faculty" <?php echo $selectedRole=='faculty'?'checked':''; ?>> Faculty
            <input type="radio" name="role" value="admin" <?php echo $selectedRole=='admin'?'checked':''; ?>> Admin
        </div>
<br>
        <!-- Email -->
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
<br>
        <!-- Password -->
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
<br>
        <!-- Button -->
        <button type="submit" class="btn btn-login">
            <i class="bi bi-box-arrow-in-right"></i> Login
        </button>

    </form>

    <p class="text-center mt-3">
        Don't have an account? <a href="/register.php">Register</a>
    </p>
</div>

</body>
</html>