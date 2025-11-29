<?php
/** @var array $courses */
/** @var array $enrolledCourseIds */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0"><i class="fas fa-book-open"></i> Courses</h1>
      <a href="<?php 
          $userRole = session()->get('role');
          if ($userRole === 'admin') {
              echo site_url('admin/dashboard');
          } elseif ($userRole === 'teacher') {
              echo site_url('teacher/dashboard');
          } else {
              echo site_url('student/dashboard');
          }
      ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Dashboard</a>
    </div>

    <!-- Search Interface -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
            <form id="searchForm" class="d-flex" method="POST" action="<?= site_url('courses/search') ?>">
              <?= csrf_field() ?>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" id="searchInput" name="search" 
                       placeholder="Search courses by title, description, or instructor..." 
                       value="<?= esc($searchTerm ?? '') ?>">
                <button class="btn btn-primary" type="submit" id="searchSubmit">
                  <i class="fas fa-search"></i> Search
                </button>
                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                  <i class="fas fa-times"></i> Clear
                </button>
              </div>
            </form>
          </div>
          <div class="col-md-4 text-md-end mt-2 mt-md-0">
            <small class="text-muted">
              <span id="resultsCount">
                <?php if (isset($searchTerm) && !empty($searchTerm)): ?>
                  <?= count($courses) ?> course(s) found for "<?= esc($searchTerm) ?>"
                <?php else: ?>
                  <?= count($courses) ?> total course(s)
                <?php endif; ?>
              </span>
            </small>
          </div>
        </div>
      </div>
    </div>

    <div id="flashArea"></div>

    <!-- Loading indicator -->
    <div id="loadingIndicator" class="text-center mb-3" style="display: none;">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Searching...</span>
      </div>
      <div class="mt-2">Searching courses...</div>
    </div>

    <!-- Courses Container -->
    <div id="coursesContainer"><?= $this->include('courses/course_list', ['courses' => $courses, 'enrolledCourseIds' => $enrolledCourseIds]) ?></div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      const csrfTokenName = '<?= esc(csrf_token()) ?>';
      let csrfHash = '<?= esc(csrf_hash()) ?>';

      function showFlash(message, type = 'success') {
        $('#flashArea').html(`
          <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>`);
      }

      function updateResultsCount(count, searchTerm = '') {
        if (searchTerm) {
          $('#resultsCount').text(`${count} course(s) found for "${searchTerm}"`);
        } else {
          $('#resultsCount').text(`${count} total course(s)`);
        }
      }

      // Client-side instant filtering
      $('#searchInput').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        const courseItems = $('.course-item');
        let visibleCount = 0;

        if (searchTerm === '') {
          courseItems.show();
          visibleCount = courseItems.length;
          updateResultsCount(visibleCount);
        } else {
          courseItems.each(function() {
            const title = $(this).data('title');
            const description = $(this).data('description');
            const instructor = $(this).data('instructor');
            
            if (title.includes(searchTerm) || 
                description.includes(searchTerm) || 
                instructor.includes(searchTerm)) {
              $(this).show();
              visibleCount++;
            } else {
              $(this).hide();
            }
          });
          updateResultsCount(visibleCount, $(this).val());
        }

        // Show/hide no results message
        if (visibleCount === 0) {
          if ($('#noCoursesMessage').length === 0) {
            $('#coursesContainer').append(`
              <div class="alert alert-info" id="noCoursesMessage">
                <i class="fas fa-info-circle"></i> No courses found matching your search criteria.
              </div>
            `);
          }
          $('#coursesList').hide();
        } else {
          $('#noCoursesMessage').remove();
          $('#coursesList').show();
        }
      });

      // Server-side AJAX search
      $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        
        const searchTerm = $('#searchInput').val();
        $('#loadingIndicator').show();
        $('#coursesContainer').hide();

        $.ajax({
          url: '<?= site_url('courses/search') ?>',
          method: 'POST',
          data: {
            search: searchTerm,
            [csrfTokenName]: csrfHash
          },
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              // Update CSRF hash for next request
              csrfHash = response.csrfHash || csrfHash;
              
              // Generate course HTML
              let coursesHtml = '';
              if (response.courses.length === 0) {
                coursesHtml = `
                  <div class="alert alert-info" id="noCoursesMessage">
                    <i class="fas fa-info-circle"></i> No courses found matching your search criteria.
                  </div>
                `;
              } else {
                coursesHtml = '<div class="row g-4" id="coursesList">';
                response.courses.forEach(function(course) {
                  const isEnrolled = response.enrolledCourseIds.includes(course.id);
                  const enrollButton = isEnrolled 
                    ? '<span class="badge bg-success"><i class="fas fa-check"></i> Enrolled</span>'
                    : `<button class="btn btn-primary enroll-btn" data-course-id="${course.id}"><i class="fas fa-user-plus"></i> Enroll</button>`;
                  
                  const instructor = course.instructor_name 
                    ? `<p class="card-text"><small class="text-muted course-instructor"><i class="fas fa-user"></i> ${course.instructor_name}</small></p>`
                    : '';

                  coursesHtml += `
                    <div class="col-md-6 col-lg-4 course-item" 
                         data-title="${course.title.toLowerCase()}" 
                         data-description="${(course.description || '').toLowerCase()}" 
                         data-instructor="${(course.instructor_name || '').toLowerCase()}">
                      <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                          <h5 class="card-title course-title">${course.title}</h5>
                          <p class="card-text flex-grow-1 course-description">${course.description || ''}</p>
                          ${instructor}
                          <div class="mt-2">${enrollButton}</div>
                        </div>
                      </div>
                    </div>
                  `;
                });
                coursesHtml += '</div>';
              }
              
              $('#coursesContainer').html(coursesHtml);
              updateResultsCount(response.totalResults, searchTerm);
              
              // Re-bind enrollment events
              bindEnrollmentEvents();
            } else {
              showFlash(response.message || 'Search failed. Please try again.', 'danger');
            }
          },
          error: function() {
            showFlash('Network error during search. Please try again.', 'danger');
          },
          complete: function() {
            $('#loadingIndicator').hide();
            $('#coursesContainer').show();
          }
        });
      });

      // Clear search
      $('#clearSearch').on('click', function() {
        $('#searchInput').val('').trigger('input');
        window.location.href = '<?= site_url('courses') ?>';
      });

      // Enrollment functionality
      function bindEnrollmentEvents() {
        $('.enroll-btn').off('click').on('click', function() {
          const $btn = $(this);
          const courseId = $btn.data('course-id');
          
          $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enrolling...');
          
          $.ajax({
            url: '<?= site_url('course/enroll') ?>',
            method: 'POST',
            data: {
              course_id: courseId,
              [csrfTokenName]: csrfHash
            },
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            success: function(response) {
              if (response.success) {
                showFlash(response.message || 'Enrolled successfully.');
                $btn.replaceWith('<span class="badge bg-success"><i class="fas fa-check"></i> Enrolled</span>');
              } else {
                showFlash(response.message || 'Failed to enroll.', 'danger');
                $btn.prop('disabled', false).html('<i class="fas fa-user-plus"></i> Enroll');
              }
            },
            error: function() {
              showFlash('Network error. Please try again.', 'danger');
              $btn.prop('disabled', false).html('<i class="fas fa-user-plus"></i> Enroll');
            }
          });
        });
      }

      // Initial binding
      bindEnrollmentEvents();

      // Optional: Auto-refresh notifications every 60 seconds
      setInterval(function() {
        // This could fetch new notifications or update course enrollment status
        console.log('Auto-refresh interval (optional feature)');
      }, 60000);
    });
  </script>
</body>
</html>
