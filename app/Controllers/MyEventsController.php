<?php

namespace App\Controllers;

use Core\Controller;
use Core\Flasher;

class MyEventsController extends Controller
{
    public function __construct()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            Flasher::setFlash('Login required', 'You need to login first to access this page', 'warning');
            $this->redirect('/login');
            exit;
        }
    }

    public function index()
    {
        $registrations = $this->model('Registration')->getUserRegistrations($_SESSION['user']['id']);

        $data = [
            'title' => 'My Events',
            'registrations' => $registrations
        ];

        $this->appView('event/my-events', $data);
    }

    public function cancelRegistration($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/my-events');
            return;
        }

        $registrationModel = $this->model('Registration');

        // Check if registration exists and belongs to the user
        $registration = $registrationModel->getById($id);
        if (!$registration || $registration['id_user'] != $_SESSION['user']['id']) {
            Flasher::setFlash('Registration not found', 'The registration you are trying to cancel does not exist or does not belong to you', 'error');
            $this->redirect('/my-events');
            return;
        }

        // Check if registration is already canceled
        if ($registration['status'] === 'canceled') {
            Flasher::setFlash('Already canceled', 'This registration has already been canceled', 'warning');
            $this->redirect('/my-events');
            return;
        }

        // Cancel registration
        $result = $registrationModel->cancelRegistration($id);

        if ($result > 0) {
            Flasher::setFlash('Registration canceled', 'Your registration has been canceled', 'success');
        } else {
            Flasher::setFlash('Cancellation failed', 'An error occurred during cancellation. Please try again.', 'error');
        }

        $this->redirect('/my-events');
    }
}
