<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
	protected $table = 'announcements';
	protected $primaryKey = 'id';
	protected $returnType = 'array';
	protected $allowedFields = ['title', 'content', 'created_at'];
	protected $useTimestamps = false;

	/**
	 * Get all announcements ordered by created_at descending
	 */
	public function getAnnouncements()
	{
		return $this->orderBy('created_at', 'DESC')->findAll();
	}

	/**
	 * Get recent announcements with a limit
	 */
	public function getRecentAnnouncements($limit = 5)
	{
		return $this->orderBy('created_at', 'DESC')
		            ->limit($limit)
		            ->findAll();
	}
}
