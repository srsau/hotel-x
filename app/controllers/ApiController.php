<?php

namespace App\Controllers;

use app\models\Room;
use app\models\Addon;

class ApiController
{
    public function getAvailableRooms()
    {
        header('Content-Type: application/json');
        $startDate = $_GET['start_date'];
        $endDate = $_GET['end_date'];
        $guests = $_GET['guests'];
        $rooms = Room::getAvailableRooms($startDate, $endDate, $guests);
        echo json_encode(['rooms' => $rooms]);
    }

    public function getAddons()
    {
        header('Content-Type: application/json');
        $addons = Addon::getAllAddons();
        echo json_encode(['addons' => $addons]);
    }

    public function getRoomById()
    {
        header('Content-Type: application/json');
        $id = $_GET['id'];
        $room = Room::getRoomById($id);
        if ($room) {
            $room['facilities'] = Room::getRoomFacilities($id);
            echo json_encode(['room' => $room]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Room not found']);
        }
    }
}
?>
