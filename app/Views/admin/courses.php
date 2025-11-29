<?php
/** @var array $instructors */
/** @var array $courses */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Courses - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .btn-outline-purple {
      color: #6f42c1;
      border-color: #6f42c1;
    }
    .btn-outline-purple:hover {
      color: #fff;
      background-color: #6f42c1;
      border-color: #6f42c1;
    }
    .card-header {
      background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
      color: white;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .stats-card {
      border-left: 4px solid;
    }
    .stats-card.primary { border-left-color: #007bff; }
    .stats-card.success { border-left-color: #28a745; }
    .stats-card.warning { border-left-color: #ffc107; }
    .stats-card.info { border-left-color: #17a2b8; }
  </style>
</head>
<body class="bg-light">
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="<?= site_url('admin/dashboard') ?>">
        <i class="fas fa-user-shield"></i> Admin Dashboard
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-user"></i> <?= session()->get('name') ?>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="<?= site_url('admin/courses') ?>"><i class="fas fa-book"></i> Manage Courses</a></li>
              <li><a class="dropdown-item" href="<?= site_url('admin/enrollments') ?>"><i class="fas fa-users"></i> Manage Enrollments</a></li>
              <li><a class="dropdown-item" href="<?= site_url('admin/materials') ?>"><i class="fas fa-file-alt"></i> All Materials</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="fas fa-upload"></i> Upload Materials</a></li>
              <li><a class="dropdown-item" href="<?= site_url('admin/browse-uploads') ?>"><i class="fas fa-folder-open"></i> Browse Uploads</a></li>
              <li><a class="dropdown-item" href="<?= site_url('announcements') ?>"><i class="fas fa-megaphone"></i> Announcements</a></li>
              <li><a class="dropdown-item" href="<?= site_url('security-test/unauthorized') ?>"><i class="fas fa-shield-alt"></i> Security Tests</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= site_url('auth/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="fas fa-book"></i> Manage Courses</h2>
      <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
      </a>
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
        <div class="card shadow">
          <div class="card-header">
            <h6 class="mb-0">
              <i class="fas fa-plus"></i> Add Course
            </h6>
          </div>
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
        <div class="card shadow">
          <div class="card-header">
            <h6 class="mb-0">
              <i class="fas fa-list"></i> Existing Courses
            </h6>
          </div>
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
                      <th>Actions</th>
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
                        <td>
                          <div class="btn-group" role="group">
                            <a href="<?= site_url('admin/course/' . $c['id'] . '/materials') ?>" 
                               class="btn btn-sm btn-outline-primary" 
                               title="Manage Materials">
                              <i class="fas fa-folder"></i>
                            </a>
                            <a href="<?= site_url('admin/course/' . $c['id'] . '/upload') ?>" 
                               class="btn btn-sm btn-outline-success" 
                               title="Upload Material">
                              <i class="fas fa-upload"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger" 
                                    onclick="confirmDelete(<?= $c['id'] ?>, '<?= addslashes(esc($c['title'])) ?>')" 
                                    title="Delete Course">
                              <i class="fas fa-trash"></i>
                            </button>
                          </div>
                        </td>
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

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
            Confirm Deletion
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete the course <strong id="courseName"></strong>?</p>
          <div class="alert alert-warning">
            <i class="fas fa-warning me-2"></i>
            <strong>Warning:</strong> This action will also delete:
            <ul class="mb-0 mt-2">
              <li>All materials uploaded to this course</li>
              <li>All student enrollments for this course</li>
              <li>All related data</li>
            </ul>
          </div>
          <p class="text-danger"><strong>This action cannot be undone!</strong></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Cancel
          </button>
          <a href="#" id="deleteConfirmBtn" class="btn btn-danger">
            <i class="fas fa-trash me-1"></i>Delete Course
          </a>
        </div>
      </div>
    </div>
  </div>

  <script>
  function confirmDelete(courseId, courseName) {
    document.getElementById('courseName').textContent = courseName;
    document.getElementById('deleteConfirmBtn').href = '<?= site_url("admin/courses/delete/") ?>' + courseId;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
  }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
