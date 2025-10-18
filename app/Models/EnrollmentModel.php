<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table            = 'enrollments';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'user_id',
        'course_id',
        'enrollment_date',
    ];

    protected $useTimestamps = false; // we manage 'enrollment_date' manually

    /**
     * Insert a new enrollment record if not already enrolled.
     *
     * @param array $data expects keys: user_id, course_id, enrollment_date
     * @return int|false Insert ID on success, false on duplicate or failure
     */
    public function enrollUser(array $data)
    {
        if (! isset($data['user_id'], $data['course_id'])) {
            return false;
        }

        if ($this->isAlreadyEnrolled((int) $data['user_id'], (int) $data['course_id'])) {
            return false;
        }

        // If no enrollment_date provided, default to now
        if (! isset($data['enrollment_date'])) {
            $data['enrollment_date'] = date('Y-m-d H:i:s');
        }

        return $this->insert($data, true); // return inserted ID
    }

    /**
     * Fetch all courses a user is enrolled in.
     *
     * @param int $user_id
     * @return array
     */
    public function getUserEnrollments(int $user_id): array
    {
        return $this
            ->select('enrollments.*, courses.*')
            ->join('courses', 'courses.id = enrollments.course_id', 'left')
            ->where('enrollments.user_id', $user_id)
            ->orderBy('enrollments.enrollment_date', 'DESC')
            ->findAll();
    }

    /**
     * Check if a user is already enrolled in a specific course.
     *
     * @param int $user_id
     * @param int $course_id
     * @return bool
     */
    public function isAlreadyEnrolled(int $user_id, int $course_id): bool
    {
        return $this->where([
            'user_id'   => $user_id,
            'course_id' => $course_id,
        ])->first() !== null;
    }
}
