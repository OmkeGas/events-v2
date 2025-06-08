<?php

namespace App\Controllers;

use Core\Controller;
use Core\Flasher;
use Core\Middleware;
use Core\Validator;

/**
 * DashboardController class
 * Handles all dashboard operations for both admin and regular users
 */
class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     * Ensures user is authenticated before accessing any dashboard methods
     */
    public function __construct()
    {
        Middleware::isAuth();
    }

    /**
     * Show the dashboard based on user role
     * Automatically redirects to the appropriate dashboard view based on a user role
     */
    public function index()
    {
        // Load the Event model
        $eventModel = $this->model('Event');

        // Check if user is an admin
        if ($_SESSION['user']['role'] === 'admin') {
            // Admin dashboard with dynamic stats
            $userModel = $this->model('User');
            $registrationModel = $this->model('Registration');

            // Get stats
            $totalEvents = count($eventModel->getAll());
            $totalUsers = $userModel->getTotalUsers();
            $totalRegistrations = $registrationModel->getTotalRegistrations();
            $today = date('Y-m-d');
            $upcomingEvents = $eventModel->getUpcomingEventsCount($today);
            $recentEvents = $eventModel->getRecentEvents(3);

            // Data to pass to the view
            $data = [
                'title' => 'Admin Dashboard',
                'user' => [
                    'username' => $_SESSION['user']['username'],
                    'email' => $_SESSION['user']['email'],
                    'full_name' => $_SESSION['user']['full_name']
                ],
                'stats' => [
                    'totalEvents' => $totalEvents,
                    'totalUsers' => $totalUsers,
                    'totalRegistrations' => $totalRegistrations,
                    'upcomingEvents' => $upcomingEvents
                ],
                'recentEvents' => $recentEvents
            ];

            // Render the admin dashboard view
            $this->dashboardView('dashboard/admin/index', $data);
        } else {
            // Regular user dashboard
            $registrationModel = $this->model('Registration');

            // Get user registrations
            $userId = $_SESSION['user']['id'];
            $userRegistrations = $registrationModel->getUserRegistrations($userId);

            // Get user stats
            $totalRegistered = 0;
            $completedEvents = 0;
            $today = date('Y-m-d');
            $upcomingRegistered = 0;
            $nextEvent = null;
            $upcomingEvents = [];

            // Loop through user registrations to calculate stats
            foreach ($userRegistrations as $registration) {
                // Count only non-canceled registrations
                if ($registration['status'] != 'canceled') {
                    $totalRegistered++;

                    // Check if the event is in the future
                    if ($registration['start_date'] >= $today) {
                        $upcomingRegistered++;
                        $upcomingEvents[] = $registration;

                        // Find the next upcoming event (the closest one)
                        if ($nextEvent === null || strtotime($registration['start_date']) < strtotime($nextEvent['start_date'])) {
                            $nextEvent = $registration;
                        }
                    }
                    // Only count as completed if it's in the past AND the user attended
                    elseif ($registration['start_date'] < $today && $registration['attended'] == 1) {
                        $completedEvents++;
                    }
                }
            }

            // Data to pass to the view
            $data = [
                'title' => 'User Dashboard',
                'user' => [
                    'username' => $_SESSION['user']['username'],
                    'email' => $_SESSION['user']['email'],
                    'full_name' => $_SESSION['user']['full_name']
                ],
                'stats' => [
                    'totalRegistered' => $totalRegistered,
                    'upcomingEvents' => $upcomingRegistered,
                    'completedEvents' => $completedEvents
                ],
                'nextEvent' => $nextEvent,
                'recommendedEvents' =>  $eventModel->getRecommendedEvents($userId, 2)
            ];

            // Render the user dashboard view
            $this->dashboardView('dashboard/user/index', $data);
        }
    }


    /**
     * Show the event management page
     * Only accessible by admin or user based on their role
     */
    public function event()
    {
        // Check a user role and redirect to the appropriate method
        if ($_SESSION['user']['role'] === 'admin') {
            $this->adminEvent();
        } else {
            $this->userEvent();
        }
    }

    /**
     * Show user management page
     * Only accessible by admin users
     */
    public function users(){
        // Ensure only admin can access this method
        Middleware::isAdmin();

        // Load the User model and get all users
        $userModel = $this->model('User');
        $users = $userModel->getAll();

        $data = [
            'title' => 'User Management',
            'users' => $users,
            'user' => [
                'username' => $_SESSION['user']['username'],
                'email' => $_SESSION['user']['email'],
                'full_name' => $_SESSION['user']['full_name']
            ],
        ];

        $this->dashboardView('dashboard/admin/user/index', $data);
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        // Get user profile data from the User model by user ID
        $userModel = $this->model('User');
        $user = $userModel->getUserById($_SESSION['user']['id']);

        // Pass data to the view
        $data = [
            'title' => 'My Profile',
            'userProfile' => $user,
            'user' => [
                'username' => $_SESSION['user']['username'],
                'email' => $_SESSION['user']['email'],
                'full_name' => $_SESSION['user']['full_name']
            ]
        ];

        // Render the profile view
        $this->dashboardView('dashboard/profile/index', $data);
    }

    /**
     * Logout the user
     */
    public function logout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard');
            return;
        }

        session_destroy();
        session_start();

        Flasher::setFlash('Logged out successfully', 'See you again!', 'success');
        $this->redirect('/login');
    }

    /**
     * Show admin event management page
     * Only accessible by admin users
     */
    public function adminEvent()
    {
        // Ensure only admin can access this method
        Middleware::isAdmin();

        $data = [
            'title' => 'Event Management - Dashboard',
            'user' => [
                'username' => $_SESSION['user']['username'],
                'email' => $_SESSION['user']['email'],
                'full_name' => $_SESSION['user']['full_name']
            ],
            'events' => $this->model('Event')->getAll()
        ];
        $this->dashboardView('dashboard/admin/event/index', $data);
    }

    /**
     * Show user's registered events
     * Only accessible by regular users
     */
    public function userEvent()
    {
        // Ensure only regular users can access this method
        Middleware::isUser();

        $data = [
            'title' => 'My Events - Dashboard',
            'user' => [
                'username' => $_SESSION['user']['username'],
                'email' => $_SESSION['user']['email'],
                'full_name' => $_SESSION['user']['full_name']
            ],
            'registrations' => $this->model('Registration')->getUserRegistrations($_SESSION['user']['id'])
        ];
        $this->dashboardView('dashboard/user/event/index', $data);
    }
}
