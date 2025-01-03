<?php

namespace App\Controllers;

use app\middleware\AuthMiddleware;
use app\models\Booking;

class AccountController
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        $userId = $_SESSION['user']['id'];
        $bookings = Booking::getBookingsWithAddonsByUserId($userId);

        $view = __DIR__ . '/../views/account.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function cancelBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingId = $_POST['booking_id'];
            $userId = $_SESSION['user']['id'];
            Booking::cancelBooking($bookingId, $userId);
            header('Location: /account');
            exit();
        }
    }
}
?>