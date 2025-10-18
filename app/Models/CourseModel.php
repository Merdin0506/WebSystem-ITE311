<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table            = 'courses';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'title',
        'description',
        'instructor_id',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}
