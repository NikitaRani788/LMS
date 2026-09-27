<?php
require_once 'config/app.php';
require_once 'config/db.php';
require_once 'includes/functions.php';

$message = '';
$success = false;

// Fetch departments (optional)
$departments = getAllDepartments($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = sanitize($_POST['role']);
    $department_id = $_POST['department_id'] ?? null;

    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $message = "All required fields must be filled!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters!";
    } else {

        // Check existing email
        $stmt = $pdo->prepare("SELECT userid FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $message = "Email already exists!";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, password, role, department_id)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            if ($stmt->execute([$name, $email, $hashedPassword, $role, $department_id])) {
                $success = true;
                $message = "Registration successful! You can now login.";
            } else {
                $message = "Registration failed!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>if0_41817906_lms Register</title>
    <style>
        body {
            font-family: Arial;
            background: linear-gradient(120deg, #2980b9, #6dd5fa);
            margin: 0;
        }

        .container {
            width: 450px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #1f6690;
        }

        .msg {
            text-align: center;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .error {
            background: #ffcccc;
            color: red;
        }

        .success {
            background: #ccffcc;
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Create Account</h2>

    <?php if ($message): ?>
        <div class="msg <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="name" placeholder="Full Name" required>

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="password" name="confirm_password" placeholder="Confirm Password" required>

        <select name="role" required>
            <option value="">Select Role</option>
            <option value="student">Student</option>
            <option value="faculty">Faculty</option>
            <option value="admin">Admin</option>
        </select>

        <select name="department_id">
            <option value="">Select Department (Optional)</option>
            <?php foreach ($departments as $dept): ?>
                <option value="<?php echo $dept['id']; ?>">
                    <?php echo $dept['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Register</button>
    </form>

    <p style="text-align:center; margin-top:10px;">
        Already have an account? <a href="login.php">Login</a>
    </p>
</div>

</body>
</html>