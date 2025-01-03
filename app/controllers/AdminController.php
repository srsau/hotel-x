<?php

namespace App\Controllers;

use app\middleware\AuthMiddleware;
use app\models\Booking;

class AdminController
{
    public function __construct()
    {
        AuthMiddleware::handle();
        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: /');
            exit();
        }
    }

    public function index()
    {
        $title = 'Admin Dashboard';
        $view = __DIR__ . '/../views/admin.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function bookings()
    {
        $bookings = Booking::getAllBookings();

        $title = 'All Bookings';
        $view = __DIR__ . '/../views/admin_bookings.php';
        require __DIR__ . '/../views/layout.php';
    }
}
?>