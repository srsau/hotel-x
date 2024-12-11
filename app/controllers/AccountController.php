<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;

class AccountController
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        $view = __DIR__ . '/../views/account.php';
        require __DIR__ . '/../views/layout.php';
    }
}