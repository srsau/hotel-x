<?php

namespace App\Models;

use app\Database;
use PDO;
use Exception;

class Room
{
    private static function validateRoomData($name, $description, $price_per_night, $capacity, $floor, $available_rooms)
    {
        if (empty($name) || empty($description) || empty($price_per_night) || empty($capacity) || empty($floor) || empty($available_rooms)) {
            throw new Exception("All fields are required.");
        }

        if (!is_numeric($price_per_night) || !is_numeric($capacity) || !is_numeric($floor) || !is_numeric($available_rooms)) {
            throw new Exception("Price per night, capacity, floor, and available rooms must be numeric.");
        }
    }

    public static function getAllRooms()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT rooms.*, GROUP_CONCAT(facilities.name SEPARATOR ', ') AS facilities
            FROM rooms
            LEFT JOIN room_facilities ON rooms.id = room_facilities.room_id
            LEFT JOIN facilities ON room_facilities.facility_id = facilities.id
            GROUP BY rooms.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRoomById($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM rooms WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function createRoom($name, $description, $image_url, $images, $price_per_night, $capacity, $floor, $popular, $available_rooms)
    {
        self::validateRoomData($name, $description, $price_per_night, $capacity, $floor, $available_rooms);

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO rooms (name, description, image_url, images, price_per_night, capacity, floor, popular, available_rooms) VALUES (:name, :description, :image_url, :images, :price_per_night, :capacity, :floor, :popular, :available_rooms)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
        $stmt->bindParam(':images', $images, PDO::PARAM_STR);
        $stmt->bindParam(':price_per_night', $price_per_night, PDO::PARAM_STR);
        $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
        $stmt->bindParam(':floor', $floor, PDO::PARAM_INT);
        $stmt->bindParam(':popular', $popular, PDO::PARAM_INT);
        $stmt->bindParam(':available_rooms', $available_rooms, PDO::PARAM_INT);
        $stmt->execute();
        return $db->lastInsertId();
    }

    public static function updateRoom($id, $name, $description, $image_url, $images, $price_per_night, $capacity, $floor, $popular, $available_rooms)
    {
        self::validateRoomData($name, $description, $price_per_night, $capacity, $floor, $available_rooms);

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE rooms SET name = :name, description = :description, image_url = :image_url, images = :images, price_per_night = :price_per_night, capacity = :capacity, floor = :floor, popular = :popular, available_rooms = :available_rooms WHERE id = :id");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
        $stmt->bindParam(':images', $images, PDO::PARAM_STR);
        $stmt->bindParam(':price_per_night', $price_per_night, PDO::PARAM_STR);
        $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
        $stmt->bindParam(':floor', $floor, PDO::PARAM_INT);
        $stmt->bindParam(':popular', $popular, PDO::PARAM_INT);
        $stmt->bindParam(':available_rooms', $available_rooms, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public static function deleteRoom($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM rooms WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public static function getRoomFacilities($roomId)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT facilities.name
            FROM facilities
            JOIN room_facilities ON facilities.id = room_facilities.facility_id
            WHERE room_facilities.room_id = :room_id
        ");
        $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


    public static function getAvailableRooms($startDate, $endDate, $guests)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT 
                r.id, 
                r.name, 
                r.description, 
                r.image_url, 
                r.price_per_night, 
                r.capacity, 
                r.floor, 
                r.available_rooms - IFNULL((
                    SELECT COUNT(*)
                    FROM bookings b
                    WHERE b.room_id = r.id
                    AND b.check_in_date < :end_date 
                    AND b.check_out_date > :start_date
                    AND b.status = 'valid'
                ), 0) AS available,
                GROUP_CONCAT(DISTINCT f.name SEPARATOR ', ') AS facilities
            FROM 
                rooms r
            LEFT JOIN room_facilities rf ON r.id = rf.room_id
            LEFT JOIN facilities f ON rf.facility_id = f.id
            WHERE 
                r.capacity >= :guests
            GROUP BY 
                r.id
            HAVING 
                available > 0;
        ");
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->bindParam(':guests', $guests, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
