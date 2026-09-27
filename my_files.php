<?php
/**
 * MY FILES PAGE
 * File management for all users (Admin, Faculty, Student)
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
$pageTitle = 'My Files';
require_once __DIR__ . '/includes/header.php';

$message = '';
$error = '';

// Create uploads directory if it doesn't exist
$upload_dir = __DIR__ . '/uploads/user_files/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload') {
    if (!isset($_FILES['file']) || $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = 'Please select a file to upload';
    } else {
        $file = $_FILES['file'];
        $filename = sanitize(basename($file['name']));
        $file_size = $file['size'];
        $file_tmp = $file['tmp_name'];
        $file_type = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Security validations
        if ($file_size > MAX_FILE_SIZE) {
            $error = 'File size exceeds ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB limit';
        } elseif (!in_array($file_type, ALLOWED_FILE_TYPES)) {
            $error = 'File type not allowed. Allowed types: ' . implode(', ', ALLOWED_FILE_TYPES);
        } elseif (!is_uploaded_file($file_tmp)) {
            $error = 'Invalid file upload';
        } else {
            // Create user-specific directory
            $user_dir = $upload_dir . $current_user_id . '/';
            if (!is_dir($user_dir)) {
                mkdir($user_dir, 0755, true);
            }

            // Generate unique filename to prevent overwrites
            $unique_filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
            $file_path = $user_dir . $unique_filename;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $message = 'File uploaded successfully: ' . $filename;
            } else {
                $error = 'Failed to upload file';
            }
        }
    }
}

// Handle file delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $filename = sanitize($_POST['filename'] ?? '');
    $user_dir = $upload_dir . $current_user_id . '/';
    $file_path = $user_dir . $filename;
    
    // Security check - ensure file is in user's directory
    if (strpos(realpath($file_path), realpath($user_dir)) === 0 && file_exists($file_path)) {
        if (unlink($file_path)) {
            $message = 'File deleted successfully';
        } else {
            $error = 'Failed to delete file';
        }
    } else {
        $error = 'Invalid file path';
    }
}

// Get list of user's files
$user_dir = $upload_dir . $current_user_id . '/';
$user_files = [];
if (is_dir($user_dir)) {
    $files = array_diff(scandir($user_dir), ['.', '..']);
    foreach ($files as $file) {
        $file_path = $user_dir . $file;
        if (is_file($file_path)) {
            $user_files[] = [
                'name' => $file,
                'size' => filesize($file_path),
                'modified' => filemtime($file_path),
                'type' => pathinfo($file, PATHINFO_EXTENSION)
            ];
        }
    }
}

// Sort by modified date (newest first)
usort($user_files, function($a, $b) {
    return $b['modified'] - $a['modified'];
});

// Helper function to format file size
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, 2) . ' ' . $units[$pow];
}

// Helper function to get file icon
function getFileIcon($extension) {
    $icons = [
        'pdf' => 'fa-file-pdf',
        'doc' => 'fa-file-word',
        'docx' => 'fa-file-word',
        'xls' => 'fa-file-excel',
        'xlsx' => 'fa-file-excel',
        'ppt' => 'fa-file-powerpoint',
        'pptx' => 'fa-file-powerpoint',
        'zip' => 'fa-file-archive',
        'rar' => 'fa-file-archive',
        'jpg' => 'fa-file-image',
        'jpeg' => 'fa-file-image',
        'png' => 'fa-file-image',
        'gif' => 'fa-file-image',
        'txt' => 'fa-file-text',
        'csv' => 'fa-file-csv'
    ];
    return $icons[strtolower($extension)] ?? 'fa-file';
}
?>

<!-- Page Header -->
<div class="page-header">
    <h1>📁 My Files</h1>
    <p style="color: #6b7280; margin: 0.5rem 0;">Manage your uploaded documents and files</p>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<!-- File Upload Card -->
<div class="card">
    <div class="card-header">
        <h2 style="margin: 0;">📤 Upload New File</h2>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload">
            
            <div class="row">
                <div class="col-md-8">
                    <div style="border: 2px dashed #cbd5e1; border-radius: 10px; padding: 2rem; text-align: center; background: #f8fafc; transition: all 0.3s ease;" 
                         id="dropZone" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: #2563eb; margin-bottom: 1rem; display: block;"></i>
                        <p style="margin: 0.5rem 0;">
                            <strong>Drag and drop your file here</strong> or <label style="cursor: pointer; color: #2563eb; text-decoration: underline;">browse</label>
                        </p>
        <p style="margin: 0; font-size: 0.85rem; color: #6b7280;">Maximum file size: <?php echo (MAX_FILE_SIZE / 1024 / 1024); ?>MB</p>
                        <input type="file" id="fileInput" name="file" style="display: none;" onchange="handleFileSelect(event)">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div style="background: #f0f9ff; border-radius: 10px; padding: 1rem; border-left: 4px solid #0284c7;">
                        <h5 style="margin-top: 0;">✓ Supported Files</h5>
                        <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.9rem;">
                            <li>Documents (PDF, DOC, DOCX)</li>
                            <li>Spreadsheets (XLS, XLSX)</li>
                            <li>Presentations (PPT, PPTX)</li>
                            <li>Images (JPG, PNG, GIF)</li>
                            <li>Archives (ZIP, RAR)</li>
                            <li>Text Files (TXT, CSV)</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
                    <i class="fas fa-upload me-2"></i>Upload File
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Files List Card -->
<div class="card">
    <div class="card-header">
        <h2 style="margin: 0; display: flex; justify-content: space-between; align-items: center;">
            <span>📋 Your Files</span>
            <span style="font-size: 0.9rem; color: #6b7280; font-weight: normal;">
                <?php echo count($user_files); ?> file<?php echo count($user_files) !== 1 ? 's' : ''; ?>
            </span>
        </h2>
    </div>
    <div class="card-body">
        <?php if (empty($user_files)): ?>
            <div style="text-align: center; padding: 2rem; color: #6b7280;">
                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.5;"></i>
                <p><strong>No files yet</strong></p>
                <p>Upload your first file to get started</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Modified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user_files as $file): ?>
                        <tr>
                            <td>
                                <i class="fas <?php echo getFileIcon($file['type']); ?> me-2" style="color: #2563eb;"></i>
                                <strong><?php echo htmlspecialchars($file['name']); ?></strong>
                            </td>
                            <td>
                                <span class="badge bg-light" style="color: #333;">
                                    <?php echo strtoupper($file['type']); ?>
                                </span>
                            </td>
                            <td><?php echo formatFileSize($file['size']); ?></td>
                            <td>
                                <small style="color: #6b7280;">
                                    <?php echo date('M d, Y H:i', $file['modified']); ?>
                                </small>
                            </td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="filename" value="<?php echo htmlspecialchars($file['name']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Are you sure you want to delete this file?');">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Storage Info Card -->
<div class="card">
    <div class="card-header">
        <h2 style="margin: 0;">💾 Storage Information</h2>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; border-radius: 10px; margin-bottom: 1rem;">
                    <p style="margin: 0 0 0.5rem; opacity: 0.9;"><small>USED SPACE</small></p>
                    <h3 style="margin: 0.5rem 0;">
                        <?php 
                        $total_size = array_sum(array_map(function($f) { return $f['size']; }, $user_files));
                        echo formatFileSize($total_size);
                        ?>
                    </h3>
                </div>
            </div>
            <div class="col-md-6">
                <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 1.5rem; border-radius: 10px; margin-bottom: 1rem;">
                    <p style="margin: 0 0 0.5rem; opacity: 0.9;"><small>AVAILABLE SPACE</small></p>
                    <h3 style="margin: 0.5rem 0;">50 MB</h3>
                </div>
            </div>
        </div>
        
        <div style="background: #f8fafc; padding: 1rem; border-radius: 10px; margin-top: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="flex: 1;">
                    <div style="background: #e5e7eb; border-radius: 10px; height: 10px; overflow: hidden;">
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100%; width: <?php echo min(($total_size / (50 * 1024 * 1024)) * 100, 100); ?>%;"></div>
                    </div>
                </div>
                <span style="font-weight: 600;">
                    <?php echo round(($total_size / (50 * 1024 * 1024)) * 100, 1); ?>%
                </span>
            </div>
        </div>
    </div>
</div>

<script>
// Drag and drop functionality
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');
const uploadBtn = document.getElementById('uploadBtn');

dropZone.addEventListener('click', () => fileInput.click());

function handleDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    dropZone.style.borderColor = '#2563eb';
    dropZone.style.backgroundColor = '#eff6ff';
}

function handleDragLeave(e) {
    e.preventDefault();
    e.stopPropagation();
    dropZone.style.borderColor = '#cbd5e1';
    dropZone.style.backgroundColor = '#f8fafc';
}

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    dropZone.style.borderColor = '#cbd5e1';
    dropZone.style.backgroundColor = '#f8fafc';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        handleFileSelect({ target: { files } });
    }
}

function handleFileSelect(e) {
    const files = e.target.files;
    if (files.length > 0) {
        uploadBtn.disabled = false;
        dropZone.innerHTML = `
            <i class="fas fa-check-circle" style="font-size: 2.5rem; color: #10b981; margin-bottom: 1rem; display: block;"></i>
            <p style="margin: 0.5rem 0;"><strong>File selected: ${files[0].name}</strong></p>
            <p style="margin: 0; font-size: 0.85rem; color: #6b7280;">Click "Upload File" button to upload</p>
        `;
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
