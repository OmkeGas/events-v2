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
        // Only admin can access user management
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('Access Denied', 'You do not have permission to access this page', 'error');
            $this->redirect('');
            exit;
        }
    }

    public function index()
    {
        $userModel = $this->model('User');
        $users = $userModel->getAll();

        $data = [
            'title' => 'User Management',
            'users' => $users,
            'user' => [
                'username' => $_SESSION['user']['username'],
                'email' => $_SESSION['user']['email'],
                'full_name' => $_SESSION['user']['full_name']
            ]
        ];

        $this->dashboardView('dashboard/admin/users/index', $data);
    }

    public function edit($id)
    {
        $userModel = $this->model('User');
        $userToEdit = $userModel->getUserById($id);

        if (!$userToEdit) {
            Flasher::setFlash('User not found', 'The user you are trying to edit does not exist', 'error');
            $this->redirect('/users');
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

        $this->dashboardView('/users/edit', $data);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/users');
            return;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            Flasher::setFlash('Error', 'User ID is missing', 'error');
            $this->redirect('/users');
            return;
        }

        $userModel = $this->model('User');
        $userToUpdate = $userModel->getUserById($id);

        if (!$userToUpdate) {
            Flasher::setFlash('Error', 'User not found', 'error');
            $this->redirect('/users');
            return;
        }

        // Validate unique constraints only if username or email has changed
        $uniqueRules = [];
        if ($userToUpdate['username'] !== $_POST['username']) {
            $uniqueRules['username'] = ['unique', ['table' => 'users', 'column' => 'username']];
        }
        if ($userToUpdate['email'] !== $_POST['email']) {
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

        // Only update password if provided
        if (!empty($_POST['password'])) {
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
            Flasher::setFlash('Success', 'User updated successfully', 'success');
        } else {
            Flasher::setFlash('Error', 'Failed to update user', 'error');
        }

        $this->redirect('/users');
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/users');
            return;
        }

        // Prevent deleting yourself
        if ($id == $_SESSION['user']['id']) {
            Flasher::setFlash('Error', 'You cannot delete your own account', 'error');
            $this->redirect('/users');
            return;
        }

        $userModel = $this->model('User');
        if ($userModel->deleteUser($id)) {
            Flasher::setFlash('Success', 'User deleted successfully', 'success');
        } else {
            Flasher::setFlash('Error', 'Failed to delete user', 'error');
        }

        $this->redirect('/users');
    }
}
