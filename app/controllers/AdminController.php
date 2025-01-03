<?php

namespace App\Controllers;

use app\middleware\AuthMiddleware;
use app\models\Booking;

require_once __DIR__ . '/../phpmailer/class.phpmailer.php';

use PHPMailer;
use Exception;
use phpmailerException;

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

    public function cancelBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingId = $_POST['booking_id'];
            $reason = $_POST['reason'];

            $userEmail = Booking::cancelBookingByAdmin($bookingId, $reason);

            $this->sendCancellationEmail($userEmail, $reason);

            header('Location: /admin/bookings');
            exit();
        }
    }

    private function sendCancellationEmail($email, $reason)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->IsSMTP();
            $mail->SMTPDebug  = 0;
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.gmail.com";
            $mail->Port       = 465;
            $mail->Username   = getenv('EMAIL_USERNAME');
            $mail->Password   = getenv('EMAIL_PASSWORD');
            $mail->AddReplyTo('no-reply@hotelx.com', 'Hotel X');
            $mail->AddAddress($email);
            $mail->SetFrom('no-reply@hotelx.com', 'Hotel X');
            $mail->Subject = 'Booking Cancellation';
            $mail->AltBody = 'To view this email, please use an HTML compatible email viewer!';
            $mail->MsgHTML("Your booking has been canceled for the following reason: $reason");
            $mail->Send();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
?>