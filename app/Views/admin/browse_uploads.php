<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Uploaded Files - Admin Dashboard</title>
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
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-folder-open me-2"></i>
                        Browse Uploaded Files
                    </h5>
                    <div>
                        <button class="btn btn-success btn-sm" onclick="location.reload()">
                            <i class="fas fa-refresh me-1"></i>Refresh
                        </button>
                        <a href="/ITE311-harid/admin/dashboard" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Upload Path Info -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Upload Directory:</strong> <?= esc($uploadPath) ?>
                    </div>

                    <?php if (empty($files)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Files Found</h5>
                            <p class="text-muted">The upload directory is empty. Upload some materials to see them here.</p>
                            <a href="/ITE311-harid/admin/dashboard" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Go Upload Materials
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Files Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-file me-1"></i>File Name</th>
                                        <th><i class="fas fa-hdd me-1"></i>Size</th>
                                        <th><i class="fas fa-calendar me-1"></i>Upload Date</th>
                                        <th><i class="fas fa-cogs me-1"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($files as $file): ?>
                                        <tr>
                                            <td>
                                                <?php
                                                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                                                $icon = 'fa-file';
                                                $iconColor = 'text-secondary';
                                                
                                                switch (strtolower($ext)) {
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
                                                <span><?= esc($file['name']) ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                if ($file['size'] > 1048576) {
                                                    echo number_format($file['size'] / 1048576, 2) . ' MB';
                                                } elseif ($file['size'] > 1024) {
                                                    echo number_format($file['size'] / 1024, 2) . ' KB';
                                                } else {
                                                    echo $file['size'] . ' bytes';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?= date('M d, Y g:i A', strtotime($file['date'])) ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="/ITE311-harid/public/<?= $file['path'] ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       target="_blank" 
                                                       title="View/Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-info" 
                                                            onclick="copyToClipboard('/ITE311-harid/public/<?= $file['path'] ?>')" 
                                                            title="Copy URL">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-success" 
                                                            onclick="showFileInfo('<?= esc($file['name']) ?>', '<?= $file['size'] ?>', '<?= $file['date'] ?>')" 
                                                            title="File Info">
                                                        <i class="fas fa-info"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary -->
                        <div class="card bg-light mt-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card stats-card primary shadow">
                                            <div class="card-body text-center">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <h5 class="text-primary mb-0"><?= count($files) ?></h5>
                                                        <small class="text-muted">Total Files</small>
                                                    </div>
                                                    <i class="fas fa-file fa-2x text-primary opacity-50"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card stats-card success shadow">
                                            <div class="card-body text-center">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <h5 class="text-success mb-0">
                                                            <?php
                                                            $totalSize = array_sum(array_column($files, 'size'));
                                                            echo $totalSize > 1048576 ? 
                                                                number_format($totalSize / 1048576, 2) . ' MB' : 
                                                                number_format($totalSize / 1024, 2) . ' KB';
                                                            ?>
                                                        </h5>
                                                        <small class="text-muted">Total Size</small>
                                                    </div>
                                                    <i class="fas fa-hdd fa-2x text-success opacity-50"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card stats-card info shadow">
                                            <div class="card-body text-center">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <h5 class="text-info mb-0">
                                                            <?php
                                                            $fileTypes = [];
                                                            foreach ($files as $file) {
                                                                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                                                $fileTypes[$ext] = true;
                                                            }
                                                            echo count($fileTypes);
                                                            ?>
                                                        </h5>
                                                        <small class="text-muted">File Types</small>
                                                    </div>
                                                    <i class="fas fa-tags fa-2x text-info opacity-50"></i>
                                                </div>
                                            </div>
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

<!-- File Info Modal -->
<div class="modal fade" id="fileInfoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>File Information
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="fileInfoContent">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(url) {
    const fullUrl = window.location.origin + url;
    navigator.clipboard.writeText(fullUrl).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'alert alert-success alert-dismissible fade show position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; width: 300px;';
        toast.innerHTML = `
            <i class="fas fa-check me-2"></i>URL copied to clipboard!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 3000);
    }).catch(function() {
        alert('Failed to copy URL. Please copy manually: ' + fullUrl);
    });
}

function showFileInfo(fileName, fileSize, fileDate) {
    const sizeFormatted = fileSize > 1048576 ? 
        (fileSize / 1048576).toFixed(2) + ' MB' : 
        (fileSize / 1024).toFixed(2) + ' KB';
    
    const content = `
        <div class="row">
            <div class="col-4"><strong>File Name:</strong></div>
            <div class="col-8">${fileName}</div>
        </div>
        <hr>
        <div class="row">
            <div class="col-4"><strong>File Size:</strong></div>
            <div class="col-8">${sizeFormatted}</div>
        </div>
        <hr>
        <div class="row">
            <div class="col-4"><strong>Upload Date:</strong></div>
            <div class="col-8">${new Date(fileDate).toLocaleString()}</div>
        </div>
        <hr>
        <div class="row">
            <div class="col-4"><strong>File Type:</strong></div>
            <div class="col-8">${fileName.split('.').pop().toUpperCase()}</div>
        </div>
    `;
    
    document.getElementById('fileInfoContent').innerHTML = content;
    const modal = new bootstrap.Modal(document.getElementById('fileInfoModal'));
    modal.show();
}
</script>

    </div> <!-- End container -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>