<?php

namespace App\Controllers;

use app\Database;
use PDO;

class RoomController
{
    public function details()
    {
        if (!isset($_GET['id'])) {
            header('Location: /404');
            exit();
        }

        $roomId = $_GET['id'];
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT rooms.*, GROUP_CONCAT(facilities.name SEPARATOR ', ') as facilities FROM rooms LEFT JOIN room_facilities ON rooms.id = room_facilities.room_id LEFT JOIN facilities ON room_facilities.facility_id = facilities.id WHERE rooms.id = :id GROUP BY rooms.id");
        $stmt->bindParam(':id', $roomId);
        $stmt->execute();
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            header('Location: /404');
            exit();
        }

        $room['images'] = json_decode($room['images'], true);

        $title = 'Room Details - ' . htmlspecialchars($room['name']);
        $view = __DIR__ . '/../views/room_details.php';
        require __DIR__ . '/../views/layout.php';
    }
}
