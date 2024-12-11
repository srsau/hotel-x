<?php

namespace App\Controllers;

class ContactController
{
    public function contact()
    {
        $view = __DIR__ . '/../views/contact.php';
        require __DIR__ . '/../views/layout.php';
    }
}