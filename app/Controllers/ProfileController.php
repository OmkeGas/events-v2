<?php

namespace App\Controllers;

use Core\Controller;
use Core\Flasher;
use Core\Middleware;
use Core\Validator;

class ProfileController extends Controller
{
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

    public function __construct()
    {
        Middleware::isAuth();
    }

    public function index()
    {
        $userModel = $this->model('User');
        $user = $userModel->getUserById($_SESSION['user']['id']);

        $data = [
            'title' => 'My Profile',
            'userProfile' => $user,
            'user' => [
                'username' => $_SESSION['user']['username'],
                'email' => $_SESSION['user']['email'],
                'full_name' => $_SESSION['user']['full_name']
            ]
        ];

        $this->dashboardView('dashboard/profile/index', $data);
    }

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

        if (!$validator->validate($_POST, $validationRules)) {
            $_SESSION['validation_errors'] = $validator->getErrors();
            $_SESSION['old_input'] = $_POST;
            Flasher::setFlash('Validation Error', 'Please check your input', 'error');
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
            // Validate password fields
            if ($_POST['password'] !== $_POST['password_confirmation']) {
                $_SESSION['validation_errors'] = ['password_confirmation' => ['Passwords do not match']];
                $_SESSION['old_input'] = $_POST;
                Flasher::setFlash('Validation Error', 'Passwords do not match', 'error');
                $this->redirect('/dashboard/profile');
                return;
            }
            $data['password'] = $_POST['password'];
        }

        // Handle profile picture upload
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
            $uploadDir = 'public/images/profiles/';
            $fileName = uniqid('profile_') . '.' . pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $uploadPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                $data['profile_picture'] = $fileName;
            }
        }

        if ($userModel->updateUser($data)) {
            // Update session data
            $_SESSION['user'] = $userModel->getUserById($id);

            Flasher::setFlash('Success', 'Profile updated successfully', 'success');
        } else {
            Flasher::setFlash('Error', 'Failed to update profile', 'error');
        }

        $this->redirect('/dashboard/profile');
    }
}
