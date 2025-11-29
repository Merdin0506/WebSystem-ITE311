<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2>System Diagnostic</h2>
        
        <div class="card mb-3">
            <div class="card-body">
                <h5>Authentication Status</h5>
                <p><strong>Logged in:</strong> <?= session()->get('isLoggedIn') ? 'Yes' : 'No' ?></p>
                <p><strong>User ID:</strong> <?= session()->get('userID') ?? 'Not set' ?></p>
                <p><strong>User Email:</strong> <?= session()->get('email') ?? 'Not set' ?></p>
                <p><strong>User Role:</strong> <?= session()->get('role') ?? 'Not set' ?></p>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5>Navigation Links</h5>
                <div class="d-grid gap-2">
                    <a href="<?= site_url('auth/login') ?>" class="btn btn-primary">Login Page</a>
                    <a href="<?= site_url('courses') ?>" class="btn btn-success">Main Courses Page (with search)</a>
                    <a href="<?= site_url('student/available-courses') ?>" class="btn btn-info">Student Available Courses</a>
                    <a href="<?= site_url('student/my-courses') ?>" class="btn btn-info">Student My Courses</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5>Current URL</h5>
                <p><?= current_url() ?></p>
            </div>
        </div>
    </div>
</body>
</html>