<?php

namespace App\Controllers;

use Core\Controller;
use Core\Validator;
use Core\Flasher;

class EventController extends Controller
{
    private const EVENT_VALIDATION_RULES = [
        'title' => ['required', ['min', 3], ['max', 255]],
        'speaker' => ['required', ['min', 3], ['max', 100]],
        'quota' => ['required', 'numeric', ['min_value', 1]],
        'category_id' => ['required', 'numeric'],
        'start_date' => ['required', 'date'],
        'start_time' => ['required'],
        'end_date' => ['required', 'date', ['greater_than_equal_to_field', 'start_date']],
        'end_time' => ['required'],
        'registration_deadline' => ['required', 'date'],
        'location_name' => ['required', ['min', 3], ['max', 150]],
        'short_description' => ['required', ['min', 10], ['max', 255]],
        'full_description' => ['required'],
        'is_published' => ['required', ['in', ['n', 'y']]],
        'thumbnail' => ['uploaded_file', ['mime_types', ['image/jpeg', 'image/png', 'image/jpg']], ['max_size', '2M']], // Remove 'required'
    ];

    public function index()
    {
        $data = [
            'title' => 'Events',
            'events' => $this->model('Event')->getPublished()
        ];
        $this->appView('event/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Event',
            'categories' => $this->model('EventCategory')->getAll(),
        ];
        $this->dashboardView('dashboard/admin/event/event-create', $data);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/event/create');
            return;
        }

        $_SESSION['old_input'] = $_POST;

        // Debug file uploads
        if (isset($_FILES['thumbnail'])) {
            $_SESSION['debug_file'] = [
                'name' => $_FILES['thumbnail']['name'],
                'type' => $_FILES['thumbnail']['type'],
                'size' => $_FILES['thumbnail']['size'],
                'error' => $_FILES['thumbnail']['error']
            ];
        }

        $validator = new Validator();

        $validationRules = self::EVENT_VALIDATION_RULES;

        if (!isset($_FILES['thumbnail']) || empty($_FILES['thumbnail']['name']) || $_FILES['thumbnail']['error'] === UPLOAD_ERR_NO_FILE) {
            unset($validationRules['thumbnail']);
        }

        if (!$validator->validate($_POST, $validationRules)) {
            $_SESSION['validation_errors'] = $validator->getErrors();
            Flasher::setFlash('Validation failed', 'Please check your input', 'error');
            $this->redirect('/event/create');
            return;
        }

        $inputData = $_POST;
        $dateFieldsToConvert = ['start_date', 'end_date', 'registration_deadline'];
        foreach ($dateFieldsToConvert as $field) {
            if (!empty($inputData[$field])) {
                $dateObj = \DateTime::createFromFormat('m/d/Y', $inputData[$field]);
                if ($dateObj) {
                    $inputData[$field] = $dateObj->format('Y-m-d');
                }
            }
        }

        $thumbnailPath = '';
        if (isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail']['name']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'images/events/';

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileExtension = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
            $uniqueId = uniqid('event_', true);
            $fileName = $uniqueId . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadPath)) {
                $thumbnailPath = $fileName;
            } else {
                $_SESSION['validation_errors']['thumbnail'] = 'Failed to upload thumbnail';
                Flasher::setFlash('Thumbnail upload failed', 'Please try again', 'error');
                $this->redirect('/event/create');
                return;
            }
        }

        $eventModel = $this->model('Event');

        $data = [
            'title' => $_POST['title'],
            'speaker' => $_POST['speaker'],
            'category_id' => $_POST['category_id'],
            'quota' => $_POST['quota'],
            'short_description' => $_POST['short_description'],
            'full_description' => $_POST['full_description'],
            'start_date' => $inputData['start_date'],
            'start_time' => $_POST['start_time'],
            'end_date' => $inputData['end_date'],
            'end_time' => $_POST['end_time'],
            'registration_deadline' => $inputData['registration_deadline'],
            'location_name' => $_POST['location_name'],
            'location_address' => $_POST['location_address'] ?? null,
            'location_link' => $_POST['location_link'] ?? null,
            'is_published' => $_POST['is_published'] == 'y' ? 1 : 0,
            'thumbnail' => $thumbnailPath
        ];

        $result = $eventModel->create($data);

        if ($result > 0) {
            unset($_SESSION['old_input']);
            Flasher::setFlash('Event created', 'Event was successfully created', 'success');
            $this->redirect('/dashboard/event');
        } else {
            Flasher::setFlash('Event creation failed', 'Please try again', 'error');
            $this->redirect('/event/create');
        }
    }

    public function edit($id)
    {
        $event = $this->model('Event')->getById($id);

        if (!$event) {
            Flasher::setFlash('Event not found', 'The event you are trying to edit does not exist', 'error');
            $this->redirect('/dashboard/event');
            return;
        }

        $dateFields = ['start_date', 'end_date'];
        foreach ($dateFields as $field) {
            if (!empty($event[$field])) {
                $dateObj = \DateTime::createFromFormat('Y-m-d', $event[$field]);
                if ($dateObj) {
                    $event[$field] = $dateObj->format('m/d/Y');
                }
            }
        }

        if (!empty($event['registration_deadline'])) {
            $dateObj = \DateTime::createFromFormat('Y-m-d H:i:s', $event['registration_deadline']);
            if (!$dateObj) {
                $dateObj = \DateTime::createFromFormat('Y-m-d', $event['registration_deadline']);
            }
            if ($dateObj) {
                $event['registration_deadline'] = $dateObj->format('m/d/Y');
            }
        }

        $data = [
            'title' => 'Edit Event',
            'categories' => $this->model('EventCategory')->getAll(),
            'event' => $event
        ];

        $this->dashboardView('dashboard/admin/event/event-edit', $data);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard/event');
            return;
        }

        $existingEvent = $this->model('Event')->getById($id);
        if (!$existingEvent) {
            Flasher::setFlash('Event not found', 'The event you are trying to edit does not exist', 'error');
            $this->redirect('/dashboard/event');
            return;
        }

        $_SESSION['old_input'] = $_POST;

        $validator = new Validator();

        $validationRules = self::EVENT_VALIDATION_RULES;

        if (!isset($_FILES['thumbnail']) || empty($_FILES['thumbnail']['name']) || $_FILES['thumbnail']['error'] === UPLOAD_ERR_NO_FILE) {
            unset($validationRules['thumbnail']);
        }

        if (!$validator->validate($_POST, $validationRules)) {
            $_SESSION['validation_errors'] = $validator->getErrors();
            Flasher::setFlash('Validation failed', 'Please check your input', 'error');
            $this->redirect("/event/edit/{$id}");
            return;
        }

        $inputData = $_POST;
        $dateFieldsToConvert = ['start_date', 'end_date', 'registration_deadline'];
        foreach ($dateFieldsToConvert as $field) {
            if (!empty($inputData[$field])) {
                $dateObj = \DateTime::createFromFormat('m/d/Y', $inputData[$field]);
                if ($dateObj) {
                    $inputData[$field] = $dateObj->format('Y-m-d');
                }
            }
        }

        $thumbnailPath = $existingEvent['thumbnail'];

        if (isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail']['name']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'images/events/';

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileExtension = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
            $uniqueId = uniqid('event_', true);
            $fileName = $uniqueId . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadPath)) {
                // Delete old thumbnail if exists
                if (!empty($existingEvent['thumbnail'])) {
                    $oldThumbnailPath = $uploadDir . $existingEvent['thumbnail'];
                    if (file_exists($oldThumbnailPath)) {
                        unlink($oldThumbnailPath);
                    }
                }
                $thumbnailPath = $fileName;
            } else {
                $_SESSION['validation_errors']['thumbnail'] = 'Failed to upload thumbnail';
                Flasher::setFlash('Thumbnail upload failed', 'Please try again', 'error');
                $this->redirect("/event/edit/{$id}");
                return;
            }
        }

        // Update event data
        $eventModel = $this->model('Event');

        $data = [
            'title' => $_POST['title'],
            'speaker' => $_POST['speaker'],
            'category_id' => $_POST['category_id'],
            'quota' => $_POST['quota'],
            'short_description' => $_POST['short_description'],
            'full_description' => $_POST['full_description'],
            'start_date' => $inputData['start_date'],
            'start_time' => $_POST['start_time'],
            'end_date' => $inputData['end_date'],
            'end_time' => $_POST['end_time'],
            'registration_deadline' => $inputData['registration_deadline'],
            'location_name' => $_POST['location_name'],
            'location_address' => $_POST['location_address'] ?? null,
            'location_link' => $_POST['location_link'] ?? null,
            'is_published' => $_POST['is_published'] == 'y' ? 1 : 0,
            'thumbnail' => $thumbnailPath
        ];

        $result = $eventModel->update($id, $data);

        if ($result > 0) {
            unset($_SESSION['old_input']);
            Flasher::setFlash('Event updated', 'Event was successfully updated', 'success');
            $this->redirect('/dashboard/event');
        } else {
            Flasher::setFlash('Event update failed', 'Please try again', 'error');
            $this->redirect("/event/edit/{$id}");
        }
    }

    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard/event');
            return;
        }

        // Get existing event data to find the thumbnail
        $existingEvent = $this->model('Event')->getById($id);
        if (!$existingEvent) {
            Flasher::setFlash('Event not found', 'The event you are trying to delete does not exist', 'error');
            $this->redirect('/dashboard/event');
            return;
        }

        // Delete the event from database
        $eventModel = $this->model('Event');
        $result = $eventModel->delete($id);

        if ($result > 0) {
            // Delete the thumbnail file if it exists
            if (!empty($existingEvent['thumbnail'])) {
                $thumbnailPath = 'images/events/' . $existingEvent['thumbnail'];
                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }
            }

            Flasher::setFlash('Event deleted', 'Event was successfully deleted', 'success');
            $this->redirect('/dashboard/event');
        } else {
            Flasher::setFlash('Event deletion failed', 'Please try again', 'error');
            $this->redirect('/dashboard/event');
        }
    }

    public function show($id)
    {
        $eventModel = $this->model('Event');
        $event = $eventModel->getById($id);

        if (!$event) {
            $this->redirect('/event');
            return;
        }


        if ($event['is_published'] == 0) {
            if (!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'admin')) {
                $this->redirect('/event');
                return;
            }
        }


        $participants = [];
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $participants = $eventModel->getParticipants($id);
        }

        $quotaInfo = $eventModel->checkQuota($id);

        $isRegistered = false;
        if (isset($_SESSION['user'])) {
            $isRegistered = $eventModel->isUserRegistered($id, $_SESSION['user']['id']);
        }

        $data = [
            'title' => $event['title'],
            'event' => $event,
            'participants' => $participants,
            'quotaInfo' => $quotaInfo,
            'isRegistered' => $isRegistered
        ];

        $this->appView('event/detail', $data);
    }

    public function register($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/event/' . $id);
            return;
        }

        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            Flasher::setFlash('Login required', 'You need to login first to register for an event', 'warning');
            $this->redirect('/login');
            return;
        }

        $eventModel = $this->model('Event');
        $registrationModel = $this->model('Registration');

        // Check if event exists
        $event = $eventModel->getById($id);
        if (!$event) {
            Flasher::setFlash('Event not found', 'The event you are trying to register for does not exist', 'error');
            $this->redirect('/event');
            return;
        }

        // Check if event is published
        if ($event['is_published'] != 1) {
            Flasher::setFlash('Registration failed', 'This event is not open for registration', 'error');
            $this->redirect('/event/' . $id);
            return;
        }

        // Check if registration deadline has passed
        if (strtotime($event['registration_deadline']) < time()) {
            Flasher::setFlash('Registration closed', 'The registration deadline for this event has passed', 'error');
            $this->redirect('/event/' . $id);
            return;
        }

        // Check if event has already ended
        if (strtotime($event['end_date'] . ' ' . $event['end_time']) < time()) {
            Flasher::setFlash('Event ended', 'This event has already ended', 'error');
            $this->redirect('/event/' . $id);
            return;
        }

        // Check if user is already registered
        if ($eventModel->isUserRegistered($id, $_SESSION['user']['id'])) {
            Flasher::setFlash('Already registered', 'You are already registered for this event', 'warning');
            $this->redirect('/event/' . $id);
            return;
        }

        // Check quota availability
        $quotaInfo = $eventModel->checkQuota($id);
        if (!$quotaInfo || $quotaInfo['available'] <= 0) {
            Flasher::setFlash('Registration failed', 'This event has reached its maximum capacity', 'error');
            $this->redirect('/event/' . $id);
            return;
        }

        // Register user for the event
        $result = $registrationModel->register($id, $_SESSION['user']['id']);

        if ($result['status']) {
            Flasher::setFlash('Registration successful', 'You have successfully registered for this event', 'success');
            $this->redirect('/my-events');
        } else {
            Flasher::setFlash('Registration failed', 'An error occurred during registration. Please try again.', 'error');
            $this->redirect('/event/' . $id);
        }
    }
}
