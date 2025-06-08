<?php
namespace Core;
class Middleware
{
    public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] === 'admin') {
                header('Location: ' . BASE_URL . '/dashboard');
            } else {
                header('Location: ' . BASE_URL . '/dashboard');
            }
            exit;
        }
    }

    public static function isAuth()
    {
        if (!isset($_SESSION['user'])) {
            Flasher::setFlash('Login required', 'You need to login first to access this page', 'warning');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function isAdmin()
    {
        self::isAuth();
        if ($_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('Access Denied', 'You do not have permission to access this page', 'error');
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }

    public static function isUser()
    {
        self::isAuth();
        if ($_SESSION['user']['role'] !== 'user') {
            Flasher::setFlash('Access Denied', 'You do not have permission to access this page', 'error');
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
}
