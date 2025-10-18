<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementsSeeder extends Seeder
{
	public function run()
	{
		$now = date('Y-m-d H:i:s');

		$data = [
			[
				'title'      => 'Welcome to the Student Portal',
				'content'    => "We're pleased to launch the portal. Check courses and announcements regularly.",
				'created_at' => $now,
			],
			[
				'title'      => 'Maintenance Notice',
				'content'    => "Scheduled maintenance on Saturday 10 PM - 2 AM. Some services may be unavailable.",
				'created_at' => $now,
			],
		];

		$this->db->table('announcements')->insertBatch($data);
	}
}
