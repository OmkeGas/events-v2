<?php

namespace App\Controllers;

use Core\Controller;
use Core\Flasher;
use Core\Middleware;

/**
 * RegistrationController class
 * Handles all registration-related operations like registering for events and cancelling registrations
 */
class RegistrationController extends Controller
{

    /**
     * RegistrationController constructor.
     * Ensures that only user can access the registration page.
     */
    public function __construct()
    {
        Middleware::isAuth();
    }

    /**
     * Process event registration
     */
    public function store($eventId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/event/show/' . $eventId);
            return;
        }

        $eventModel = $this->model('Event');
        $registrationModel = $this->model('Registration');

        $eventController = new EventController();
        $eligibility = $eventController->validateEventRegistrationEligibility($eventModel, $eventId);

        if ($eligibility !== true) {
            Flasher::setFlash($eligibility['title'], $eligibility['message'], $eligibility['type']);
            $this->redirect('/event/show/' . $eventId);
            return;
        }

        $result = $registrationModel->register($eventId, $_SESSION['user']['id']);

        if ($result['status']) {
            Flasher::setFlash('Registration successful', 'You have successfully registered for this event', 'success');
        } else {
            // Use more specific error message if available
            $errorMessage = isset($result['message']) ? 'Error: ' . $result['message'] : 'An error occurred during registration. Please try again.';
            Flasher::setFlash('Registration failed', $errorMessage, 'error');
            // Log the error for admin review
            error_log("User registration failed for event {$eventId} by user {$_SESSION['user']['id']}: " . json_encode($result));
        }

        $this->redirect('/event/show/' . $eventId);
    }

    /**
     * Cancel a registration
     */
    public function cancel($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard/event');
            return;
        }

        $registrationModel = $this->model('Registration');

        // Check if registration exists and belongs to the user
        $registration = $registrationModel->getById($id);
        if (!$registration || $registration['id_user'] != $_SESSION['user']['id']) {
            Flasher::setFlash('Registration not found', 'The registration you are trying to cancel does not exist or does not belong to you', 'error');
            $this->redirect('/dashboard/event');
            return;
        }

        // Check if registration is already canceled
        if ($registration['status'] === 'canceled') {
            Flasher::setFlash('Already canceled', 'This registration has already been canceled', 'warning');
            $this->redirect('/dashboard/event');
            return;
        }

        // Cancel registration
        $result = $registrationModel->cancelRegistration($id);

        if ($result > 0) {
            Flasher::setFlash('Registration canceled', 'Your registration has been canceled', 'success');
        } else {
            Flasher::setFlash('Cancellation failed', 'An error occurred during cancellation. Please try again.', 'error');
        }

        $this->redirect('/dashboard/event');
    }
}
