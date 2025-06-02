<?php
namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->appView('home', [
            'title' => 'Home',
        ]);
    }
}
