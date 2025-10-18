<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateAdminRoleSeeder extends Seeder
{
    public function run()
    {
        // Set role = 'admin' for known admin account(s)
        $db = \Config\Database::connect();

        // Prefer by email if present; otherwise by ID 9 as per your data
        $db->table('users')->where('email', 'admin@gmail.com')->set(['role' => 'admin'])->update();
        $db->table('users')->where('id', 9)->set(['role' => 'admin'])->update();
    }
}
