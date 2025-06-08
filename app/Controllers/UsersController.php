<?php

namespace App\Controllers;

use Core\Controller;
use Core\Flasher;
use Core\Middleware;
use Core\Validator;

class UsersController extends Controller
{
    private const VALIDATION_RULES = [
        'username' => [
            'required',
            ['min', 4],
            ['max', 20],
            ['unique', ['table' => 'users', 'column' => 'username']]
        ],
        'full_name' => [
            'required',
            ['min', 4],
            ['max', 100]
        ],
        'email' => [
            'required',
            'email',
            ['max', 100],
            ['unique', ['table' => 'users', 'column' => 'email']]
        ],
        'password' => [
            'required',
            ['min', 8],
            ['max', 255]
        ],
        'password_confirmation' => [
            'required',
            ['min', 8],
            ['max', 255],
            ['matches', ['field_to_match' => 'password']]
        ]
    ];

    /**
     * UsersController constructor.
     * Ensures only authenticated users with admin role can access user management
     */
    public function __construct()
    {
        Middleware::isAdmin();
    }

    /**
     * Display the list of users
     * Admin only
     */
    public function index(){
       $this->redirect("/dashboard/users");
    }

    /**
     * Store a newly created user
     * Admin only
     */
    public function store()
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/users');
            return;
        }

        // Store old input in session for form repopulation if validation fails
        $_SESSION['old_input'] = $_POST;

        // Validate input
        $validator = new Validator();
        $validationRules = self::VALIDATION_RULES;

        if (!$validator->validate($_POST, $validationRules)) {
            $_SESSION['validation_errors'] = $validator->getErrors();
            $_SESSION['modal_open'] = true;
            Flasher::setFlash('Validation Error', 'Please check your input', 'error');
            $this->redirect('/dashboard/users');
            return;
        }

        // Prepare data for user creation
        $data = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'full_name' => $_POST['full_name'],
            'password' => $_POST['password'],
            'profile_picture' => '',
            'role' => $_POST['role'] ?? 'user'
        ];

        // Create user
        $userModel = $this->model('User');
        $result = $userModel->create($data);

        if ($result > 0) {
            unset($_SESSION['old_input']);
            $_SESSION['modal_open'] = false;
            Flasher::setFlash('Success', 'User created successfully', 'success');
            $this->redirect('/dashboard/users');
        } else {
            $_SESSION['modal_open'] = true;
            Flasher::setFlash('Error', 'Failed to create user. Please try again.', 'error');
            $this->redirect('/dashboard/users');
        }
    }

    /**
     * Show the form for editing a user
     * Admin only
     */
    public function edit($id)
    {
        $userModel = $this->model('User');
        $userToEdit = $userModel->getUserById($id);

        if (!$userToEdit) {
            Flasher::setFlash('User not found', 'The user you are trying to edit does not exist', 'error');
            $this->redirect('/dashboard/users');
            exit;
        }

        $data = [
            'title' => 'Edit User',
            'userToEdit' => $userToEdit,
            'user' => [
                'username' => $_SESSION['user']['username'],
                'email' => $_SESSION['user']['email'],
                'full_name' => $_SESSION['user']['full_name']
            ]
        ];

        $this->dashboardView('dashboard/admin/user/user-edit', $data);
    }

    /**
     * Update an existing user
     * Admin only
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard/users');
            return;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            Flasher::setFlash('Error', 'User ID is missing', 'error');
            $this->redirect('/dashboard/users');
            return;
        }

        $userModel = $this->model('User');
        $userToUpdate = $userModel->getUserById($id);

        if (!$userToUpdate) {
            Flasher::setFlash('Error', 'User not found', 'error');
            $this->redirect('/dashboard/users');
            return;
        }

        // Store old input in session for form repopulation if validation fails
        $_SESSION['old_input'] = $_POST;

        // Customize validation rules for update
        $validationRules = [
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
            ],
            'role' => [
                'required'
            ]
        ];

        // Add unique rules only if username or email has changed
        if ($userToUpdate['username'] !== $_POST['username']) {
            $validationRules['username'][] = ['unique', ['table' => 'users', 'column' => 'username']];
        }
        if ($userToUpdate['email'] !== $_POST['email']) {
            $validationRules['email'][] = ['unique', ['table' => 'users', 'column' => 'email']];
        }

        // Only validate password if it's provided
        if (!empty($_POST['password'])) {
            $validationRules['password'] = [
                ['min', 8],
                ['max', 255]
            ];
            $validationRules['password_confirmation'] = [
                'required',
                ['min', 8],
                ['max', 255],
                ['matches', ['field_to_match' => 'password']]
            ];
        }

        $validator = new Validator();
        if (!$validator->validate($_POST, $validationRules)) {
            $_SESSION['validation_errors'] = $validator->getErrors();
            Flasher::setFlash('Validation Error', 'Please check your input', 'error');
            $this->redirect('/users/edit/' . $id);
            return;
        }

        // Check if any data has actually changed
        $hasChanges = false;
        if ($userToUpdate['username'] !== $_POST['username'] ||
            $userToUpdate['email'] !== $_POST['email'] ||
            $userToUpdate['full_name'] !== $_POST['full_name'] ||
            $userToUpdate['role'] !== $_POST['role'] ||
            !empty($_POST['password'])) {
            $hasChanges = true;
        }

        // If no changes, inform the user and redirect
        if (!$hasChanges) {
            Flasher::setFlash('Info', 'No changes detected to update', 'info');
            $this->redirect('/users/edit/' . $id);
            return;
        }

        $data = [
            'id' => $id,
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'full_name' => $_POST['full_name'],
            'role' => $_POST['role']
        ];

        // Handle password update if provided
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        $result = $userModel->updateUser($data);
        if ($result) {
            unset($_SESSION['old_input']);
            unset($_SESSION['validation_errors']);
            Flasher::setFlash('Success', 'User updated successfully', 'success');
            $this->redirect('/dashboard/users');
        } else {
            Flasher::setFlash('Error', 'Failed to update user', 'error');
            $this->redirect('/users/edit/' . $id);
        }
    }

    /**
     * Delete a user
     * Admin only
     */
    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard/users');
            return;
        }

        // Prevent deleting yourself
        if ($id == $_SESSION['user']['id']) {
            Flasher::setFlash('Error', 'You cannot delete your own account', 'error');
            $this->redirect('/dashboard/users');
            return;
        }

        $userModel = $this->model('User');
        if ($userModel->deleteUser($id)) {
            Flasher::setFlash('Success', 'User deleted successfully', 'success');
        } else {
            Flasher::setFlash('Error', 'Failed to delete user', 'error');
        }

        $this->redirect('/dashboard/users');
    }

    /**
     * Show the details of a specific user
     * Admin only
     */
    public function show($id)
    {
        $userModel = $this->model('User');
        $userProfile = $userModel->getUserById($id);

        if (!$userProfile) {
            Flasher::setFlash('Error', 'User not found', 'error');
            $this->redirect('/dashboard/users');
            return;
        }

        // Get user's event registrations
        $registrationModel = $this->model('Registration');
        $registrations = $registrationModel->getUserRegistrations($id);

        $data = [
            'title' => 'User Details',
            'userProfile' => $userProfile,
            'registrations' => $registrations,
            'user' => $_SESSION['user']
        ];

        $this->dashboardView('dashboard/admin/user/user-show', $data);
    }
}
