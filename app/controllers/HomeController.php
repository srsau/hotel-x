<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        $view = __DIR__ . '/../views/home.php';
        require __DIR__ . '/../views/layout.php';
    }
}
