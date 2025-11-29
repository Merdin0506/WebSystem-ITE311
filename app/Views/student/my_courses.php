<?php $this->extend('student/layout'); ?>

<?php $this->section('title'); ?>My Courses<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book-reader"></i> My Courses</h2>
    <div>
        <a href="<?= base_url('courses') ?>" class="btn btn-primary me-2">
            <i class="fas fa-search"></i> Full Search Page
        </a>
        <a href="<?= base_url('student/available-courses') ?>" class="btn btn-outline-primary">
            <i class="fas fa-plus-circle"></i> Browse More Courses
        </a>
    </div>
</div>

<?php if (!empty($enrollments)): ?>
    <!-- Search Interface for My Courses -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="courseSearchInput" 
                               placeholder="Search your enrolled courses by title or description...">
                        <button class="btn btn-outline-secondary" type="button" id="clearCourseSearch">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                    <small class="text-muted">
                        <span id="courseResultsCount"><?= count($enrollments) ?> enrolled course(s)</span>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="coursesContainer">
        <?php foreach ($enrollments as $enrollment): ?>
            <div class="col-md-6 col-lg-4 mb-4 course-item" 
                 data-title="<?= esc(strtolower($enrollment['title'] ?? '')) ?>" 
                 data-description="<?= esc(strtolower($enrollment['description'] ?? '')) ?>">
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
    
    <!-- No results message (hidden by default) -->
    <div id="noCoursesMessage" class="text-center py-5" style="display: none;">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h5>No courses found</h5>
        <p class="text-muted">No enrolled courses match your search criteria.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('courseSearchInput');
            const clearButton = document.getElementById('clearCourseSearch');
            const courseItems = document.querySelectorAll('.course-item');
            const resultsCount = document.getElementById('courseResultsCount');
            const noCoursesMessage = document.getElementById('noCoursesMessage');
            const coursesContainer = document.getElementById('coursesContainer');
            
            function updateResultsCount(count, searchTerm = '') {
                if (searchTerm) {
                    resultsCount.textContent = `${count} course(s) found for "${searchTerm}"`;
                } else {
                    resultsCount.textContent = `${count} enrolled course(s)`;
                }
            }
            
            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;
                
                if (searchTerm === '') {
                    courseItems.forEach(item => {
                        item.style.display = '';
                        visibleCount++;
                    });
                    noCoursesMessage.style.display = 'none';
                    coursesContainer.style.display = '';
                    updateResultsCount(visibleCount);
                } else {
                    courseItems.forEach(item => {
                        const title = item.getAttribute('data-title');
                        const description = item.getAttribute('data-description');
                        
                        if (title.includes(searchTerm) || description.includes(searchTerm)) {
                            item.style.display = '';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    
                    if (visibleCount === 0) {
                        noCoursesMessage.style.display = '';
                        coursesContainer.style.display = 'none';
                    } else {
                        noCoursesMessage.style.display = 'none';
                        coursesContainer.style.display = '';
                    }
                    
                    updateResultsCount(visibleCount, searchInput.value);
                }
            }
            
            searchInput.addEventListener('input', performSearch);
            
            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                performSearch();
            });
        });
    </script>
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