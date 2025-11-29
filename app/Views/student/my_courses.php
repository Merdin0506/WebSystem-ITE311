<?php $this->extend('student/layout'); ?>

<?php $this->section('title'); ?>My Courses<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book-reader"></i> My Courses</h2>
</div>

<?php if (!empty($enrollments)): ?>
    <div class="row">
        <?php foreach ($enrollments as $enrollment): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($enrollment['title'] ?? 'Untitled Course') ?></h5>
                        <p class="card-text text-muted"><?= esc(substr($enrollment['description'] ?? 'No description available', 0, 100)) ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="card-text mb-0"><small class="text-muted">Enrolled on: <?= date('M d, Y', strtotime($enrollment['enrollment_date'] ?? 'now')) ?></small></p>
                            <span class="badge bg-info">
                                <i class="fas fa-file-alt"></i> 
                                <?= $enrollment['materials_count'] ?? 0 ?> Materials
                            </span>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary"><i class="fas fa-check-circle"></i> Enrolled</span>
                        <a href="<?= base_url('student/course/' . $enrollment['course_id'] . '/materials') ?>" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-file-alt"></i> View Materials
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-book-reader fa-4x text-muted mb-3"></i>
        <h4>No Enrolled Courses</h4>
        <p class="text-muted">You haven't enrolled in any courses yet.</p>
        <a href="<?= base_url('student/available-courses') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Browse Available Courses
        </a>
    </div>
<?php endif; ?>
<?php $this->endSection(); ?>