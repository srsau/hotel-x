<?php

namespace App\Controllers;

use app\models\Room;

class HomeController
{
    public function index()
    {
        $rooms = Room::getAllRooms();

        $title = 'Home - Hotel X';
        $keywords = ['vacanta', 'hotel', 'camere libere la hotel', 'Hotel X',  'camere de lux', 'camere ieftine'];
        $description = 'Hotel X, cele mai bune camere pentru vacanța dvs.';
        $view = __DIR__ . '/../views/home.php';
        require __DIR__ . '/../views/layout.php';
    }
}
