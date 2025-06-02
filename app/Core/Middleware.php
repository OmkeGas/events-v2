<?php
namespace Core;
class Middleware
{
    public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] === 'admin') {
                header('Location: ' . BASE_URL . '/dashboard/admin');
            } else {
                header('Location: ' . BASE_URL . '/dashboard/user');
            }
            exit;
        }
    }

    public static function isAuth()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function isAdmin()
    {
        self::isAuth();
        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/dashboard/user');
            exit;
        }
    }

    public static function isUser()
    {
        self::isAuth();
        if ($_SESSION['user']['role'] !== 'user') {
            header('Location: ' . BASE_URL . '/dashboard/admin');
            exit;
        }
    }
}
