<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Include the initialization file
require_once __DIR__ . '/../app/init.php';

// Create a new App instance
$app = new Core\App();
