<?php

namespace App\Controllers;

class AboutController
{
    public function about()
    {
        $view = __DIR__ . '/../views/about.php';
        require __DIR__ . '/../views/layout.php';
    }
}
