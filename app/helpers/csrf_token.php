<?php

namespace app\helpers;

class Csrf
{
    public static function generateToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function validateToken($token): bool
    {
        if(!$token) {
            return false;
        }
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function regenerateToken(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

?>