<?php $this->extend('student/layout'); ?>

<?php $this->section('title'); ?>Available Courses<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book-open"></i> Available Courses</h2>
    <div>
        <a href="<?= base_url('courses') ?>" class="btn btn-primary me-2">
            <i class="fas fa-search"></i> Full Search Page
        </a>
        <a href="<?= base_url('student/my-courses') ?>" class="btn btn-outline-success">
            <i class="fas fa-book-reader"></i> My Enrolled Courses
        </a>
    </div>
</div>

<div id="alerts"></div>

<?php if (!empty($availableCourses)): ?>
    <!-- Search Interface for Available Courses -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="courseSearchInput" 
                               placeholder="Search available courses by title or description...">
                        <button class="btn btn-outline-secondary" type="button" id="clearCourseSearch">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                    <small class="text-muted">
                        <span id="courseResultsCount"><?= count($availableCourses) ?> available course(s)</span>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="coursesContainer">
        <?php foreach ($availableCourses as $course): ?>
            <div class="col-md-6 col-lg-4 mb-4 course-item" 
                 data-title="<?= esc(strtolower($course['title'] ?? '')) ?>" 
                 data-description="<?= esc(strtolower($course['description'] ?? '')) ?>">
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
    
    <!-- No results message (hidden by default) -->
    <div id="noCoursesMessage" class="text-center py-5" style="display: none;">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h5>No courses found</h5>
        <p class="text-muted">No available courses match your search criteria.</p>
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
    // Search functionality
    const searchInput = $('#courseSearchInput');
    const clearButton = $('#clearCourseSearch');
    const courseItems = $('.course-item');
    const resultsCount = $('#courseResultsCount');
    const noCoursesMessage = $('#noCoursesMessage');
    const coursesContainer = $('#coursesContainer');
    
    function updateResultsCount(count, searchTerm = '') {
        if (searchTerm) {
            resultsCount.text(`${count} course(s) found for "${searchTerm}"`);
        } else {
            resultsCount.text(`${count} available course(s)`);
        }
    }
    
    function performSearch() {
        const searchTerm = searchInput.val().toLowerCase().trim();
        let visibleCount = 0;
        
        if (searchTerm === '') {
            courseItems.show();
            visibleCount = courseItems.length;
            noCoursesMessage.hide();
            coursesContainer.show();
            updateResultsCount(visibleCount);
        } else {
            courseItems.each(function() {
                const $item = $(this);
                const title = $item.attr('data-title');
                const description = $item.attr('data-description');
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    $item.show();
                    visibleCount++;
                } else {
                    $item.hide();
                }
            });
            
            if (visibleCount === 0) {
                noCoursesMessage.show();
                coursesContainer.hide();
            } else {
                noCoursesMessage.hide();
                coursesContainer.show();
            }
            
            updateResultsCount(visibleCount, searchInput.val());
        }
    }
    
    searchInput.on('input', performSearch);
    clearButton.on('click', function() {
        searchInput.val('');
        performSearch();
    });

    // Enrollment functionality
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
                // Update search results count after enrollment
                setTimeout(() => {
                    performSearch();
                }, 500);
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