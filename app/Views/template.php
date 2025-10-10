<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'ITE311-HARID' ?></title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Additional CSS -->
    <?= isset($additional_css) ? $additional_css : '' ?>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= site_url() ?>">ITE311-HARID</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?= (current_url() == site_url()) ? 'active' : '' ?>" href="<?= site_url() ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (current_url() == site_url('about')) ? 'active' : '' ?>" href="<?= site_url('about') ?>">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (current_url() == site_url('contact')) ? 'active' : '' ?>" href="<?= site_url('contact') ?>">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= site_url('auth/register') ?>">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= site_url('auth/login') ?>">Login</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Content Area -->
<div class="container mt-5">
    <?= isset($content) ? $content : '<h1>Welcome to ITE311-HARID</h1><p>This is the default template content.</p>' ?>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Additional JS -->
<?= isset($additional_js) ? $additional_js : '' ?>

</body>
</html>