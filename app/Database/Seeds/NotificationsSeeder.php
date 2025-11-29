<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NotificationsSeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
				'user_id'    => 1, // Change to a valid user ID in your DB
				'message'    => 'Welcome! This is your first notification.',
				'is_read'    => 0,
				'created_at' => date('Y-m-d H:i:s'),
			],
			[
				'user_id'    => 1, // Change to a valid user ID in your DB
				'message'    => 'You have been enrolled in Math 101.',
				'is_read'    => 0,
				'created_at' => date('Y-m-d H:i:s'),
			],
			[
				'user_id'    => 2, // Another user for testing
				'message'    => 'Your profile was updated.',
				'is_read'    => 0,
				'created_at' => date('Y-m-d H:i:s'),
			],
		];

		$this->db->table('notifications')->insertBatch($data);
	}
}
