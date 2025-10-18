<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;

class Announcement extends BaseController
{
    protected $announcementModel;

    public function __construct()
    {
        helper(['url']);
        $this->announcementModel = new AnnouncementModel();
    }

    /**
     * Show all announcements to logged-in users
     */
    public function index()
    {
        // Require login
        if (! session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to view announcements.');
            return redirect()->to(base_url('auth/login'));
        }

        // Fetch announcements ordered newest first
        try {
            $announcements = $this->announcementModel
                ->orderBy('created_at', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Announcement fetch error: ' . $e->getMessage());
            $announcements = [];
        }

        return view('announcements', ['announcements' => $announcements]);
    }

    /**
     * Helper: returns dashboard route for a given role.
     * Use this in Auth::login() to keep redirection consistent:
     * e.g. return redirect()->to(Announcement::dashboardRouteForRole($role));
     */
    public static function dashboardRouteForRole(string $role): string
    {
        $map = [
            'admin'   => '/admin/dashboard',
            'teacher' => '/teacher/dashboard',
            'student' => '/student/dashboard', // or '/announcements' if students should land there
            'user'    => '/announcements',    // fallback
        ];

        return $map[$role] ?? $map['user'];
    }
}