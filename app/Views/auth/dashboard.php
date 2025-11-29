<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Authentication System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Notification Icon -->
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span id="notifBadge" class="badge bg-danger position-absolute top-0 start-100 translate-middle" style="display:none;">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown" style="min-width: 350px;">
                            <li class="dropdown-header">Notifications</li>
                            <li>
                                <div id="notifList" class="px-3 py-2">
                                    <div class="text-center text-muted" id="notifEmpty">No notifications</div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?= $user['name'] ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= base_url('auth/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('courses') ?>"><i class="fas fa-book"></i> Courses</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('announcements') ?>"><i class="fas fa-megaphone"></i> Announcements</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= site_url('auth/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        
        <div id="alerts"></div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Welcome Section -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <h2><i class="fas fa-home"></i> Welcome to Your Dashboard</h2>
                        <p class="mb-0">Hello, <?= $user['name'] ?>! You are successfully logged in.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Information Cards -->
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">User ID</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $user['id'] ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-id-card fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Full Name</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800"><?= $user['name'] ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Email</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800"><?= $user['email'] ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-envelope fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card border-left-warning shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Role</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'secondary' ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tag fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-bolt"></i> Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="#" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-user-edit"></i><br>
                                    Edit Profile
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#" class="btn btn-outline-success btn-lg w-100">
                                    <i class="fas fa-cog"></i><br>
                                    Settings
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#" class="btn btn-outline-info btn-lg w-100">
                                    <i class="fas fa-chart-bar"></i><br>
                                    Reports
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= site_url('auth/logout') ?>" class="btn btn-outline-danger btn-lg w-100">
                                    <i class="fas fa-sign-out-alt"></i><br>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrolled Courses -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-book-reader"></i> Enrolled Courses
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($enrollments)): ?>
                            <div class="list-group" id="enrolledCoursesList">
                                <?php foreach ($enrollments as $en): ?>
                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold"><?= esc($en['title'] ?? 'Untitled Course') ?></div>
                                            <small class="text-muted">Enrolled on: <?= esc($en['enrollment_date'] ?? '') ?></small>
                                            <?php if (!empty($en['description'])): ?>
                                                <div class="small mt-1 text-secondary"><?= esc($en['description']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <span class="badge bg-success rounded-pill">Enrolled</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div id="enrolledCoursesList" class="list-group"></div>
                            <p id="noEnrollmentsMsg" class="text-muted mb-0">You are not enrolled in any courses yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Courses -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list"></i> Available Courses
                        </h6>
                        <small class="text-muted">Enroll to start learning</small>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($availableCourses)): ?>
                            <div class="list-group" id="availableCoursesList">
                                <?php foreach ($availableCourses as $course): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold"><?= esc($course['title']) ?></div>
                                            <?php if (!empty($course['description'])): ?>
                                                <div class="small text-secondary"><?= esc($course['description']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <button class="btn btn-sm btn-primary enroll-btn" data-course-id="<?= (int) $course['id'] ?>" data_course_id="<?= (int) $course['id'] ?>">
                                            <i class="fas fa-user-plus"></i> Enroll
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">No available courses at the moment.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session Information -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle"></i> Session Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><strong>Session Status</strong></td>
                                        <td><span class="badge bg-success">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Login Time</strong></td>
                                        <td><?= date('Y-m-d H:i:s') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>User Agent</strong></td>
                                        <td><?= $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown' ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>IP Address</strong></td>
                                        <td><?= $_SERVER['REMOTE_ADDR'] ?? 'Unknown' ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function($) {
            const routeUrl = '<?= site_url('course/enroll') ?>';
            const csrf = {
                enabled: <?= function_exists('csrf_token') ? 'true' : 'false' ?>,
                name: '<?= function_exists('csrf_token') ? csrf_token() : '' ?>',
                hash: '<?= function_exists('csrf_hash') ? csrf_hash() : '' ?>'
            };

            function showAlert(type, message) {
                const $alert = $('<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert"></div>')
                    .html('<i class="fas ' + (type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle') + '"></i> ' + message)
                    .append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
                $('#alerts').empty().append($alert);
            }

            $(document).on('click', '.enroll-btn', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const courseId = $btn.data('course-id') || $btn.attr('data_course_id');
                if (!courseId) return;

                // Spinner state
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enrolling...');

                const payload = { course_id: courseId };
                if (csrf.enabled && csrf.name && csrf.hash) {
                    payload[csrf.name] = csrf.hash;
                }

                $.post(routeUrl, payload, function(resp) {
                    if (resp && resp.success) {
                        showAlert('success', resp.message || 'Enrolled successfully.');

                        // Build enrolled item from DOM context
                        const $item = $btn.closest('.list-group-item');
                        const title = $item.find('.fw-bold').text().trim();
                        const desc = $item.find('.small.text-secondary').text().trim();
                        const enrolledAt = new Date().toISOString().slice(0, 19).replace('T', ' ');

                        const enrolledHtml = [
                            '<div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">',
                            '  <div class="ms-2 me-auto">',
                            '    <div class="fw-bold">' + $('<div>').text(title || 'Untitled Course').html() + '</div>',
                            '    <small class="text-muted">Enrolled on: ' + enrolledAt + '</small>',
                            desc ? ('    <div class="small mt-1 text-secondary">' + $('<div>').text(desc).html() + '</div>') : '',
                            '  </div>',
                            '  <span class="badge bg-success rounded-pill">Enrolled</span>',
                            '</div>'
                        ].join('');

                        $('#noEnrollmentsMsg').remove();
                        $('#enrolledCoursesList').append(enrolledHtml);

                        // Update button to completed state and remove available item
                        $btn.removeClass('btn-primary').addClass('btn-success').html('<i class="fas fa-check"></i> Enrolled');
                        setTimeout(() => { $item.slideUp(200, function(){ $(this).remove(); }); }, 600);
                    } else {
                        $btn.prop('disabled', false).removeClass('btn-primary').addClass('btn-warning').html('<i class="fas fa-exclamation-circle"></i> Try Again');
                        showAlert('warning', (resp && resp.message) ? resp.message : 'Enrollment failed.');
                    }
                }, 'json')
                .fail(function() {
                    $btn.prop('disabled', false).removeClass('btn-primary').addClass('btn-danger').html('<i class="fas fa-times"></i> Error');
                    showAlert('danger', 'Network error. Please try again.');
                });
            });

            function loadNotifications() {
                $.get('<?= base_url('notifications') ?>', function(resp) {
                    if (resp && resp.success) {
                        if (resp.count > 0) {
                            $('#notifBadge').text(resp.count).show();
                        } else {
                            $('#notifBadge').hide();
                        }
                        var $list = $('#notifList').empty();
                        if (resp.notifications && resp.notifications.length > 0) {
                            resp.notifications.forEach(function(n) {
                                var item = $('<div class="alert alert-info d-flex justify-content-between align-items-center mb-2"></div>');
                                item.append('<span>' + $('<div>').text(n.message).html() + '</span>');
                                item.append('<button class="btn btn-sm btn-outline-success mark-read-btn" data-id="' + n.id + '">Mark as Read</button>');
                                $list.append(item);
                            });
                        } else {
                            $list.append('<div class="text-center text-muted" id="notifEmpty">No notifications</div>');
                        }
                    }
                }, 'json');
            }
            $('#notifDropdown').on('show.bs.dropdown', loadNotifications);
            loadNotifications();
            setInterval(loadNotifications, 60000);
            $(document).on('click', '.mark-read-btn', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var id = $btn.data('id');
                $.post('<?= base_url('notifications/mark_read') ?>/' + id, function(resp) {
                    if (resp && resp.success) {
                        $btn.closest('.alert').fadeOut(300, function() {
                            $(this).remove();
                            loadNotifications();
                        });
                    }
                }, 'json');
            });
        })(jQuery);
    </script>
</body>
</html>
