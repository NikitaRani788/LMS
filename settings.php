<?php
/**
 * USER SETTINGS PAGE
 * Accessible to all users (Admin, Faculty, Student)
 */
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// Verify user is logged in
if (!isset($_SESSION['role']) || !isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$current_role = $_SESSION['role'];
$current_user_id = $_SESSION['user_id'];
$pageTitle = 'Settings';
require_once __DIR__ . '/includes/header.php';

$message = '';
$error = '';

// Get current user info
$current_user = getUserById($pdo, $current_user_id);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    
    if ($name && $email) {
        try {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
            $stmt->execute([$name, $email, $current_user_id]);
            $message = 'Profile updated successfully';
            $current_user = getUserById($pdo, $current_user_id);
        } catch (Exception $e) {
            $error = 'Error updating profile: ' . $e->getMessage();
        }
    } else {
        $error = 'Please fill all required fields';
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if ($new_password === $confirm_password && !empty($new_password)) {
        // Verify current password (if needed)
        try {
            $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            $stmt->execute([$new_hash, $current_user_id]);
            $message = 'Password changed successfully';
        } catch (Exception $e) {
            $error = 'Error changing password: ' . $e->getMessage();
        }
    } else {
        $error = 'Passwords do not match or are empty';
    }
}
?>

<!-- Settings Header -->
<div class="page-header">
    <h1>⚙️ Settings</h1>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<!-- Settings Tabs -->
<div class="row">
    <div class="col-md-8">
        <!-- Profile Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2 style="margin: 0;">👤 Profile Settings</h2>
            </div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" 
                               value="<?php echo sanitize($current_user['name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?php echo sanitize($current_user['email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">User Role</label>
                        <input type="text" class="form-control" 
                               value="<?php echo ucfirst($current_role); ?>" disabled>
                        <small class="text-muted">Role cannot be changed</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Member Since</label>
                        <input type="text" class="form-control" 
                               value="<?php echo date('M d, Y', strtotime($current_user['created_at'] ?? 'now')); ?>" disabled>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2 style="margin: 0;">🔐 Change Password</h2>
            </div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="col-12">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    
                    <div class="col-12">
                        <small class="text-muted">Password must be at least 8 characters long</small>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notification Preferences Card -->
        <div class="card">
            <div class="card-header">
                <h2 style="margin: 0;">🔔 Notification Preferences</h2>
            </div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                            <label class="form-check-label" for="emailNotif">
                                <strong>Email Notifications</strong>
                                <small class="d-block text-muted">Receive email updates for important events</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="courseNotif" checked>
                            <label class="form-check-label" for="courseNotif">
                                <strong>Course Notifications</strong>
                                <small class="d-block text-muted">Get notified about course updates and announcements</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="assignmentNotif" checked>
                            <label class="form-check-label" for="assignmentNotif">
                                <strong>Assignment Notifications</strong>
                                <small class="d-block text-muted">Get notified about new assignments and deadlines</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="systemNotif" checked>
                            <label class="form-check-label" for="systemNotif">
                                <strong>System Notifications</strong>
                                <small class="d-block text-muted">Receive important system and maintenance notifications</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" onclick="alert('Preferences saved!')">Save Preferences</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info Card -->
    <div class="col-md-4">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div style="width: 70px; height: 70px; border-radius: 50%; background: rgba(255,255,255,0.2); 
                                display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 2rem;">
                        <?php echo strtoupper(substr($current_user['name'] ?? 'U', 0, 1)); ?>
                    </div>
                </div>
                
                <h4 style="text-align: center; margin-bottom: 0.5rem;"><?php echo sanitize($current_user['name'] ?? 'User'); ?></h4>
                <p style="text-align: center; margin-bottom: 1.5rem; opacity: 0.9;">
                    <strong><?php echo ucfirst($current_role); ?></strong>
                </p>
                
                <hr style="background: rgba(255,255,255,0.3); border: none; height: 1px;">
                
                <p style="margin: 1rem 0 0.5rem;">
                    <i class="fas fa-envelope me-2"></i>
                    <small><?php echo sanitize($current_user['email'] ?? 'N/A'); ?></small>
                </p>
                
                <p style="margin: 0.5rem 0;">
                    <i class="fas fa-calendar me-2"></i>
                    <small>Joined: <?php echo date('M d, Y', strtotime($current_user['created_at'] ?? 'now')); ?></small>
                </p>
            </div>
        </div>

        <!-- Account Status Card -->
        <div class="card" style="margin-top: 1rem;">
            <div class="card-header">
                <h5 style="margin: 0;">Account Status</h5>
            </div>
            <div class="card-body">
                <p><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                <p><strong>Last Login:</strong> <small>Today at 10:30 AM</small></p>
                <p><strong>Account Security:</strong> <small>✓ Secure</small></p>
            </div>
        </div>

        <!-- Danger Zone Card -->
        <div class="card" style="margin-top: 1rem; border: 2px solid #ef4444;">
            <div class="card-header" style="background: #fee2e2; color: #991b1b;">
                <h5 style="margin: 0;">⚠️ Danger Zone</h5>
            </div>
            <div class="card-body">
                <p><small>Delete your account and all associated data</small></p>
                <button class="btn btn-danger btn-sm" onclick="alert('Account deletion requires admin approval')">Delete Account</button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
