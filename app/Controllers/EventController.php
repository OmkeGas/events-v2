<?php

namespace App\Controllers;

use Core\Controller;
use Core\Validator;
use Core\Flasher;
use Core\Middleware;
use DateTime;

class EventController extends Controller
{
    /**
     * Validation rules for event form
     */
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
        'thumbnail' => ['uploaded_file', ['mime_types', ['image/jpeg', 'image/png', 'image/jpg']], ['max_size', '2M']],
    ];

    /**
     * Display a list of all published events with intelligent sorting
     * Events are sorted by a priority algorithm that considers:
     * - Events happening soon
     * - Events with limited available seats
     * - Events with approaching registration deadlines
     * - Popular events with many participants
     */
    public function index()
    {
        // Clear search keyword when visiting the main events page
        if (isset($_SESSION['search_keyword'])) {
            unset($_SESSION['search_keyword']);
        }

        $eventModel = $this->model('Event');
        $events = $eventModel->getPublished();

        // Prepare event data with additional information for the view
        $processedEvents = [];
        foreach ($events as $event) {
            // Calculate remaining days and check if event is past
            $today = new DateTime();
            $eventDate = new DateTime($event['start_date'] . ' ' . $event['start_time']);
            $daysRemaining = $today->diff($eventDate)->days;
            $isPast = $today > $eventDate;

            // Get event status information
            $quotaInfo = $eventModel->checkQuota($event['id']);
            $registrationClosed = strtotime($event['registration_deadline']) < time();
            $eventEnded = strtotime($event['end_date'] . ' ' . $event['end_time']) < time();
            $isRegistered = false;

            // Check if current user is registered for this event
            if (isset($_SESSION['user'])) {
                $isRegistered = $eventModel->isUserRegistered($event['id'], $_SESSION['user']['id']);
            }

            // Add all information to the event data
            $event['days_remaining'] = $daysRemaining;
            $event['is_past'] = $isPast;
            $event['quota_info'] = $quotaInfo;
            $event['registration_closed'] = $registrationClosed;
            $event['event_ended'] = $eventEnded;
            $event['is_registered'] = $isRegistered;

            // Calculate priority score for sorting
            $priorityScore = $this->calculateEventPriorityScore(
                $isPast,
                $eventEnded,
                $daysRemaining,
                $quotaInfo,
                $registrationClosed,
                $event['registration_deadline']
            );

            $event['priority_score'] = $priorityScore;
            $processedEvents[] = $event;
        }

        // Sort events by priority score (descending)
        usort($processedEvents, function($a, $b) {
            return $b['priority_score'] - $a['priority_score'];
        });

        $data = [
            'title' => 'Events',
            'events' => $processedEvents
        ];

        $this->appView('event/index', $data);
    }

    /**
     * Search for events based on keyword
     * Public access
     */
    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';

        // Redirect to main event page if no keyword provided
        if (empty($keyword)) {
            $this->redirect('/event');
            return;
        }

        // Save keyword to session so it persists between page loads
        $_SESSION['search_keyword'] = $keyword;

        $eventModel = $this->model('Event');
        $events = $eventModel->searchEvents($keyword);

        // Prepare event data with additional information for the view
        $processedEvents = [];
        foreach ($events as $event) {
            // Calculate remaining days and check if event is past
            $today = new DateTime();
            $eventDate = new DateTime($event['start_date'] . ' ' . $event['start_time']);
            $daysRemaining = $today->diff($eventDate)->days;
            $isPast = $today > $eventDate;

            // Get event status information
            $quotaInfo = $eventModel->checkQuota($event['id']);
            $registrationClosed = strtotime($event['registration_deadline']) < time();
            $eventEnded = strtotime($event['end_date'] . ' ' . $event['end_time']) < time();
            $isRegistered = false;

            // Check if current user is registered for this event
            if (isset($_SESSION['user'])) {
                $isRegistered = $eventModel->isUserRegistered($event['id'], $_SESSION['user']['id']);
            }

            // Add all information to the event data
            $event['days_remaining'] = $daysRemaining;
            $event['is_past'] = $isPast;
            $event['quota_info'] = $quotaInfo;
            $event['registration_closed'] = $registrationClosed;
            $event['event_ended'] = $eventEnded;
            $event['is_registered'] = $isRegistered;

            $processedEvents[] = $event;
        }

        $data = [
            'title' => 'Search Results: ' . htmlspecialchars($keyword),
            'events' => $processedEvents,
            'keyword' => htmlspecialchars($keyword)
        ];

        $this->appView('event/index', $data);
    }

    /**
     * Show the event creation form
     * Only accessible by admin users
     */
    public function create()
    {
        // Ensure only admin can access this method
        Middleware::isAdmin();

        $data = [
            'title' => 'Create Event',
            'categories' => $this->model('EventCategory')->getAll(),
        ];
        $this->dashboardView('dashboard/admin/event/event-create', $data);
    }

    /**
     * Process the event creation form submission
     * Only accessible by admin users
     */
    public function store()
    {
        // Ensure only admin can access this method
        Middleware::isAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/event/create');
            return;
        }

        $_SESSION['old_input'] = $_POST;

        // Validate form input
        $validator = new Validator();
        $validationRules = self::EVENT_VALIDATION_RULES;

        // Make thumbnail optional
        if (!isset($_FILES['thumbnail']) || empty($_FILES['thumbnail']['name']) || $_FILES['thumbnail']['error'] === UPLOAD_ERR_NO_FILE) {
            unset($validationRules['thumbnail']);
        }

        if (!$validator->validate($_POST, $validationRules)) {
            $_SESSION['validation_errors'] = $validator->getErrors();
            Flasher::setFlash('Validation failed', 'Please check your input', 'error');
            $this->redirect('/event/create');
            return;
        }

        // Convert date formats
        $inputData = $this->convertDateFormats($_POST);

        // Process thumbnail upload
        $thumbnailPath = $this->processThumbnailUpload($_FILES['thumbnail'] ?? null);
        if (isset($_SESSION['validation_errors']['thumbnail'])) {
            $this->redirect('/event/create');
            return;
        }

        // Create event in database
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

    /**
     * Show the event edit form
     * Only accessible by admin users
     */
    public function edit($id)
    {
        // Ensure only admin can access this method
        Middleware::isAdmin();

        $event = $this->model('Event')->getById($id);

        if (!$event) {
            Flasher::setFlash('Event not found', 'The event you are trying to edit does not exist', 'error');
            $this->redirect('/dashboard/event');
            return;
        }

        // Convert database date formats to form date formats
        $event = $this->convertDatabaseDatesToFormFormat($event);

        $data = [
            'title' => 'Edit Event',
            'categories' => $this->model('EventCategory')->getAll(),
            'event' => $event
        ];

        $this->dashboardView('dashboard/admin/event/event-edit', $data);
    }

    /**
     * Process the event update form submission
     * Only accessible by admin users
     */
    public function update($id)
    {
        // Ensure only admin can access this method
        Middleware::isAdmin();

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

        // Validate form input
        $validator = new Validator();
        $validationRules = self::EVENT_VALIDATION_RULES;

        // Make thumbnail optional on update
        if (!isset($_FILES['thumbnail']) || empty($_FILES['thumbnail']['name']) || $_FILES['thumbnail']['error'] === UPLOAD_ERR_NO_FILE) {
            unset($validationRules['thumbnail']);
        }

        if (!$validator->validate($_POST, $validationRules)) {
            $_SESSION['validation_errors'] = $validator->getErrors();
            Flasher::setFlash('Validation failed', 'Please check your input', 'error');
            $this->redirect("/event/edit/{$id}");
            return;
        }

        // Convert date formats
        $inputData = $this->convertDateFormats($_POST);

        // Process thumbnail upload or use existing
        $thumbnailPath = $existingEvent['thumbnail'];
        if (isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail']['name']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $thumbnailPath = $this->processThumbnailUpload($_FILES['thumbnail'], $existingEvent['thumbnail']);
            if (isset($_SESSION['validation_errors']['thumbnail'])) {
                $this->redirect("/event/edit/{$id}");
                return;
            }
        }

        // Update event in database
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

    /**
     * Delete an event
     * Only accessible by admin users
     */
    public function destroy($id)
    {
        // Ensure only admin can access this method
        Middleware::isAdmin();

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

    /**
     * Show the details of a specific event
     * Public access
     */
    public function show($id)
    {
        $eventModel = $this->model('Event');
        $event = $eventModel->getById($id);

        if (!$event) {
            $this->redirect('/event');
            return;
        }

        // Restrict access to unpublished events for non-admin users
        if ($event['is_published'] == 0) {
            if (!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'admin')) {
                $this->redirect('/event');
                return;
            }
        }

        // Get participant list for admin users
        $participants = [];
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $participants = $eventModel->getParticipants($id);
        }

        // Get event quota information
        $quotaInfo = $eventModel->checkQuota($id);

        // Check if current user is registered
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

    /**
     * Calculate priority score for an event to determine display order
     * Higher scores will be displayed first
     */
    private function calculateEventPriorityScore($isPast, $eventEnded, $daysRemaining, $quotaInfo, $registrationClosed, $registrationDeadline)
    {
        $priorityScore = 0;

        // Priority 1: Upcoming events (not past)
        if (!$isPast && !$eventEnded) {
            // Add high base score for upcoming events
            $priorityScore += 1000;

            // Priority 2: Events happening soon get higher priority
            if ($daysRemaining <= 3) {
                // Very soon (0-3 days) - highest urgency
                $priorityScore += 500 - ($daysRemaining * 50);
            } else if ($daysRemaining <= 7) {
                // This week (4-7 days)
                $priorityScore += 300 - ($daysRemaining * 20);
            } else if ($daysRemaining <= 14) {
                // Next week (8-14 days)
                $priorityScore += 200 - ($daysRemaining * 5);
            } else {
                // Later events
                $priorityScore += 100 - min(100, $daysRemaining);
            }

            // Priority 3: Events with limited seats get higher priority
            if ($quotaInfo && $quotaInfo['quota'] > 0) {
                $percentFull = ($quotaInfo['quota'] - $quotaInfo['available']) / $quotaInfo['quota'] * 100;

                if ($percentFull >= 90) {
                    // Almost full (90-100%)
                    $priorityScore += 400;
                } else if ($percentFull >= 75) {
                    // Getting full (75-89%)
                    $priorityScore += 300;
                } else if ($percentFull >= 50) {
                    // Half full (50-74%)
                    $priorityScore += 200;
                }

                // Add bonus for very few seats remaining (absolute number)
                if ($quotaInfo['available'] <= 5 && $quotaInfo['available'] > 0) {
                    $priorityScore += (6 - $quotaInfo['available']) * 50; // 5=50, 4=100, 3=150, 2=200, 1=250
                }
            }

            // Priority 4: Events with active registration period get priority
            if (!$registrationClosed) {
                $priorityScore += 100;

                // Check if registration deadline is approaching
                $regDeadline = strtotime($registrationDeadline);
                $daysToDeadline = ceil(($regDeadline - time()) / (60 * 60 * 24));

                if ($daysToDeadline <= 2) {
                    // Registration closing very soon
                    $priorityScore += 150;
                } else if ($daysToDeadline <= 5) {
                    // Registration closing soon
                    $priorityScore += 100;
                }
            }

            // Priority 5: Popular events with many participants get some priority
            if ($quotaInfo && isset($quotaInfo['registered']) && $quotaInfo['registered'] > 0) {
                // More popular events get slightly higher priority
                $popularityBoost = min(100, $quotaInfo['registered'] * 2);
                $priorityScore += $popularityBoost;
            }
        }

        return $priorityScore;
    }

    /**
     * Validate if an event is eligible for registration
     * This method is used by RegistrationController
     */
    public function validateEventRegistrationEligibility($eventModel, $id)
    {
        // Check if event exists
        $event = $eventModel->getById($id);
        if (!$event) {
            return [
                'title' => 'Event not found',
                'message' => 'The event you are trying to register for does not exist',
                'type' => 'error'
            ];
        }

        // Check if event is published
        if ($event['is_published'] != 1) {
            return [
                'title' => 'Registration failed',
                'message' => 'This event is not open for registration',
                'type' => 'error'
            ];
        }

        // Check if registration deadline has passed
        if (strtotime($event['registration_deadline']) < time()) {
            return [
                'title' => 'Registration closed',
                'message' => 'The registration deadline for this event has passed',
                'type' => 'error'
            ];
        }

        // Check if event has already ended
        if (strtotime($event['end_date'] . ' ' . $event['end_time']) < time()) {
            return [
                'title' => 'Event ended',
                'message' => 'This event has already ended',
                'type' => 'error'
            ];
        }

        // Check if user is already registered
        if ($eventModel->isUserRegistered($id, $_SESSION['user']['id'])) {
            return [
                'title' => 'Already registered',
                'message' => 'You are already registered for this event',
                'type' => 'warning'
            ];
        }

        // Check quota availability
        $quotaInfo = $eventModel->checkQuota($id);
        if (!$quotaInfo || $quotaInfo['available'] <= 0) {
            return [
                'title' => 'Registration failed',
                'message' => 'This event has reached its maximum capacity',
                'type' => 'error'
            ];
        }

        return true;
    }

    /**
     * Convert form date formats (m/d/Y) to database date formats (Y-m-d)
     */
    private function convertDateFormats($formData)
    {
        $inputData = $formData;
        $dateFieldsToConvert = ['start_date', 'end_date', 'registration_deadline'];

        foreach ($dateFieldsToConvert as $field) {
            if (!empty($inputData[$field])) {
                $dateObj = \DateTime::createFromFormat('m/d/Y', $inputData[$field]);
                if ($dateObj) {
                    $inputData[$field] = $dateObj->format('Y-m-d');
                }
            }
        }

        return $inputData;
    }

    /**
     * Convert database date formats (Y-m-d) to form date formats (m/d/Y)
     */
    private function convertDatabaseDatesToFormFormat($event)
    {
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

        return $event;
    }

    /**
     * Process thumbnail upload for events
     */
    private function processThumbnailUpload($fileData, $oldThumbnail = null)
    {
        if (!$fileData || empty($fileData['name']) || $fileData['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        $uploadDir = 'images/events/';

        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate unique filename
        $fileExtension = pathinfo($fileData['name'], PATHINFO_EXTENSION);
        $uniqueId = uniqid('event_', true);
        $fileName = $uniqueId . '.' . $fileExtension;
        $uploadPath = $uploadDir . $fileName;

        // Upload file
        if (move_uploaded_file($fileData['tmp_name'], $uploadPath)) {
            // Delete old thumbnail if exists and this is an update
            if (!empty($oldThumbnail)) {
                $oldThumbnailPath = $uploadDir . $oldThumbnail;
                if (file_exists($oldThumbnailPath)) {
                    unlink($oldThumbnailPath);
                }
            }
            return $fileName;
        } else {
            $_SESSION['validation_errors']['thumbnail'] = 'Failed to upload thumbnail';
            Flasher::setFlash('Thumbnail upload failed', 'Please try again', 'error');
            return '';
        }
    }
}
