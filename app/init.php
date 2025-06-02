<?php

// Autoloader
spl_autoload_register(function ($class) {
    // Debug
    error_log("Trying to autoload: " . $class);
    
    // Special handling for Core namespace - it's in the app directory
    if (strpos($class, 'Core\\') === 0) {
        $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
        error_log("Looking for Core class at: " . $file);
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    // Normal autoloading for other namespaces
    $path = __DIR__ . '/../';
    $class = str_replace('\\', '/', $class);
    $file = $path . $class . '.php';
    
    error_log("Looking for class at: " . $file);
    // Check if file exists
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
});

// Constants
require_once 'Core/helpers.php';
require_once 'Config/app.php';
