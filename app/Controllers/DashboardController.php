<?php

namespace App\Controllers;

use Core\Controller;
use Core\Flasher;
use Core\Middleware;

class DashboardController extends Controller
{
    public function __construct()
    {
        Middleware::isAuth();
    }

    public function index()
    {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            // Admin dashboard with dynamic stats
            $eventModel = $this->model('Event');
            $userModel = $this->model('User');
            $registrationModel = $this->model('Registration');

            // Get stats
            $totalEvents = count($eventModel->getAll());
            $totalUsers = $userModel->getTotalUsers();
            $totalRegistrations = $registrationModel->getTotalRegistrations();

            // Get upcoming events (events with start_date >= today)
            $today = date('Y-m-d');
            $upcomingEvents = $eventModel->getUpcomingEventsCount($today);

            // Get recent events (limit to 3)
            $recentEvents = $eventModel->getRecentEvents(3);

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
            $this->dashboardView('dashboard/admin/index', $data);
        }
        else {
            // Regular user dashboard with useful information
            $eventModel = $this->model('Event');
            $registrationModel = $this->model('Registration');

            // Get user's registrations
            $userId = $_SESSION['user']['id'];
            $userRegistrations = $registrationModel->getUserRegistrations($userId);

            // Count total registered events (excluding canceled)
            $totalRegistered = 0;
            // Count attended (completed) events
            $completedEvents = 0;
            // Get upcoming registered events (events that haven't happened yet)
            $today = date('Y-m-d');
            $upcomingRegistered = 0;
            $nextEvent = null;
            $upcomingEvents = [];

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

            $recommendedEvents = $eventModel->getRecommendedEvents($userId, 2);



            // Check if there are any published upcoming events
            $publishedUpcoming = $eventModel->getUpcomingEventsCount($today);
            error_log("Total upcoming published events: " . $publishedUpcoming);

            // Check how many events the user is registered for
            error_log("User registered for " . $totalRegistered . " events");

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
                'recommendedEvents' => $recommendedEvents
            ];
            $this->dashboardView('dashboard/user/index', $data);
        }
    }

    public function event()
    {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            // Admin dashboard
            $data = [
                'title' => 'Event - Dashboard',
                'user' => [
                    'username' => $_SESSION['user']['username'],
                    'email' => $_SESSION['user']['email'],
                    'full_name' => $_SESSION['user']['full_name']
                ],
                'events' => $this->model('Event')->getAll()
            ];
            $this->dashboardView('dashboard/admin/event/index', $data);
        }
        else {
            // Regular user dashboard
            $data = [
                'title' => 'Event - Dashboard',
                'user' => [
                    'username' => $_SESSION['user']['username'],
                    'email' => $_SESSION['user']['email'],
                    'full_name' => $_SESSION['user']['full_name']
                ],
               'registrations' => $registrations = $this->model('Registration')->getUserRegistrations($_SESSION['user']['id'])
            ];
            $this->dashboardView('dashboard/user/event/index', $data);
        }
    }

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
}
