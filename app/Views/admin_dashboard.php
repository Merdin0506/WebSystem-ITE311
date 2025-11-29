<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title>Admin Dashboard - Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        
        .text-xs {
            font-size: .7rem;
        }
        
        .text-gray-300 {
            color: #dddfeb !important;
        }
        
        .quick-action-btn {
            transition: transform 0.2s;
            height: 100px;
        }
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
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
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="fas fa-upload"></i> Upload Materials</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('admin/browse-uploads') ?>"><i class="fas fa-folder-open"></i> Browse Uploads</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('announcements') ?>"><i class="fas fa-megaphone"></i> Announcements</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('security-test/unauthorized') ?>"><i class="fas fa-shield-alt"></i> Security Tests</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= site_url('auth/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Welcome Section -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <h2><i class="fas fa-user-shield"></i> Admin Control Panel</h2>
                        <p class="mb-0">Welcome, <?= session()->get('name') ?>! Manage the entire student portal from here.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalUsers ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Courses</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalCourses ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Enrollments</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalEnrollments ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-left-warning shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Announcements</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalAnnouncements ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-megaphone fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Recent Enrollments -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-clock"></i> Recent Enrollments
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentEnrollments)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Course</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentEnrollments as $enrollment): ?>
                                            <tr>
                                                <td><?= esc($enrollment['student_name'] ?? 'N/A') ?></td>
                                                <td><?= esc($enrollment['course_name'] ?? 'N/A') ?></td>
                                                <td><?= date('M j, Y', strtotime($enrollment['created_at'] ?? '')) ?></td>
                                                <td><span class="badge bg-success">Active</span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">No recent enrollments</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Materials -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-file me-1"></i> Recent Materials
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentMaterials)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>File Name</th>
                                            <th>Course</th>
                                            <th>Upload Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentMaterials as $material): ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    $ext = pathinfo($material['file_name'], PATHINFO_EXTENSION);
                                                    $icon = 'fa-file';
                                                    $color = 'text-secondary';
                                                    
                                                    switch (strtolower($ext)) {
                                                        case 'pdf': $icon = 'fa-file-pdf'; $color = 'text-danger'; break;
                                                        case 'doc':
                                                        case 'docx': $icon = 'fa-file-word'; $color = 'text-primary'; break;
                                                        case 'ppt':
                                                        case 'pptx': $icon = 'fa-file-powerpoint'; $color = 'text-warning'; break;
                                                        case 'jpg':
                                                        case 'jpeg':
                                                        case 'png': $icon = 'fa-file-image'; $color = 'text-success'; break;
                                                    }
                                                    ?>
                                                    <i class="fas <?= $icon ?> <?= $color ?> me-2"></i>
                                                    <?= esc($material['file_name']) ?>
                                                </td>
                                                <td><?= esc($material['course_name'] ?? 'N/A') ?></td>
                                                <td><?= date('M j, Y g:i A', strtotime($material['created_at'] ?? '')) ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="/ITE311-harid/public/materials/download/<?= $material['id'] ?>" 
                                                           class="btn btn-sm btn-outline-primary" title="Download">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <a href="/ITE311-harid/public/admin/course/<?= $material['course_id'] ?>/materials" 
                                                           class="btn btn-sm btn-outline-info" title="View Course Materials">
                                                            <i class="fas fa-folder"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Materials Uploaded Yet</h5>
                                <p class="text-muted">Upload your first course material to see it here.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    <i class="fas fa-upload me-2"></i>Upload First Material
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Materials Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">
                        <i class="fas fa-upload me-2"></i>Upload Course Material
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="quickUploadForm" action="/ITE311-harid/public/materials/processQuickUpload" method="post" enctype="multipart/form-data" target="_blank">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="courseSelect" class="form-label">
                                        <i class="fas fa-book me-1"></i>Select Course
                                    </label>
                                    <select class="form-select" id="courseSelect" name="course_id" required>
                                        <option value="">-- Choose Course --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="materialFile" class="form-label">
                                        <i class="fas fa-file me-1"></i>Select File
                                    </label>
                                    <input type="file" class="form-control" id="materialFile" name="material" 
                                           accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.jpg,.jpeg,.png" required>
                                    <div class="form-text">Max: 10MB | PDF, DOC, PPT, Images</div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="uploadProgress" class="mb-3" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>

                        <div id="uploadResult" class="mb-3"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="uploadBtn" onclick="submitUploadForm()">
                        <i class="fas fa-upload me-1"></i>Upload Material
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Materials Manager Modal -->
    <div class="modal fade" id="materialsModal" tabindex="-1" aria-labelledby="materialsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="materialsModalLabel">
                        <i class="fas fa-folder-open me-2"></i>All Course Materials
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="materialsContent">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p>Loading materials...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Course data from PHP
    const courses = <?= json_encode($courses ?? []) ?>;
    
    // Load courses when upload modal opens
    document.getElementById('uploadModal').addEventListener('shown.bs.modal', function () {
        loadCourses();
    });

    function loadCourses() {
        console.log('Loading courses...');
        const select = document.getElementById('courseSelect');
        select.innerHTML = '<option value="">-- Choose Course --</option>';
        
        if (courses && courses.length > 0) {
            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id;
                option.textContent = `${course.title} (COURSE-${String(course.id).padStart(3, '0')})`;
                select.appendChild(option);
            });
            console.log('Loaded', courses.length, 'courses');
        } else {
            showUploadResult('warning', 'No courses found. Please create courses first.');
        }
    }

    function submitUploadForm() {
        const courseId = document.getElementById('courseSelect').value;
        const file = document.getElementById('materialFile').files[0];
        
        if (!courseId) {
            showUploadResult('warning', 'Please select a course');
            return;
        }

        if (!file) {
            showUploadResult('warning', 'Please select a file');
            return;
        }

        // Add course ID as hidden input
        const form = document.getElementById('quickUploadForm');
        let courseInput = form.querySelector('input[name="course_id"]');
        if (!courseInput) {
            courseInput = document.createElement('input');
            courseInput.type = 'hidden';
            courseInput.name = 'course_id';
            form.appendChild(courseInput);
        }
        courseInput.value = courseId;

        // Show loading
        const btn = document.getElementById('uploadBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Uploading...';

        // Submit the form
        form.submit();

        // Show success message and close modal after a delay
        setTimeout(() => {
            showUploadResult('success', 'Upload started! Check the new tab for results.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-upload me-1"></i>Upload Material';
            bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
        }, 1000);
    }

    function showUploadResult(type, message) {
        const resultDiv = document.getElementById('uploadResult');
        resultDiv.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }

    function showMaterialsManager() {
        const modal = new bootstrap.Modal(document.getElementById('materialsModal'));
        modal.show();
        
        // Load all materials
        loadAllMaterials();
    }

    function loadAllMaterials() {
        fetch('/ITE311-harid/public/admin/apiMaterials')
            .then(response => response.json())
            .then(data => {
                const content = document.getElementById('materialsContent');
                
                if (data.success && data.materials && data.materials.length > 0) {
                    let html = `
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Course</th>
                                        <th>File Name</th>
                                        <th>Upload Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    
                    data.materials.forEach(material => {
                        const ext = material.file_name.split('.').pop().toLowerCase();
                        let icon = 'fa-file';
                        let color = 'text-secondary';
                        
                        switch(ext) {
                            case 'pdf': icon = 'fa-file-pdf'; color = 'text-danger'; break;
                            case 'doc':
                            case 'docx': icon = 'fa-file-word'; color = 'text-primary'; break;
                            case 'ppt':
                            case 'pptx': icon = 'fa-file-powerpoint'; color = 'text-warning'; break;
                            case 'jpg':
                            case 'jpeg':
                            case 'png': icon = 'fa-file-image'; color = 'text-success'; break;
                        }
                        
                        html += `
                            <tr>
                                <td>
                                    <strong>${material.course_name}</strong><br>
                                    <small class="text-muted">${material.course_code}</small>
                                </td>
                                <td>
                                    <i class="fas ${icon} ${color} me-2"></i>
                                    ${material.file_name}
                                </td>
                                <td>${new Date(material.created_at).toLocaleDateString()}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/materials/download/${material.id}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a href="/admin/course/${material.course_id}/materials" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-folder"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteMaterial(${material.id})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    
                    html += '</tbody></table></div>';
                    content.innerHTML = html;
                } else {
                    content.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Materials Found</h5>
                            <p class="text-muted">Upload your first course material to get started.</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading materials:', error);
                document.getElementById('materialsContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error loading materials. Please try again.
                    </div>
                `;
            });
    }

    function deleteMaterial(materialId) {
        if (confirm('Are you sure you want to delete this material?')) {
            fetch(`/ITE311-harid/public/materials/delete/${materialId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadAllMaterials(); // Reload the materials list
                } else {
                    alert('Error deleting material: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('Error deleting material');
            });
        }
    }
    </script>
</body>
</html>
