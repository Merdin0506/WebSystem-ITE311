<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Material - <?= esc($course['title']) ?></title>
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
        .upload-area {
            border: 2px dashed #007bff;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        .upload-area:hover {
            background-color: #e9ecef;
            border-color: #0056b3;
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
                        <i class="fas fa-upload me-2"></i>
                        Upload Material - <?= esc($course['title']) ?>
                    </h5>
                    <a href="<?= site_url('admin/course/' . $course['id'] . '/materials') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Materials
                    </a>
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

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Validation Errors:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Course Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted">Course Information</h6>
                                    <p class="mb-1"><strong>Course:</strong> <?= esc($course['title']) ?></p>
                                    <p class="mb-1"><strong>ID:</strong> COURSE-<?= str_pad($course['id'], 3, '0', STR_PAD_LEFT) ?></p>
                                    <p class="mb-0"><strong>Description:</strong> <?= esc($course['description']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Form -->
                    <form action="/admin/course/<?= $course_id ?>/upload" method="post" enctype="multipart/form-data" id="uploadForm">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="material" class="form-label">
                                        <i class="fas fa-file me-1"></i>
                                        Select Material File
                                    </label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="material" 
                                           name="material" 
                                           required
                                           accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.jpg,.jpeg,.png">
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Allowed file types: PDF, DOC, DOCX, PPT, PPTX, TXT, JPG, JPEG, PNG. Maximum size: 10MB
                                    </div>
                                </div>

                                <!-- File Preview -->
                                <div id="filePreview" class="mb-3" style="display: none;">
                                    <div class="card border-primary">
                                        <div class="card-body">
                                            <h6 class="card-title text-primary">
                                                <i class="fas fa-eye me-1"></i>
                                                Selected File
                                            </h6>
                                            <div id="fileInfo"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                                        <i class="fas fa-upload me-2"></i>
                                        Upload Material
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                        <i class="fas fa-times me-2"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Upload Guidelines
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Use descriptive filenames
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Keep file size under 10MB
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Supported formats: PDF, DOC, PPT, images
                                            </li>
                                            <li class="mb-0">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Files will be available to enrolled students
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('material');
    const filePreview = document.getElementById('filePreview');
    const fileInfo = document.getElementById('fileInfo');
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadForm = document.getElementById('uploadForm');

    // File input change handler
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            displayFilePreview(file);
        } else {
            hideFilePreview();
        }
    });

    // Form submit handler
    uploadForm.addEventListener('submit', function(e) {
        const file = fileInput.files[0];
        if (!file) {
            e.preventDefault();
            alert('Please select a file to upload.');
            return;
        }

        // Show loading state
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
        uploadBtn.disabled = true;
    });

    function displayFilePreview(file) {
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const fileType = file.type || 'Unknown';
        
        let icon = 'fa-file';
        if (file.type.includes('pdf')) icon = 'fa-file-pdf';
        else if (file.type.includes('word') || file.name.includes('.doc')) icon = 'fa-file-word';
        else if (file.type.includes('powerpoint') || file.name.includes('.ppt')) icon = 'fa-file-powerpoint';
        else if (file.type.includes('image')) icon = 'fa-file-image';

        fileInfo.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas ${icon} fa-2x text-primary me-3"></i>
                <div>
                    <p class="mb-1"><strong>${file.name}</strong></p>
                    <p class="mb-1 text-muted">Size: ${fileSize} MB</p>
                    <p class="mb-0 text-muted">Type: ${fileType}</p>
                </div>
            </div>
        `;
        filePreview.style.display = 'block';
    }

    function hideFilePreview() {
        filePreview.style.display = 'none';
    }

    // Reset form function
    window.resetForm = function() {
        uploadForm.reset();
        hideFilePreview();
        uploadBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Material';
        uploadBtn.disabled = false;
    }
});
</script>

    </div> <!-- End container -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>