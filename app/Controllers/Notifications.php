<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notifications extends BaseController
{
	public function get()
	{
		if (!session()->get('isLoggedIn')) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Not logged in',
				'count' => 0,
				'notifications' => []
			]);
		}

		$userId = session()->get('userID');
		$model = new NotificationModel();
		$count = $model->getUnreadCount($userId);
		$list = $model->getNotificationsForUser($userId, 10); // Increase limit if needed

		return $this->response->setJSON([
			'success' => true,
			'count' => $count,
			'notifications' => $list
		]);
	}

	public function mark_as_read($id = null)
	{
		if (!session()->get('isLoggedIn')) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Not logged in'
			]);
		}

		if (!$id) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'No notification ID provided'
			]);
		}

		$model = new NotificationModel();
		$updated = $model->markAsRead($id);

		return $this->response->setJSON([
			'success' => (bool)$updated,
			'message' => $updated ? 'Notification marked as read' : 'Failed to mark as read'
		]);
	}
}
