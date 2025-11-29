<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Student Portal</title>
	<!-- Add your CSS files here -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url('path/to/your/custom.css') ?>">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container">
		<a class="navbar-brand" href="#">
			<i class="fas fa-school"></i> Student Portal
		</a>
		<!-- ...existing code... -->
		<ul class="navbar-nav ms-auto">
			<!-- ...existing code... -->
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
			<!-- ...existing code... -->
		</ul>
	</div>
</nav>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function() {
	function loadNotifications() {
		$.get('<?= base_url('notifications') ?>', function(resp) {
			if (resp && resp.success) {
				// Badge
				if (resp.count > 0) {
					$('#notifBadge').text(resp.count).show();
				} else {
					$('#notifBadge').hide();
				}
				// List
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

	// Load notifications when dropdown is opened
	$('#notifDropdown').on('show.bs.dropdown', loadNotifications);

	// Load notifications on page load
	loadNotifications();

	// Poll every 60 seconds for updates
	setInterval(loadNotifications, 60000);

	// Mark as read handler
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
});
</script>

<!-- Add your JavaScript files here -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('path/to/your/custom.js') ?>"></script>
</body>
</html>