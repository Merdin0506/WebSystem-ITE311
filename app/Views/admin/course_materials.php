<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($course['title']) ?> - Course Materials</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
                        <?= esc($course['title']) ?> - Materials
                    </h5>
                    <div>
                        <a href="<?= site_url('admin/course/' . $course['id'] . '/upload') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-upload me-1"></i>Upload Material
                        </a>
                        <a href="<?= site_url('admin/materials') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to All Materials
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Success/Error Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Course Info -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted">Course Information</h6>
                                            <p class="mb-1"><strong>Course:</strong> <?= esc($course['title']) ?></p>
                                            <p class="mb-0"><strong>Code:</strong> COURSE-<?= str_pad($course['id'], 3, '0', STR_PAD_LEFT) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted">Statistics</h6>
                                            <p class="mb-1"><strong>Total Materials:</strong> <?= count($materials) ?></p>
                                            <p class="mb-0"><strong>Last Updated:</strong> 
                                                <?= !empty($materials) ? date('M d, Y', strtotime($materials[0]['created_at'])) : 'Never' ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Materials Table -->
                    <?php if (empty($materials)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Materials Uploaded</h5>
                            <p class="text-muted">Start by uploading your first course material.</p>
                            <a href="/admin/course/<?= $course['id'] ?>/upload" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Upload First Material
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-file me-1"></i>File Name</th>
                                        <th><i class="fas fa-info-circle me-1"></i>File Type</th>
                                        <th><i class="fas fa-calendar me-1"></i>Upload Date</th>
                                        <th><i class="fas fa-hdd me-1"></i>File Size</th>
                                        <th><i class="fas fa-cogs me-1"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($materials as $material): ?>
                                        <tr id="material-<?= $material['id'] ?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php
                                                    $ext = pathinfo($material['file_name'], PATHINFO_EXTENSION);
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
                                                    }
                                                    ?>
                                                    <i class="fas <?= $icon ?> <?= $iconColor ?> me-2 fa-lg"></i>
                                                    <span><?= esc($material['file_name']) ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark"><?= strtoupper($ext) ?></span>
                                            </td>
                                            <td>
                                                <?= date('M d, Y g:i A', strtotime($material['created_at'])) ?>
                                            </td>
                                            <td>
                                                <?php
                                                $filePath = FCPATH . $material['file_path'];
                                                $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                                if ($fileSize > 1048576) {
                                                    echo number_format($fileSize / 1048576, 2) . ' MB';
                                                } elseif ($fileSize > 1024) {
                                                    echo number_format($fileSize / 1024, 2) . ' KB';
                                                } else {
                                                    echo $fileSize . ' bytes';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="/materials/download/<?= $material['id'] ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            onclick="deleteMaterial(<?= $material['id'] ?>)"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this material?</p>
                <p class="text-muted mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash me-1"></i>Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let materialToDelete = null;

function deleteMaterial(materialId) {
    materialToDelete = materialId;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (materialToDelete) {
        const btn = this;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Deleting...';
        btn.disabled = true;

        fetch(`<?= site_url('materials/delete/') ?>${materialToDelete}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove row from table
                document.getElementById(`material-${materialToDelete}`).remove();
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                
                // Show success message
                showAlert('success', data.message);
                
                // Check if table is empty
                const tbody = document.querySelector('tbody');
                if (tbody.children.length === 0) {
                    location.reload(); // Reload to show "no materials" message
                }
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            showAlert('danger', 'An error occurred while deleting the material.');
        })
        .finally(() => {
            btn.innerHTML = '<i class="fas fa-trash me-1"></i>Delete';
            btn.disabled = false;
            materialToDelete = null;
        });
    }
});

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.card-body');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            bootstrap.Alert.getInstance(alert).close();
        }
    }, 5000);
}
</script>

    </div> <!-- End container -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>