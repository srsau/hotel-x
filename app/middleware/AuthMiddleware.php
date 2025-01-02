<?php

namespace App\Middleware;

class AuthMiddleware
{
    public static function handle()
    {
        if (!isset($_SESSION['user']) || empty($_SESSION['user']['id']) || empty($_SESSION['user']['email'])) {
            header('Location: /login');
            exit();
        }
    }
}