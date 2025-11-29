<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        
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
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-light">
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('student/dashboard') ?>">
                <i class="fas fa-graduation-cap"></i> Student Portal
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars text-white"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?= esc($user['name']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header"><?= esc(session()->get('email')) ?></h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/my-courses') ?>"><i class="fas fa-book-reader"></i> My Courses</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/available-courses') ?>"><i class="fas fa-book-open"></i> Available Courses</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/assignments') ?>"><i class="fas fa-tasks"></i> Assignments</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/grades') ?>"><i class="fas fa-chart-line"></i> Grades</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/materials') ?>"><i class="fas fa-download"></i> Materials</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/announcements') ?>"><i class="fas fa-bullhorn"></i> Announcements</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('student/notifications') ?>"><i class="fas fa-bell"></i> Notifications 
                                <span class="badge bg-danger ms-1 notification-badge" style="display: none;">0</span>
                            </a></li>
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

        <!-- Page Content -->
        <?= $this->renderSection('content') ?>
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
            
            // Load notification count on page load
            loadNotificationCount();
        });
        
        function loadNotificationCount() {
            // Add timestamp to prevent caching
            const timestamp = new Date().getTime();
            fetch('<?= base_url('student/test-notifications') ?>?t=' + timestamp, {
                method: 'GET',
                headers: {
                    'Cache-Control': 'no-cache',
                    'Pragma': 'no-cache'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Fresh notification data:', data);
                updateNotificationBadge(data.unread_count);
            })
            .catch(error => {
                console.log('Could not load notification count:', error);
            });
        }
        
        function updateNotificationBadge(count) {
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.style.display = 'inline';
                } else {
                    badge.style.display = 'none';
                }
            }
        }
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>