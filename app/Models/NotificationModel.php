<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
	protected $table = 'notifications';
	protected $primaryKey = 'id';
	protected $returnType = 'array';
	protected $allowedFields = ['user_id', 'message', 'is_read', 'created_at'];
	protected $useTimestamps = false;

	// Fetches the count of unread notifications for a user
	public function getUnreadCount($userId)
	{
		return $this->where('user_id', $userId)
		            ->where('is_read', 0)
		            ->countAllResults();
	}

	// Fetches the latest notifications (limit 5) for a user - ONLY UNREAD for dropdown
	public function getNotificationsForUser($userId, $limit = 5)
	{
		return $this->where('user_id', $userId)
		            ->where('is_read', 0) // Only unread notifications
		            ->orderBy('created_at', 'DESC')
		            ->limit($limit)
		            ->findAll();
	}

	// Fetches all notifications for a user (both read and unread) for notifications page
	public function getAllNotificationsForUser($userId, $limit = 50)
	{
		return $this->where('user_id', $userId)
		            ->orderBy('created_at', 'DESC')
		            ->limit($limit)
		            ->findAll();
	}

	// Updates a specific notification's is_read field to 1
	public function markAsRead($notificationId)
	{
		return $this->update($notificationId, ['is_read' => 1]);
	}
}
