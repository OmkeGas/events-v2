<?php

/**
 * Load environment variables from .env file
 */
function env($key, $default = null) {
    static $env = null;
    if ($env === null) {
        $env = [];
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (strpos(trim($line), '#') === 0 || trim($line) === '' || strpos($line, '=') === false) continue;
                list($k, $v) = explode('=', $line, 2);
                $env[trim($k)] = trim($v);
            }
        }
    }
    return isset($env[$key]) ? $env[$key] : $default;
}

/**
 * Base URL
 */
define("BASE_URL", env('APP_URL', 'http://localhost/event/public'));

/**
 * Database
 */
define("DB_HOST", env('DB_HOST', 'localhost'));
define("DB_USER", env('DB_USER', 'root'));
define("DB_PASS", env('DB_PASS', ''));
define("DB_NAME", env('DB_NAME', 'events'));
