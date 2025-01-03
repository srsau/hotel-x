<?php

namespace App\Models;

use app\Database;
use PDO;

class Booking
{
    public static function createBooking($userId, $roomId, $checkInDate, $checkOutDate, $addons, $totalPrice)
    {
        $db = Database::getInstance()->getConnection();
        $encodedAddons = json_encode($addons); 
        $stmt = $db->prepare("INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, addons, total_price, status, created_at) VALUES (:user_id, :room_id, :check_in_date, :check_out_date, :addons, :total_price, 'valid', NOW())");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $stmt->bindParam(':check_in_date', $checkInDate);
        $stmt->bindParam(':check_out_date', $checkOutDate);
        $stmt->bindParam(':addons', $encodedAddons); 
        $stmt->bindParam(':total_price', $totalPrice);
        $stmt->execute();
    }

    public static function getBookingsByUserId($userId)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT bookings.*, rooms.name as room_name FROM bookings JOIN rooms ON bookings.room_id = rooms.id WHERE bookings.user_id = :user_id AND bookings.status = 'valid'");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getBookingByIdAndUserId($bookingId, $userId)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT bookings.*, rooms.name as room_name, users.name as user_name, (SELECT GROUP_CONCAT(addons.name SEPARATOR ', ') FROM addons WHERE JSON_CONTAINS(bookings.addons, JSON_QUOTE(CAST(addons.id AS CHAR(10))))) as addon_names FROM bookings JOIN rooms ON bookings.room_id = rooms.id JOIN users ON bookings.user_id = users.id WHERE bookings.id = :booking_id AND bookings.user_id = :user_id");
        $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getBookingsWithAddonsByUserId($userId)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT 
                bookings.*, 
                rooms.name as room_name, 
                (SELECT GROUP_CONCAT(addons.name SEPARATOR ', ') 
                 FROM addons 
                 WHERE JSON_CONTAINS(bookings.addons, JSON_QUOTE(CAST(addons.id AS CHAR(10))))) as addon_names
            FROM bookings
            JOIN rooms ON bookings.room_id = rooms.id
            WHERE bookings.user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllBookings()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT 
                bookings.*, 
                rooms.name as room_name, 
                users.name as user_name, 
                users.email as user_email, 
                (SELECT GROUP_CONCAT(addons.name SEPARATOR ', ') 
                 FROM addons 
                 WHERE JSON_CONTAINS(bookings.addons, JSON_QUOTE(CAST(addons.id AS CHAR(10))))) as addon_names
            FROM bookings
            JOIN rooms ON bookings.room_id = rooms.id
            JOIN users ON bookings.user_id = users.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function cancelBookingByAdmin($bookingId, $reason)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE bookings SET status = 'canceled' WHERE id = :booking_id");
        $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch user email
        $stmt = $db->prepare("SELECT users.email FROM bookings JOIN users ON bookings.user_id = users.id WHERE bookings.id = :booking_id");
        $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
        $stmt->execute();
        $userEmail = $stmt->fetchColumn();

        return $userEmail;
    }
}
?>