<?php

namespace App\Controllers;

use app\models\Room;

class HomeController
{
    public function index()
    {
        $rooms = Room::getAllRooms();

        $title = 'Home - Hotel X';
        $view = __DIR__ . '/../views/home.php';
        require __DIR__ . '/../views/layout.php';
    }
}
