<?php
namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        $this->appView('home', [
            'title' => 'Home',
        ]);
    }
}
