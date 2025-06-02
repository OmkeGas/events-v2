<?php

namespace App\Controllers;

use Core\Controller;
use Core\Flasher;

class RegistrationController extends Controller
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

    public function store($eventId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/event/show' . $eventId);
            return;
        }

        $eventModel = $this->model('Event');
        $registrationModel = $this->model('Registration');

        $event = $eventModel->getById($eventId);
        if (!$event) {
            Flasher::setFlash('Event not found', 'The event you are trying to register for does not exist', 'error');
            $this->redirect('/event');
            return;
        }


        // Check if registration deadline has passed
        if (strtotime($event['registration_deadline']) < time()) {
            Flasher::setFlash('Registration closed', 'The registration deadline for this event has passed', 'error');
            $this->redirect('/event/show/' . $eventId);
            return;
        }

        // Check if event has already ended
        if (strtotime($event['end_date'] . ' ' . $event['end_time']) < time()) {
            Flasher::setFlash('Event ended', 'This event has already ended', 'error');
            $this->redirect('/event/show/' . $eventId);
            return;
        }

        // Check if user is already registered
        if ($eventModel->isUserRegistered($eventId, $_SESSION['user']['id'])) {
            Flasher::setFlash('Already registered', 'You are already registered for this event', 'warning');
            $this->redirect('/event/show/' . $eventId);
            return;
        }

        // Check quota availability
        $quotaInfo = $eventModel->checkQuota($eventId);
        if (!$quotaInfo || $quotaInfo['available'] <= 0) {
            Flasher::setFlash('Registration failed', 'This event has reached its maximum capacity', 'error');
            $this->redirect('/event/show/' . $eventId);
            return;
        }

        // Register user for the event
        $result = $registrationModel->register($eventId, $_SESSION['user']['id']);

        if ($result['status']) {
            Flasher::setFlash('Registration successful', 'You have successfully registered for this event', 'success');
            $this->redirect('/event/show/' . $eventId);
        } else {
            Flasher::setFlash('Registration failed', 'An error occurred during registration. Please try again.', 'error');
            $this->redirect('/event/show/' . $eventId);
        }
    }

    public function destroy($id)
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
