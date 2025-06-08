<?php

namespace App\Controllers;

use Core\Controller;
use Core\Validator;
use Core\Flasher;
use Core\Middleware;

/**
 * LoginController handles user login functionality.
 * It validates login credentials and manages user sessions.
 */
class LoginController extends Controller
{
    /**
     * Validation rules for login form.
     */
    private const VALIDATION_RULES = [
        'username' => ['required'],
        'password' => ['required']
    ];

    /**
     * LoginController constructor.
     * Ensures that only guests can access the login page.
     */
    public function __construct() {
        Middleware::isGuest();
    }

    /**
     * Display the login page.
     */
    public function index()
    {
        $this->authView('/auth/login');
    }

    /**
     * Handle the login form submission.
     * Validates input, checks user credentials, and starts a session if successful.
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        $_SESSION['old_input'] = $_POST;

        $validator = new Validator();

        if (!$validator->validate($_POST, self::VALIDATION_RULES)) {
            $_SESSION['validation_errors'] = $validator->getErrors();
            Flasher::setFlash('Login failed', 'Please check your input', 'error');
            $this->redirect('/login');
            return;
        }

        $userModel = $this->model('User');
        $user = $userModel->getUserByUsername($_POST['username']);

        if (!$user || !password_verify($_POST['password'], $user['password'])) {
            Flasher::setFlash('Login failed', 'Invalid username or password', 'error');
            $this->redirect('/login');
            return;
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'role' => $user['role']
        ];

        unset($_SESSION['old_input']);

        Flasher::setFlash('Welcome back!', $user['full_name'], 'success');
        $this->redirect('/dashboard');
    }
}

