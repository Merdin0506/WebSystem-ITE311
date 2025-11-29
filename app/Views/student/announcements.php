<?php $this->extend('student/layout'); ?>

<?php $this->section('title'); ?>Announcements<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h2><i class="fas fa-bullhorn"></i> Announcements</h2>
                <p class="mb-0">Stay updated with important announcements and course updates.</p>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($announcements)): ?>
    <div class="row">
        <?php foreach ($announcements as $announcement): ?>
            <div class="col-12 mb-4">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?= esc($announcement['title']) ?></h5>
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt"></i> 
                            <?= date('M d, Y H:i', strtotime($announcement['created_at'])) ?>
                        </small>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?= nl2br(esc($announcement['content'])) ?></p>
                        <?php if (!empty($announcement['author'])): ?>
                            <div class="mt-3 pt-3 border-top">
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> Posted by: <?= esc($announcement['author']) ?>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-bullhorn fa-4x text-muted mb-3"></i>
        <h4>No Announcements</h4>
        <p class="text-muted">There are no announcements at the moment. Check back later for updates!</p>
        <a href="<?= base_url('student/dashboard') ?>" class="btn btn-primary">
            <i class="fas fa-tachometer-alt"></i> Back to Dashboard
        </a>
    </div>
<?php endif; ?>
<?php $this->endSection(); ?>