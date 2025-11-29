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

    <div id="flashArea"></div>

    <?php if (empty($courses)): ?>
      <div class="alert alert-info">No courses available.</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($courses as $course): ?>
          <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= esc($course['title']) ?></h5>
                <p class="card-text flex-grow-1"><?= esc($course['description'] ?? '') ?></p>
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
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const csrfTokenName = '<?= esc(csrf_token()) ?>';
      const csrfHash = '<?= esc(csrf_hash()) ?>';

      function showFlash(message, type = 'success') {
        const area = document.getElementById('flashArea');
        area.innerHTML = `
          <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>`;
      }

      document.querySelectorAll('.enroll-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
          const courseId = btn.getAttribute('data-course-id');
          btn.disabled = true;
          btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enrolling...';
          try {
            const formData = new FormData();
            formData.append('course_id', courseId);
            formData.append(csrfTokenName, csrfHash);

            const res = await fetch('<?= site_url('course/enroll') ?>', {
              method: 'POST',
              headers: { 'X-Requested-With': 'XMLHttpRequest' },
              body: formData
            });
            const data = await res.json();
            if (data.success) {
              showFlash(data.message || 'Enrolled successfully.');
              btn.outerHTML = '<span class="badge bg-success"><i class="fas fa-check"></i> Enrolled</span>';
            } else {
              showFlash(data.message || 'Failed to enroll.', 'danger');
              btn.disabled = false;
              btn.innerHTML = '<i class="fas fa-user-plus"></i> Enroll';
            }
          } catch (e) {
            showFlash('Network error. Please try again.', 'danger');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-user-plus"></i> Enroll';
          }
        });
      });
    });
  </script>
</body>
</html>
