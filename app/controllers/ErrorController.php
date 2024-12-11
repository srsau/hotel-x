<?php

namespace App\Controllers;

class ErrorController
{
    public function notFound()
    {
        $view = __DIR__ . '/../views/404.php';
        require __DIR__ . '/../views/layout.php';
    }
}
