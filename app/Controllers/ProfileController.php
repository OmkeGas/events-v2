<?php

namespace App\Controllers;

use Core\Controller;
use Core\Flasher;
use Core\Middleware;
use Core\Validator;

/**
 * DashboardController handles the dashboard functionalities for both admin and regular users
 * It provides methods to view the dashboard, user profile, update profile.
 */
class ProfileController extends Controller
{
    /**
     * Validation rules for user profile fields
     * These rules are used to validate user input when updating their profile
     */
    private const VALIDATION_RULES = [
        'username' => [
            'required',
            ['min', 4],
            ['max', 20]
        ],
        'full_name' => [
            'required',
            ['min', 4],
            ['max', 100]
        ],
        'email' => [
            'required',
            'email',
            ['max', 100]
        ]
    ];

    /**
     * ProfileController constructor.
     * Ensures user is authenticated before accessing profile methods
     */
    public function __construct()
    {
        Middleware::isAuth();
    }

    /**
     * Display the user profile
     */
    public function index()
    {
        $this->redirect('/dashboard/profile');
    }

    /**
     * Update user profile
     * Validates input, checks for unique constraints, and updates the user profile
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard/profile');
            return;
        }

        $id = $_SESSION['user']['id'];

        $userModel = $this->model('User');
        $currentUser = $userModel->getUserById($id);

        // Validate unique constraints only if username or email has changed
        $uniqueRules = [];
        if ($currentUser['username'] !== $_POST['username']) {
            $uniqueRules['username'] = ['unique', ['table' => 'users', 'column' => 'username']];
        }
        if ($currentUser['email'] !== $_POST['email']) {
            $uniqueRules['email'] = ['unique', ['table' => 'users', 'column' => 'email']];
        }

        $validator = new Validator();
        $validationRules = self::VALIDATION_RULES;

        // Add unique rules if username or email has changed
        foreach ($uniqueRules as $field => $rule) {
            $validationRules[$field][] = $rule;
        }

        // Only validate password if it's provided
        if (!empty($_POST['password'])) {
            $validationRules['password'] = [
                ['min', 8],
                ['max', 255]
            ];
            $validationRules['password_confirmation'] = [
                'required',
                ['matches', ['field_to_match' => 'password']]
            ];
        }

        if (!$validator->validate($_POST, $validationRules)) {
            $_SESSION['validation_errors'] = $validator->getErrors();
            $_SESSION['old_input'] = $_POST;
            Flasher::setFlash('Validation Error', 'Please check your input', 'error');
            $this->redirect('/dashboard/profile');
            return;
        }

        // Check if any data has actually changed
        $hasChanges = false;
        if ($currentUser['username'] !== $_POST['username'] ||
            $currentUser['email'] !== $_POST['email'] ||
            $currentUser['full_name'] !== $_POST['full_name'] ||
            !empty($_POST['password']) ||
            (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0)) {
            $hasChanges = true;
        }

        // If no changes, inform the user and redirect
        if (!$hasChanges) {
            Flasher::setFlash('Info', 'No changes detected to update', 'info');
            $this->redirect('/dashboard/profile');
            return;
        }

        $data = [
            'id' => $id,
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'full_name' => $_POST['full_name']
        ];

        // Only update password if provided
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        if ($userModel->updateUser($data)) {
            unset($_SESSION['old_input']);
            unset($_SESSION['validation_errors']);
            // Update session data with new user info
            $_SESSION['user'] = $userModel->getUserById($id);
            Flasher::setFlash('Success', 'Profile updated successfully', 'success');
        } else {
            Flasher::setFlash('Error', 'Failed to update profile', 'error');
        }
        $this->redirect('/dashboard/profile');
    }
}
