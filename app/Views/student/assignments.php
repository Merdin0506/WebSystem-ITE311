<?php $this->extend('student/layout'); ?>

<?php $this->section('title'); ?>Assignments<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tasks"></i> Assignments</h2>
</div>

<?php if (!empty($assignments)): ?>
    <div class="row">
        <?php foreach ($assignments as $assignment): ?>
            <div class="col-12 mb-3">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title"><?= esc($assignment['title']) ?></h5>
                                <p class="card-text"><?= esc($assignment['description']) ?></p>
                                <small class="text-muted">Course: <?= esc($assignment['course_name']) ?></small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-<?= $assignment['status'] === 'submitted' ? 'success' : ($assignment['status'] === 'overdue' ? 'danger' : 'warning') ?>">
                                    <?= ucfirst(esc($assignment['status'])) ?>
                                </span>
                                <br><small class="text-muted">Due: <?= date('M d, Y', strtotime($assignment['due_date'])) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-tasks fa-4x text-muted mb-3"></i>
        <h4>No Assignments</h4>
        <p class="text-muted">You don't have any assignments at the moment.</p>
        <a href="<?= base_url('student/my-courses') ?>" class="btn btn-primary">
            <i class="fas fa-book-reader"></i> View My Courses
        </a>
    </div>
<?php endif; ?>
<?php $this->endSection(); ?>