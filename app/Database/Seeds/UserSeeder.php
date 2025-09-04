<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'       => 'Test User',
            'email'      => 'test@example.com',
            'password'   => password_hash('123456', PASSWORD_DEFAULT),
            'role'       => 'student',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('users')->insert($data);

        echo "Inserted ID: " . $this->db->insertID() . PHP_EOL;
    }
}
