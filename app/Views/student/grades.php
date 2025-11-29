<?php $this->extend('student/layout'); ?>

<?php $this->section('title'); ?>Grades<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-line"></i> Grades</h2>
</div>

<?php if (!empty($grades)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Academic Progress</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Assignment</th>
                                    <th>Grade</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($grades as $grade): ?>
                                    <tr>
                                        <td><?= esc($grade['course_name']) ?></td>
                                        <td><?= esc($grade['assignment_name']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $grade['grade'] >= 75 ? 'primary' : ($grade['grade'] >= 60 ? 'info' : 'secondary') ?>">
                                                <?= esc($grade['grade']) ?>%
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($grade['graded_date'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $grade['grade'] >= 75 ? 'primary' : ($grade['grade'] >= 60 ? 'info' : 'secondary') ?>">
                                                <?= $grade['grade'] >= 75 ? 'Passed' : ($grade['grade'] >= 60 ? 'Needs Improvement' : 'Failed') ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Summary -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h3 class="text-primary">85.6%</h3>
                    <p class="mb-0">Overall Average</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h3 class="text-primary"><?= count($grades) ?></h3>
                    <p class="mb-0">Total Assignments</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h3 class="text-primary">B+</h3>
                    <p class="mb-0">Current Grade</p>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
        <h4>No Grades Available</h4>
        <p class="text-muted">Your grades will appear here once assignments are graded.</p>
        <a href="<?= base_url('student/assignments') ?>" class="btn btn-primary">
            <i class="fas fa-tasks"></i> View Assignments
        </a>
    </div>
<?php endif; ?>
<?php $this->endSection(); ?>