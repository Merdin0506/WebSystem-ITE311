<?php $this->extend('student/layout'); ?>

<?php $this->section('title'); ?>Available Courses<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book-open"></i> Available Courses</h2>
</div>

<div id="alerts"></div>

<?php if (!empty($availableCourses)): ?>
    <div class="row">
        <?php foreach ($availableCourses as $course): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($course['title']) ?></h5>
                        <p class="card-text"><?= esc(substr($course['description'] ?? 'No description available', 0, 100)) ?>...</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary"><i class="fas fa-clock"></i> Available</span>
                        <button class="btn btn-primary btn-sm enroll-btn" data-course-id="<?= $course['id'] ?>">
                            <i class="fas fa-user-plus"></i> Enroll
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
        <h4>No Available Courses</h4>
        <p class="text-muted">All courses are currently enrolled or there are no courses available.</p>
        <a href="<?= base_url('student/my-courses') ?>" class="btn btn-primary">
            <i class="fas fa-book-reader"></i> View My Courses
        </a>
    </div>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    $('.enroll-btn').on('click', function() {
        const $btn = $(this);
        const courseId = $btn.data('course-id');
        
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Enrolling...');
        
        $.post('<?= base_url('course/enroll') ?>', {
            course_id: courseId,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        })
        .done(function(response) {
            if (response.success) {
                $('#alerts').html('<div class="alert alert-success alert-dismissible fade show" role="alert"><i class="fas fa-check-circle"></i> ' + response.message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                $btn.closest('.card').fadeOut();
            } else {
                $('#alerts').html('<div class="alert alert-warning alert-dismissible fade show" role="alert"><i class="fas fa-exclamation-triangle"></i> ' + response.message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                $btn.prop('disabled', false).html('<i class="fas fa-user-plus"></i> Enroll');
            }
        })
        .fail(function() {
            $('#alerts').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fas fa-times-circle"></i> Enrollment failed. Please try again.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            $btn.prop('disabled', false).html('<i class="fas fa-user-plus"></i> Enroll');
        });
    });
});
</script>
<?php $this->endSection(); ?>