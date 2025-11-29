<?php if (empty($courses)): ?>
  <div class="alert alert-info" id="noCoursesMessage">
    <i class="fas fa-info-circle"></i> No courses found matching your search criteria.
  </div>
<?php else: ?>
  <div class="row g-4" id="coursesList">
    <?php foreach ($courses as $course): ?>
      <div class="col-md-6 col-lg-4 course-item" 
           data-title="<?= esc(strtolower($course['title'])) ?>" 
           data-description="<?= esc(strtolower($course['description'] ?? '')) ?>" 
           data-instructor="<?= esc(strtolower($course['instructor_name'] ?? '')) ?>">
        <div class="card h-100 shadow-sm">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title course-title"><?= esc($course['title']) ?></h5>
            <p class="card-text flex-grow-1 course-description"><?= esc($course['description'] ?? '') ?></p>
            <?php if (!empty($course['instructor_name'])): ?>
              <p class="card-text"><small class="text-muted course-instructor">
                <i class="fas fa-user"></i> <?= esc($course['instructor_name']) ?>
              </small></p>
            <?php endif; ?>
            <?php $isEnrolled = in_array($course['id'], $enrolledCourseIds ?? [], true); ?>
            <div class="mt-2">
              <?php if ($isEnrolled): ?>
                <span class="badge bg-success"><i class="fas fa-check"></i> Enrolled</span>
              <?php else: ?>
                <button class="btn btn-primary enroll-btn" data-course-id="<?= (int)$course['id'] ?>">
                  <i class="fas fa-user-plus"></i> Enroll
                </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>