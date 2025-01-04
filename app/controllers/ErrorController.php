<?php

namespace App\Controllers;

class ErrorController
{
    public function notFound()
    {
        $view = __DIR__ . '/../views/404.php';
        $title = 'Pagina Nu A Fost Găsită';
        require __DIR__ . '/../views/layout.php';
    }
}
