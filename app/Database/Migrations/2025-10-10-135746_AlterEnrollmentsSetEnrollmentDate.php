<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class alterEnrollmentsSetEnrollmentDate extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        // If 'enrolled_at' exists, rename it to 'enrollment_date'
        if ($this->db->fieldExists('enrolled_at', 'enrollments')) {
            $this->forge->modifyColumn('enrollments', [
                'enrolled_at' => [
                    'name' => 'enrollment_date',
                    'type' => 'DATETIME',
                    'null' => false,
                ],
            ]);
        } elseif (! $this->db->fieldExists('enrollment_date', 'enrollments')) {
            // Otherwise if 'enrollment_date' does not exist, add it
            $this->forge->addColumn('enrollments', [
                'enrollment_date' => [
                    'type' => 'DATETIME',
                    'null' => false,
                ],
            ]);
        }
    }

    public function down()
    {
        // If 'enrollment_date' exists and 'enrolled_at' does not, rename back
        if ($this->db->fieldExists('enrollment_date', 'enrollments') && ! $this->db->fieldExists('enrolled_at', 'enrollments')) {
            $this->forge->modifyColumn('enrollments', [
                'enrollment_date' => [
                    'name' => 'enrolled_at',
                    'type' => 'DATETIME',
                    'null' => false,
                ],
            ]);
        } elseif ($this->db->fieldExists('enrollment_date', 'enrollments')) {
            // Otherwise if we only added 'enrollment_date', drop it
            $this->forge->dropColumn('enrollments', 'enrollment_date');
        }
    }
}
