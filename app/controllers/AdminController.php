<?php

namespace App\Controllers;

use app\Middleware\AuthMiddleware;

class AdminController
{
    public function __construct()
    {
        AuthMiddleware::handle();
        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: /');
            exit();
        }
    }

    public function index()
    {
        $title = 'Admin Dashboard';
        $view = __DIR__ . '/../views/admin.php';
        require __DIR__ . '/../views/layout.php';
    }
}
