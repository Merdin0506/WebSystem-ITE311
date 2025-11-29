<?= $this->extend('student/layout') ?>

<?= $this->section('title') ?>Notifications<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h2><i class="fas fa-bell"></i> Notifications</h2>
                <p class="mb-0">Stay updated with important announcements and course updates.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bell"></i> Recent Notifications
                </h6>
                <?php if (!empty($all_notifications)): ?>
                    <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                        <i class="fas fa-check-double"></i> Mark All as Read
                    </button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (!empty($all_notifications)): ?>
                    <div class="list-group">
                        <?php foreach ($all_notifications as $notification): ?>
                            <div class="list-group-item list-group-item-action notification-item <?= $notification['is_read'] ? 'read' : '' ?>" 
                                 data-id="<?= $notification['id'] ?>">
                                <div class="d-flex w-100 justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 d-flex align-items-center">
                                            <i class="fas fa-bell <?= $notification['is_read'] ? 'text-muted' : 'text-primary' ?> me-2"></i>
                                            Notification
                                            <?php if (!$notification['is_read']): ?>
                                                <span class="badge bg-warning text-dark ms-2">New</span>
                                            <?php endif; ?>
                                        </h6>
                                        <p class="mb-1"><?= esc($notification['message']) ?></p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> 
                                            <?= date('M d, Y g:i A', strtotime($notification['created_at'])) ?>
                                        </small>
                                    </div>
                                    <?php if (!$notification['is_read']): ?>
                                        <button class="btn btn-sm btn-outline-success ms-2" 
                                                onclick="markAsRead(<?= $notification['id'] ?>)"
                                                title="Mark as Read">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No notifications yet</h5>
                        <p class="text-muted">You'll see notifications here when there are new announcements or updates.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->section('styles') ?>
<style>
    .notification-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .notification-item:not(.read) {
        background-color: #fff3cd;
        border-left-color: #ffc107;
    }
    
    .notification-item.read {
        background-color: #f8f9fa;
        border-left-color: #28a745;
        opacity: 0.8;
    }
    
    .notification-item:hover {
        background-color: #e9ecef;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function markAsRead(notificationId) {
        // Make AJAX request to mark notification as read
        fetch('<?= base_url('student/mark-as-read') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'notification_id=' + notificationId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the notification item
                const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
                if (notificationItem) {
                    notificationItem.classList.add('read');
                    
                    // Remove the "New" badge
                    const badge = notificationItem.querySelector('.badge');
                    if (badge) badge.remove();
                    
                    // Remove the mark as read button
                    const markButton = notificationItem.querySelector('.btn-outline-success');
                    if (markButton) markButton.remove();
                    
                    // Update the icon color
                    const icon = notificationItem.querySelector('.fas.fa-bell');
                    if (icon) {
                        icon.classList.remove('text-primary');
                        icon.classList.add('text-muted');
                    }
                    
                    // Show success message
                    showAlert('success', 'Notification marked as read!');
                    
                    // Update page notification count in navigation
                    if (typeof updateNotificationBadge === 'function') {
                        // Get fresh count from server with cache busting
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
                            console.log('Updated notification count:', data.unread_count);
                            updateNotificationBadge(data.unread_count);
                        })
                        .catch(error => {
                            console.log('Could not refresh notification count:', error);
                        });
                    }
                }
            } else {
                showAlert('danger', 'Failed to mark notification as read: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while marking notification as read.');
        });
    }

    function markAllAsRead() {
        // Make AJAX request to mark all notifications as read
        fetch('<?= base_url('student/mark-all-as-read') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update all notification items
                const notificationItems = document.querySelectorAll('.notification-item:not(.read)');
                notificationItems.forEach(item => {
                    item.classList.add('read');
                    
                    // Remove the "New" badge
                    const badge = item.querySelector('.badge');
                    if (badge) badge.remove();
                    
                    // Remove the mark as read button
                    const markButton = item.querySelector('.btn-outline-success');
                    if (markButton) markButton.remove();
                    
                    // Update the icon color
                    const icon = item.querySelector('.fas.fa-bell');
                    if (icon) {
                        icon.classList.remove('text-primary');
                        icon.classList.add('text-muted');
                    }
                });
                
                // Hide the "Mark All as Read" button
                const markAllButton = document.querySelector('.card-header button');
                if (markAllButton) markAllButton.style.display = 'none';
                
                // Show success message
                showAlert('success', 'All notifications marked as read!');
                
                // Update page notification count in navigation
                if (typeof updateNotificationBadge === 'function') {
                    // Get fresh count from server with cache busting
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
                        console.log('Updated notification count after mark all:', data.unread_count);
                        updateNotificationBadge(data.unread_count);
                    })
                    .catch(error => {
                        console.log('Could not refresh notification count:', error);
                    });
                }
            } else {
                showAlert('danger', 'Failed to mark all notifications as read: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while marking notifications as read.');
        });
    }
    
    function showAlert(type, message) {
        // Create and show Bootstrap alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert after the page header
        const pageHeader = document.querySelector('.card.bg-primary');
        if (pageHeader) {
            pageHeader.parentNode.insertBefore(alertDiv, pageHeader.nextSibling);
        }
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>