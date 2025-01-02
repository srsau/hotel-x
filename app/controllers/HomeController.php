<?php

namespace App\Controllers;

use app\Database;
use PDO;

class HomeController
{
    public function index()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT rooms.id, rooms.name, rooms.description, rooms.image_url, rooms.capacity, GROUP_CONCAT(facilities.name SEPARATOR ', ') as facilities, rooms.price_per_night FROM rooms LEFT JOIN room_facilities ON rooms.id = room_facilities.room_id LEFT JOIN facilities ON room_facilities.facility_id = facilities.id GROUP BY rooms.id");
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Home - Hotel X';
        $view = __DIR__ . '/../views/home.php';
        require __DIR__ . '/../views/layout.php';
    }
}
