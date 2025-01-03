<?php

namespace App\Models;

use app\Database;
use PDO;

class Facility
{
    public static function getAllFacilities()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM facilities");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getFacilitiesByRoomId($roomId)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT facility_id FROM room_facilities WHERE room_id = :room_id");
        $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function updateFacilities($roomId, $facilities)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM room_facilities WHERE room_id = :room_id");
        $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $stmt->execute();

        foreach ($facilities as $facilityId) {
            $stmt = $db->prepare("INSERT INTO room_facilities (room_id, facility_id) VALUES (:room_id, :facility_id)");
            $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);
            $stmt->bindParam(':facility_id', $facilityId, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}
?>
