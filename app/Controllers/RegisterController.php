<?php

namespace App\Controllers;

use Core\Controller;
use Core\Validator;
use Core\Flasher;
use Core\Middleware;

/**
 * RegisterController handles user registration functionality.
 */
class RegisterController extends Controller
{
    /**
     * Validation rules for the registration form.
     * These rules ensure that the input meets the required criteria.
     */
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
     * RegisterController constructor.
     * Ensures that only guests can access the registration page.
     */
    public function __construct()
    {
       Middleware::isGuest();
    }

    /**
     * Display the registration page.
     */
    public function index(): void
    {
        $this->authView('/auth/register');
    }

    /**
     * Handle the registration form submission.
     * Validates input, creates a new user, and redirects accordingly.
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }

        $_SESSION['old_input'] = $_POST;

        $validator = new Validator();

        if (!$validator->validate($_POST, self::VALIDATION_RULES)) {
            $_SESSION['validation_errors'] = $validator->getErrors();
            Flasher::setFlash('Validation failed', 'Please check your input', 'error');
            $this->redirect('/register');
        }

        $userModel = $this->model('User');

        $data = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'full_name' => $_POST['full_name'] ?? '',
            'password' => $_POST['password'],
            'profile_picture' => ''
        ];

        $result = $userModel->create($data);
        if ($result > 0) {
            unset($_SESSION['old_input']);
            Flasher::setFlash('Registration successful', 'You can now log in', 'success');
            $this->redirect('/login');
        } else {
            Flasher::setFlash('Registration failed', 'Please try again', 'error');
            $this->redirect('/register');
        }
    }

}

