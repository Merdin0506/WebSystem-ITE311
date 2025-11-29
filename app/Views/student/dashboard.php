<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - <?= esc($user['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        
        .text-xs {
            font-size: .7rem;
        }
        
        .text-gray-300 {
            color: #dddfeb !important;
        }
        
        .badge-notification {
            position: absolute;
            top: -5px;
            right: -8px;
            font-size: 0.6rem;
            min-width: 18px;
            height: 18px;
            border-radius: 50%;
        }
        
        .notification-dropdown {
            padding: 0;
        }
        
        .notification-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin: 0;
            border: none;
            border-radius: 0;
            transition: all 0.3s ease;
            cursor: pointer;
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            position: relative;
        }
        
        .notification-item:hover {
            background-color: #e3f2fd;
        }
        
        .notification-item.read {
            background-color: #f5f5f5;
            opacity: 0.7;
            border-left: 4px solid #28a745;
        }
        
        .notification-item:not(.read) {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        
        .notification-item i {
            font-size: 1.1rem;
            margin-right: 12px;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
        }
        
        .notification-content {
            flex: 1;
            min-width: 0;
        }
        
        .notification-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 2px;
            color: #333;
        }
        
        .notification-text {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 2px;
            line-height: 1.3;
        }
        
        .notification-time {
            font-size: 0.75rem;
            color: #999;
        }
        
        .dropdown-item.notification-item {
            white-space: normal;
            overflow: hidden;
            border: none;
        }
        
        li {
            padding: 0 !important;
        }
        
        .dropdown-divider {
            margin: 0;
        }
        
        .mark-read-btn:hover {
            opacity: 1;
        }
        
        .dropdown-item .text-sm {
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('student/dashboard') ?>">
                <i class="fas fa-graduation-cap"></i> Student Portal
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger badge-notification">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 320px;">
                            <li><h6 class="dropdown-header d-flex justify-content-between align-items-center">Notifications <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">Mark All Read</button></h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <div class="dropdown-item notification-item" data-id="1" onclick="markAsRead(1)" style="cursor: pointer;">
                                    <i class="fas fa-bullhorn text-primary me-3" style="font-size: 1.1rem; align-self: flex-start; margin-top: 2px;"></i>
                                    <div class="notification-content">
                                        <h6 class="mb-1 text-sm">New Course Available</h6>
                                        <p class="text-muted small mb-0">Web Development Course now open</p>
                                        <small class="text-muted">2 hours ago</small>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <div class="dropdown-item notification-item" data-id="2" onclick="markAsRead(2)" style="cursor: pointer;">
                                    <i class="fas fa-graduation-cap text-success me-3" style="font-size: 1.1rem; align-self: flex-start; margin-top: 2px;"></i>
                                    <div class="notification-content">
                                        <h6 class="mb-1 text-sm">Assignment Due Soon</h6>
                                        <p class="text-muted small mb-0">Programming assignment due tomorrow</p>
                                        <small class="text-muted">1 day ago</small>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <div class="dropdown-item notification-item" data-id="3" onclick="markAsRead(3)" style="cursor: pointer;">
                                    <i class="fas fa-info-circle text-info me-3" style="font-size: 1.1rem; align-self: flex-start; margin-top: 2px;"></i>
                                    <div class="notification-content">
                                        <h6 class="mb-1 text-sm">System Update</h6>
                                        <p class="text-muted small mb-0">Portal updated with new features</p>
                                        <small class="text-muted">3 days ago</small>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-center" href="<?= base_url('student/notifications') ?>">
                                    <i class="fas fa-arrow-right"></i> View All Notifications
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?= esc($user['name']) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header"><?= esc($user['email']) ?></h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/my-courses') ?>"><i class="fas fa-book-reader"></i> My Courses</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/available-courses') ?>"><i class="fas fa-book-open"></i> Available Courses</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/assignments') ?>"><i class="fas fa-tasks"></i> Assignments</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/grades') ?>"><i class="fas fa-chart-line"></i> Grades</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/announcements') ?>"><i class="fas fa-bullhorn"></i> Announcements</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">

        <!-- Flash Messages -->
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
                        <h2><i class="fas fa-home"></i> Welcome back, <?= esc($user['name']) ?>!</h2>
                        <p class="mb-0">Here's your learning dashboard. Track your progress, access courses, and stay updated with announcements.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Enrolled Courses</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['enrolled_courses'] ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book-reader fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Assignments</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tasks fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Announcements</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total_announcements'] ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <!-- Recent Enrollments -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-clock"></i> Recent Enrollments
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($stats['recent_enrollments'])): ?>
                            <?php foreach ($stats['recent_enrollments'] as $enrollment): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-book text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?= esc($enrollment['title'] ?? 'Unknown Course') ?></h6>
                                        <small class="text-muted">Enrolled on <?= date('M d, Y', strtotime($enrollment['enrollment_date'] ?? 'now')) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">No recent enrollments</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Announcements -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-bullhorn"></i> Recent Announcements
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_announcements)): ?>
                            <?php foreach (array_slice($recent_announcements, 0, 3) as $announcement): ?>
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-bullhorn text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1"><?= esc($announcement['title'] ?? 'Announcement') ?></h6>
                                        <p class="text-muted small mb-0"><?= esc(substr($announcement['content'] ?? '', 0, 80)) ?>...</p>
                                        <small class="text-muted"><?= date('M d, Y', strtotime($announcement['created_at'] ?? 'now')) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="text-center">
                                <a href="<?= base_url('student/announcements') ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-arrow-right"></i> View All
                                </a>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">No recent announcements</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-bolt"></i> Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="<?= base_url('student/available-courses') ?>" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-plus-circle"></i><br>
                                    Enroll in Course
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= base_url('student/assignments') ?>" class="btn btn-outline-success btn-lg w-100">
                                    <i class="fas fa-tasks"></i><br>
                                    View Assignments
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= base_url('student/grades') ?>" class="btn btn-outline-info btn-lg w-100">
                                    <i class="fas fa-chart-line"></i><br>
                                    Check Grades
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?= base_url('student/announcements') ?>" class="btn btn-outline-warning btn-lg w-100">
                                    <i class="fas fa-bullhorn"></i><br>
                                    Read Announcements
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-close navbar on mobile after clicking a nav link
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992 && navbarCollapse.classList.contains('show')) {
                        const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                            toggle: false
                        });
                        bsCollapse.hide();
                    }
                });
            });
        });
        
        // Notification functions
        function markAsRead(notificationId) {
            const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.add('read');
                updateNotificationBadge();
            }
        }
        
        function markAllAsRead() {
            const notificationItems = document.querySelectorAll('.notification-item');
            notificationItems.forEach(item => {
                item.classList.add('read');
            });
            updateNotificationBadge();
        }
        
        function updateNotificationBadge() {
            const unreadItems = document.querySelectorAll('.notification-item:not(.read)');
            const badge = document.querySelector('.badge-notification');
            if (badge) {
                const count = unreadItems.length;
                if (count === 0) {
                    badge.style.display = 'none';
                } else {
                    badge.textContent = count;
                    badge.style.display = 'inline-block';
                }
            }
        }
    </script>
</body>
</html>
