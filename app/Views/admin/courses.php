<?php
/** @var array $instructors */
/** @var array $courses */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0"><i class="fas fa-book"></i> Manage Courses</h1>
      <a href="<?= site_url('auth/dashboard') ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
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
          <div class="card-header"><strong><i class="fas fa-plus"></i> Add Course</strong></div>
          <div class="card-body">
            <form action="<?= site_url('admin/courses/store') ?>" method="post">
              <?= function_exists('csrf_field') ? csrf_field() : '' ?>
              <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?= old('title') ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"><?= old('description') ?></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Instructor</label>
                <select name="instructor_id" class="form-select" required>
                  <option value="">-- Select Instructor --</option>
                  <?php foreach ($instructors as $ins): ?>
                    <option value="<?= (int)$ins['id'] ?>" <?= set_select('instructor_id', (string)$ins['id']) ?>>
                      <?= esc($ins['name']) ?> (<?= esc($ins['email']) ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Course</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="card shadow-sm">
          <div class="card-header"><strong><i class="fas fa-list"></i> Existing Courses</strong></div>
          <div class="card-body">
            <?php if (empty($courses)): ?>
              <p class="text-muted mb-0">No courses yet. Add one using the form.</p>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table table-hover align-middle">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Title</th>
                      <th>Instructor</th>
                      <th>Created</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($courses as $c): ?>
                      <tr>
                        <td><?= (int)$c['id'] ?></td>
                        <td><?= esc($c['title']) ?></td>
                        <td>
                          <?php
                            $ins = array_filter($instructors, fn($i) => (int)$i['id'] === (int)$c['instructor_id']);
                            $ins = array_values($ins);
                          ?>
                          <?= !empty($ins) ? esc($ins[0]['name']) : 'N/A' ?>
                        </td>
                        <td><?= esc($c['created_at']) ?></td>
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
