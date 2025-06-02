<?php

namespace App\Controllers;

use Core\Controller;
use Core\Validator;
use Core\Flasher;
use Core\Middleware;

class LoginController extends Controller
{
    private const VALIDATION_RULES = [
        'username' => ['required'],
        'password' => ['required']
    ];

    public function __construct() {
        Middleware::isGuest();
    }

    public function index()
    {
        $this->authView('/auth/login');
    }

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

        // Menggunakan method model dari parent Controller
        $userModel = $this->model('User');
        $user = $userModel->getUserByUsername($_POST['username']);

        if (!$user || !password_verify($_POST['password'], $user['password'])) {
            Flasher::setFlash('Login failed', 'Invalid username or password', 'error');
            $this->redirect('/login');
            return;
        }

        // Set session untuk user yang berhasil login - dengan struktur array
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

