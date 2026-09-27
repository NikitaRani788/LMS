<?php
/**
 * ADMIN - MANAGE USERS
 */
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

// Verify admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}

$current_role = 'admin';
$current_user_id = $_SESSION['user_id'] ?? 1;
$pageTitle = 'Manage Users';
require_once '../includes/header.php';
$message = '';
$error = '';
// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $user_id = intval($_POST['user_id']);
    if ($user_id > 1) {
        try {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$user_id]);
            $message = 'User deleted successfully';
        } catch (Exception $e) {
            $error = 'Error deleting user';
        }
    } else {
        $error = 'Cannot delete admin user';
    }
}

// Handle Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $role = sanitize($_POST['role'] ?? 'student');
    $dept_id = intval($_POST['department_id'] ?? 1);
    
    if ($name && $email && in_array($role, ['admin', 'faculty', 'student'])) {
        try {
            $password = password_hash('password', PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role, department_id) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$name, $email, $password, $role, $dept_id]);
            $message = 'User added successfully';
        } catch (Exception $e) {
            $error = "Actual Error: " . $e->getMessage();
        }
    } else {
        $error = 'Please fill all fields';
    }
}

$users = getAllUsers($pdo);
$departments = getAllDepartments($pdo);
?>

<div class="page-header">
    <h1>👥 Manage Users</h1>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
    
    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0;">Add New User</h2>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="action" value="add">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select">
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo $dept['id']; ?>"><?php echo sanitize($dept['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Add User</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0;">Users List</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['userid']; ?></td>
                                <td><?php echo sanitize($user['name']); ?></td>
                                <td><?php echo sanitize($user['email']); ?></td>
                                <td><span class="badge bg-primary"><?php echo ucfirst($user['role']); ?></span></td>
                                <td><?php 
                                    $dept = getDepartmentById($pdo, $user['department_id']); 
                                    echo $dept ? sanitize($dept['name']) : 'N/A'; 
                                ?></td>
                                <td><?php echo formatDate($user['created_at']); ?></td>
                                <td>
                                    <?php if ($user['userid'] > 1): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="user_id" value="<?php echo $user['userid']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?');">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
