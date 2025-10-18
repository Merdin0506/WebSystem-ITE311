<?php
/** @var array $students */
/** @var array $courses */
/** @var array $enrollments */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Enrollments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0"><i class="fas fa-user-graduate"></i> Manage Enrollments</h1>
      <div class="d-flex gap-2">
        <a href="<?= site_url('admin/courses') ?>" class="btn btn-outline-secondary"><i class="fas fa-book"></i> Courses</a>
        <a href="<?= site_url('auth/dashboard') ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Dashboard</a>
      </div>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="row g-4">
      <div class="col-lg-5">
        <div class="card shadow-sm">
          <div class="card-header"><strong><i class="fas fa-plus"></i> Enroll Student</strong></div>
          <div class="card-body">
            <form action="<?= site_url('admin/enrollments/store') ?>" method="post">
              <?= function_exists('csrf_field') ? csrf_field() : '' ?>

              <div class="mb-3">
                <label class="form-label">Student</label>
                <select name="user_id" class="form-select" required>
                  <option value="">-- Select Student --</option>
                  <?php foreach ($students as $s): ?>
                    <option value="<?= (int)$s['id'] ?>" <?= set_select('user_id', (string)$s['id']) ?>>
                      <?= esc($s['name']) ?> (<?= esc($s['email']) ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Course</label>
                <select name="course_id" class="form-select" required>
                  <option value="">-- Select Course --</option>
                  <?php foreach ($courses as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= set_select('course_id', (string)$c['id']) ?>>
                      <?= esc($c['title']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Enroll</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="card shadow-sm">
          <div class="card-header"><strong><i class="fas fa-list"></i> Recent Enrollments</strong></div>
          <div class="card-body">
            <?php if (empty($enrollments)): ?>
              <p class="text-muted mb-0">No enrollments yet.</p>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table table-hover align-middle">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Student</th>
                      <th>Course</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($enrollments as $e): ?>
                      <tr>
                        <td><?= (int)$e['id'] ?></td>
                        <td><?= esc($e['user_name']) ?> <small class="text-muted d-block"><?= esc($e['user_email']) ?></small></td>
                        <td><?= esc($e['course_title']) ?></td>
                        <td><?= esc($e['enrollment_date']) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
