<?php

namespace App\Controllers;

use app\models\Addon;
use app\models\Booking;
use app\models\Room;
use Exception;

class BookingController
{
    public function finalizeBooking()
    {
        try {

            if (!isset($_SESSION['user']['id'])) {
                throw new Exception("User not logged in.");
            }

            $userId = $_SESSION['user']['id'];
            $bookingData = json_decode(file_get_contents('php://input'), true);

            $roomId = $bookingData['roomId'];
            $checkInDate = $bookingData['startDate'];
            $checkOutDate = $bookingData['endDate'];
            $addons = !empty($bookingData['addons']) ? $bookingData['addons'] : []; // Ensure addons is an array
            $totalPrice = $this->calculateTotalPrice($roomId, $checkInDate, $checkOutDate, $addons);

            Booking::createBooking($userId, $roomId, $checkInDate, $checkOutDate, $addons, $totalPrice);

            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function calculateTotalPrice($roomId, $checkInDate, $checkOutDate, $addons)
    {
        $room = Room::getRoomById($roomId);
        $nights = (new \DateTime($checkOutDate))->diff(new \DateTime($checkInDate))->days;
        $roomPrice = $room['price_per_night'] * $nights;

        $addonsPrice = 0;
        foreach ($addons as $addonId) {
            $addon = Addon::getAddonById($addonId);
            $addonsPrice += $addon['price'];
        }

        return $roomPrice + $addonsPrice;
    }

    public function book()
    {
        $script = '
        <script>
            window.hotelx_uname = ' . (isset($_SESSION['user']['name']) ? '"' . $_SESSION['user']['name'] . '"' : 'null') . ';
            window.hotelx_preferred_currency = ' . (isset($_SESSION['preferred_currency']) ? '"' . $_SESSION['preferred_currency'] . '"' : 'null') . ';
        </script>
        <script src="/js/booking.js"></script>
    ';
    
        $view = __DIR__ . '/../views/book.php';
        $keywords = ['rezerva', 'camera', 'concediu'];
        $description = 'Rezerva acum o camera pentru urmatorul tau concediu!';
        require __DIR__ . '/../views/layout.php';
    }
}
?>
