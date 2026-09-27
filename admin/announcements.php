<?php
/**
 * ADMIN ANNOUNCEMENTS PAGE
 * ================================================================
 * 
 * New unified layout approach:
 * 1. Set role and page variables BEFORE any output
 * 2. Include unified header (provides layout + navbar + sidebar)
 * 3. Add page content inside .app-content section
 * 4. Include unified footer (closes layout + provides scripts)
 */

// Session & Database Setup
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Verify admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}

// Set page context variables
$current_role = 'admin';
$current_user_id = $_SESSION['user_id'] ?? 1;
$pageTitle = 'Manage Announcements';

// Include header
require_once __DIR__ . '/../includes/header.php';

$message = '';
$error = '';

// Handle Add Announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = sanitize($_POST['title'] ?? '');
    $content = sanitize($_POST['content'] ?? '');
    
    if ($title && $content) {
        try {
            $stmt = $pdo->prepare('INSERT INTO announcements (user_id, title, content, created_at) VALUES (?, ?, ?, NOW())');
            $stmt->execute([$current_user_id, $title, $content]);
            $message = 'Announcement posted successfully';
        } catch (Exception $e) {
            $error = 'Error posting announcement: ' . $e->getMessage();
        }
    } else {
        $error = 'Please fill all required fields';
    }
}

// Handle Delete Announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $ann_id = intval($_POST['announcement_id'] ?? 0);
    if ($ann_id > 0) {
        try {
            $stmt = $pdo->prepare('DELETE FROM announcements WHERE id = ?');
            $stmt->execute([$ann_id]);
            $message = 'Announcement deleted successfully';
        } catch (Exception $e) {
            $error = 'Error deleting announcement';
        }
    }
}

// Fetch all announcements
$announcements = [];
try {
    $stmt = $pdo->query('SELECT * FROM announcements ORDER BY created_at DESC LIMIT 50');
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log('Error fetching announcements: ' . $e->getMessage());
}
?>

<!-- PAGE HEADER -->
<div class="page-header mb-4">
    <h1 class="display-5 font-weight-600">
        <i class="fas fa-bullhorn me-2"></i>Announcements
    </h1>
    <p class="text-muted mb-0">Create and manage system announcements</p>
</div>

<!-- ALERT MESSAGES -->
<?php if (!empty($message)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <strong>Success!</strong> <?php echo htmlspecialchars($message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- CREATE ANNOUNCEMENT SECTION -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0">
            <i class="fas fa-plus-circle me-2 text-primary"></i>Create New Announcement
        </h5>
    </div>
    <div class="card-body">
        <form method="POST" novalidate>
            <input type="hidden" name="action" value="add">
            
            <div class="mb-3">
                <label for="announcementTitle" class="form-label font-weight-600">
                    Announcement Title <span class="text-danger">*</span>
                </label>
                <input 
                    type="text" 
                    class="form-control form-control-lg" 
                    id="announcementTitle"
                    name="title" 
                    placeholder="e.g., New Semester Begins" 
                    required
                    maxlength="255">
                <small class="text-muted">Be clear and concise (max 255 characters)</small>
            </div>
            
            <div class="mb-3">
                <label for="announcementContent" class="form-label font-weight-600">
                    Content <span class="text-danger">*</span>
                </label>
                <textarea 
                    class="form-control" 
                    id="announcementContent"
                    name="content" 
                    rows="8" 
                    placeholder="Enter announcement content here..." 
                    required></textarea>
                <small class="text-muted">Provide detailed information for all users</small>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane me-2"></i>Post Announcement
                </button>
                <button type="reset" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-redo me-2"></i>Clear Form
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ANNOUNCEMENTS LIST SECTION -->
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-info"></i>Recent Announcements
                <span class="badge bg-secondary ms-2"><?php echo count($announcements); ?></span>
            </h5>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($announcements)): ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th width="35%">Title</th>
                            <th width="20%">Author</th>
                            <th width="25%">Posted</th>
                            <th width="20%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($announcements as $ann): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($ann['title']); ?></strong>
                                    <br>
                                    <small class="text-muted text-truncate d-block">
                                        <?php echo htmlspecialchars(substr($ann['content'], 0, 100)) . (strlen($ann['content']) > 100 ? '...' : ''); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php 
                                    // Try to get user name
                                    try {
                                        $stmt = $pdo->prepare('SELECT name FROM users WHERE id = ?');
                                        $stmt->execute([$ann['user_id']]);
                                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                        echo $user ? htmlspecialchars($user['name']) : 'Unknown';
                                    } catch (Exception $e) {
                                        echo 'Unknown';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <small>
                                        <?php 
                                        echo isset($ann['created_at']) 
                                            ? date('M d, Y @ H:i', strtotime($ann['created_at']))
                                            : 'N/A';
                                        ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="announcement_id" value="<?php echo $ann['id']; ?>">
                                        <button 
                                            type="submit" 
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this announcement?');"
                                            title="Delete this announcement">
                                            <i class="fas fa-trash-alt me-1"></i>Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No announcements yet. Create one to get started!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Page-specific styles (optional) */
    .page-header {
        padding-bottom: 1.5rem;
        border-bottom: 2px solid var(--gray-light);
    }
    
    .page-header h1 {
        color: var(--dark);
        margin-bottom: 0.25rem;
    }
    
    .card {
        border: none;
        border-radius: var(--border-radius);
    }
    
    .card-header {
        background-color: transparent !important;
    }
</style>

<?php
// Include unified footer (closes .app-content, .app-main, .app-container, </body>, </html>)
require_once __DIR__ . '/../includes/unified_footer.php';
?>

