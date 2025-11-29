<?php $this->extend('student/layout'); ?>

<?php $this->section('title'); ?><?= esc($course['title']) ?> - Materials<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-file-alt"></i> Course Materials</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('student/my-courses') ?>">My Courses</a></li>
                <li class="breadcrumb-item active"><?= esc($course['title']) ?></li>
            </ol>
        </nav>
    </div>
    <a href="<?= base_url('student/my-courses') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to My Courses
    </a>
</div>

<!-- Course Information -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h5 class="card-title">
                    <span class="badge bg-primary me-2">COURSE-<?= str_pad($course['id'], 3, '0', STR_PAD_LEFT) ?></span>
                    <?= esc($course['title']) ?>
                </h5>
                <?php if (!empty($course['description'])): ?>
                    <p class="card-text"><?= esc($course['description']) ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-end">
                <?php if (!empty($course['instructor_name'])): ?>
                    <p class="mb-1"><strong>Instructor:</strong> <?= esc($course['instructor_name']) ?></p>
                <?php endif; ?>
                <p class="mb-0"><strong>Status:</strong> <span class="badge bg-success">Enrolled</span></p>
            </div>
        </div>
    </div>
</div>

<!-- Materials Section -->
<?php if (!empty($materials)): ?>
    <div class="row">
        <?php foreach ($materials as $material): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="file-icon me-3">
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
                                    case 'zip':
                                    case 'rar':
                                        $icon = 'fa-file-archive';
                                        $iconColor = 'text-dark';
                                        break;
                                }
                                ?>
                                <i class="fas <?= $icon ?> <?= $iconColor ?> fa-3x"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1"><?= esc($material['file_name']) ?></h6>
                                <p class="text-muted small mb-1">
                                    <i class="fas fa-calendar-alt"></i> 
                                    Uploaded: <?= date('M d, Y', strtotime($material['created_at'])) ?>
                                </p>
                                <span class="badge bg-light text-dark"><?= strtoupper($ext) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-grid">
                            <a href="<?= base_url('materials/download/' . $material['id']) ?>" 
                               class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Materials Summary -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <h4 class="text-primary"><?= count($materials) ?></h4>
                    <small class="text-muted">Total Materials</small>
                </div>
                <div class="col-md-3">
                    <h4 class="text-success">
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
                <div class="col-md-3">
                    <h4 class="text-info">
                        <?php
                        $recentMaterials = array_filter($materials, function($material) {
                            return strtotime($material['created_at']) > strtotime('-7 days');
                        });
                        echo count($recentMaterials);
                        ?>
                    </h4>
                    <small class="text-muted">This Week</small>
                </div>
                <div class="col-md-3">
                    <h4 class="text-warning">
                        <?php
                        $todayMaterials = array_filter($materials, function($material) {
                            return date('Y-m-d', strtotime($material['created_at'])) === date('Y-m-d');
                        });
                        echo count($todayMaterials);
                        ?>
                    </h4>
                    <small class="text-muted">Today</small>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
            <h4 class="text-muted">No Materials Available</h4>
            <p class="text-muted mb-4">
                No materials have been uploaded for this course yet. 
                Check back later or contact your instructor.
            </p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Course:</strong> <?= esc($course['title']) ?><br>
                        <strong>Status:</strong> You are enrolled and can access materials when they become available.
                    </div>
                </div>
            </div>
            <a href="<?= base_url('student/my-courses') ?>" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to My Courses
            </a>
        </div>
    </div>
<?php endif; ?>

<style>
.file-icon {
    min-width: 60px;
    text-align: center;
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: #6c757d;
}
</style>
<?php $this->endSection(); ?>