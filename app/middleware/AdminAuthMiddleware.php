<?php

namespace app\middleware;

class AdminAuthMiddleware
{
    public static function ensureAdmin()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: /404');
            exit();
        }
    }
}
?>
