<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Course Materials - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .btn-outline-purple {
            color: #6f42c1;
            border-color: #6f42c1;
        }
        .btn-outline-purple:hover {
            color: #fff;
            background-color: #6f42c1;
            border-color: #6f42c1;
        }
        .card-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .file-icon {
            min-width: 60px;
            text-align: center;
        }
        .card:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease-in-out;
        }
        .stats-card {
            border-left: 4px solid;
        }
        .stats-card.primary { border-left-color: #007bff; }
        .stats-card.success { border-left-color: #28a745; }
        .stats-card.warning { border-left-color: #ffc107; }
        .stats-card.info { border-left-color: #17a2b8; }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url('admin/dashboard') ?>">
                <i class="fas fa-user-shield"></i> Admin Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?= session()->get('name') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= site_url('admin/courses') ?>"><i class="fas fa-book"></i> Manage Courses</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('admin/enrollments') ?>"><i class="fas fa-users"></i> Manage Enrollments</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('admin/materials') ?>"><i class="fas fa-file-alt"></i> All Materials</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('admin/browse-uploads') ?>"><i class="fas fa-folder-open"></i> Browse Uploads</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('announcements') ?>"><i class="fas fa-megaphone"></i> Announcements</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= site_url('auth/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        All Course Materials
                    </h5>
                    <div>
                        <button class="btn btn-success btn-sm" onclick="location.reload()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                        <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>Admin Access:</strong> You can view and download materials from all courses without enrollment restrictions.
                    </div>

                    <?php if (empty($materials)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Materials Found</h5>
                            <p class="text-muted">No materials have been uploaded yet. Upload some course materials to see them here.</p>
                            <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Go Upload Materials
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Materials Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-file me-1"></i>File Name</th>
                                        <th><i class="fas fa-book me-1"></i>Course</th>
                                        <th><i class="fas fa-calendar me-1"></i>Upload Date</th>
                                        <th><i class="fas fa-cogs me-1"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($materials as $material): ?>
                                        <tr>
                                            <td>
                                                <?php
                                                $ext = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
                                                $icon = 'fa-file';
                                                $iconColor = 'text-secondary';
                                                
                                                switch ($ext) {
                                                    case 'pdf':
                                                        $icon = 'fa-file-pdf';
                                                        $iconColor = 'text-danger';
                                                        break;
                                                    case 'doc':
                                                    case 'docx':
                                                        $icon = 'fa-file-word';
                                                        $iconColor = 'text-primary';
                                                        break;
                                                    case 'ppt':
                                                    case 'pptx':
                                                        $icon = 'fa-file-powerpoint';
                                                        $iconColor = 'text-warning';
                                                        break;
                                                    case 'jpg':
                                                    case 'jpeg':
                                                    case 'png':
                                                        $icon = 'fa-file-image';
                                                        $iconColor = 'text-success';
                                                        break;
                                                    case 'txt':
                                                        $icon = 'fa-file-alt';
                                                        $iconColor = 'text-info';
                                                        break;
                                                }
                                                ?>
                                                <i class="fas <?= $icon ?> <?= $iconColor ?> me-2 fa-lg"></i>
                                                <span><?= esc($material['file_name']) ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary me-2"><?= esc($material['course_code']) ?></span>
                                                <strong><?= esc($material['course_name']) ?></strong>
                                            </td>
                                            <td>
                                                <?= date('M d, Y g:i A', strtotime($material['created_at'])) ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= site_url('materials/download/' . $material['id']) ?>" 
                                                       class="btn btn-sm btn-outline-success" 
                                                       title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <a href="<?= site_url('admin/course/' . $material['course_id'] . '/materials') ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="View Course Materials">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-info" 
                                                            onclick="copyToClipboard('<?= site_url('materials/download/' . $material['id']) ?>')" 
                                                            title="Copy Download Link">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" 
                                                            onclick="confirmDelete(<?= $material['id'] ?>, '<?= addslashes(esc($material['file_name'])) ?>')" 
                                                            title="Delete Material">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card stats-card primary shadow">
                                    <div class="card-body text-center">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h4 class="text-primary mb-0"><?= count($materials) ?></h4>
                                                <small class="text-muted">Total Materials</small>
                                            </div>
                                            <i class="fas fa-file-alt fa-2x text-primary opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stats-card success shadow">
                                    <div class="card-body text-center">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h4 class="text-success mb-0">
                                                    <?php
                                                    $courses = array_unique(array_column($materials, 'course_id'));
                                                    echo count($courses);
                                                    ?>
                                                </h4>
                                                <small class="text-muted">Courses with Materials</small>
                                            </div>
                                            <i class="fas fa-book fa-2x text-success opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stats-card info shadow">
                                    <div class="card-body text-center">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h4 class="text-info mb-0">
                                                    <?php
                                                    $fileTypes = [];
                                                    foreach ($materials as $material) {
                                                        $ext = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
                                                        $fileTypes[$ext] = true;
                                                    }
                                                    echo count($fileTypes);
                                                    ?>
                                                </h4>
                                                <small class="text-muted">File Types</small>
                                            </div>
                                            <i class="fas fa-tags fa-2x text-info opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stats-card warning shadow">
                                    <div class="card-body text-center">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h4 class="text-warning mb-0">
                                                    <?php
                                                    $recentMaterials = array_filter($materials, function($material) {
                                                        return strtotime($material['created_at']) > strtotime('-7 days');
                                                    });
                                                    echo count($recentMaterials);
                                                    ?>
                                                </h4>
                                                <small class="text-muted">This Week</small>
                                            </div>
                                            <i class="fas fa-calendar-week fa-2x text-warning opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteFileName"></strong>?</p>
                <p class="text-danger">This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="deleteConfirmBtn" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i>Delete
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(url) {
    // If URL is already complete, use it as is, otherwise build full URL
    const fullUrl = url.startsWith('http') ? url : window.location.origin + '/' + url.replace(/^\//, '');
    navigator.clipboard.writeText(fullUrl).then(function() {
        showToast('Download link copied to clipboard!', 'success');
    }).catch(function() {
        showToast('Failed to copy link', 'error');
    });
}

function confirmDelete(materialId, fileName) {
    document.getElementById('deleteFileName').textContent = fileName;
    document.getElementById('deleteConfirmBtn').href = '<?= site_url("materials/delete/") ?>' + materialId;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function showToast(message, type) {
    const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white ${bgClass} border-0 position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check' : 'times'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toast);
    });
}
</script>

    </div> <!-- End container -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>