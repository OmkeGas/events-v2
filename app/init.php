<?php
date_default_timezone_set('Asia/Jakarta');

spl_autoload_register(function ($class) {
    error_log("Trying to autoload: " . $class);
    
    if (strpos($class, 'Core\\') === 0) {
        $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
        error_log("Looking for Core class at: " . $file);
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    if (strpos($class, 'App\\Controllers\\') === 0) {
        $file = __DIR__ . '/Controllers/' . substr(str_replace('App\\Controllers\\', '', $class), 0) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    if (strpos($class, 'App\\Models\\') === 0) {
        $file = __DIR__ . '/Models/' . substr(str_replace('App\\Models\\', '', $class), 0) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }

    $path = __DIR__ . '/../';
    $class = str_replace('\\', '/', $class);
    $file = $path . $class . '.php';
    
    error_log("Looking for class at: " . $file);
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
});

// Constants
require_once 'Core/helpers.php';
require_once 'Config/app.php';
