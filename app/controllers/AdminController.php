<?php

namespace App\Controllers;

use app\middleware\AuthMiddleware;
use app\models\Booking;
use app\models\Analytics;


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

            $bookingDetails = Booking::cancelBookingByAdmin($bookingId, $reason);

            $this->sendCancellationEmail($bookingDetails, $reason);

            header('Location: /admin/bookings');
            exit();
        }
    }

    public function analytics()
    {
        $analytics = Analytics::getAll();
        $uniqueIps = Analytics::getUniqueIps();
        $uniquePages = Analytics::getUniquePages();
        $uniqueAccessCount = Analytics::getUniqueAccessCount();
        $deviceTypeDistribution = Analytics::getDeviceTypeDistribution();
        $browserDistribution = Analytics::getBrowserDistribution();

        // Generate the device type distribution chart
        $deviceChartDataUri = $this->generatePieChart($deviceTypeDistribution, 'device_type');

        // Generate the browser distribution chart
        $browserChartDataUri = $this->generatePieChart($browserDistribution, 'browser_name');

        $title = 'Analytics';
        $view = __DIR__ . '/../views/admin_analytics.php';
        require __DIR__ . '/../views/layout.php';
    }

    private function generatePieChart($data, $labelKey)
    {
        $width = 400; 
        $height = 400; 
        $legendHeight = 100;
        $image = imagecreate($width, $height + $legendHeight);

        $colors = [
            'pc' => imagecolorallocate($image, 255, 0, 0),
            'mobile' => imagecolorallocate($image, 0, 255, 0),
            'tablet' => imagecolorallocate($image, 0, 0, 255),
            'unknown' => imagecolorallocate($image, 128, 128, 128),
            'Internet Explorer' => imagecolorallocate($image, 255, 165, 0),
            'Firefox' => imagecolorallocate($image, 255, 69, 0),
            'Chrome' => imagecolorallocate($image, 0, 128, 0),
            'Safari' => imagecolorallocate($image, 0, 0, 128),
            'Opera' => imagecolorallocate($image, 128, 0, 128),
        ];

        $backgroundColor = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $backgroundColor);

        $total = array_sum(array_column($data, 'count'));
        $angleStart = 0;

        foreach ($data as $row) {
            $angle = ($row['count'] / $total) * 360;
            $angleEnd = $angleStart + $angle;
            $color = $colors[$row[$labelKey]] ?? imagecolorallocate($image, 0, 0, 0); // Default to black if color not found
            imagefilledarc($image, $width / 2, $height / 2, $width - 10, $height - 10, $angleStart, $angleEnd, $color, IMG_ARC_PIE);
            $angleStart = $angleEnd;
        }

        // Add legend
        $legendX = 10;
        $legendY = $height + 10;
        $fontSize = 5;
        foreach ($data as $row) {
            $color = $colors[$row[$labelKey]] ?? imagecolorallocate($image, 0, 0, 0); // Default to black if color not found
            imagefilledrectangle($image, $legendX, $legendY, $legendX + 20, $legendY + 20, $color);
            imagestring($image, $fontSize, $legendX + 30, $legendY + 5, $row[$labelKey] . ' (' . $row['count'] . ')', imagecolorallocate($image, 0, 0, 0));
            $legendY += 30;
        }

        ob_start();
        imagepng($image);
        $chartData = ob_get_clean();
        imagedestroy($image);

        return 'data:image/png;base64,' . base64_encode($chartData);
    }

    private function sendCancellationEmail($bookingDetails, $reason)
    {
        $message = "
            Dear {$bookingDetails['user_name']},<br><br>
            Your booking has been canceled for the following reason: $reason<br><br>
            Booking Details:<br>
            Room: {$bookingDetails['room_name']}<br>
            Check-in Date: {$bookingDetails['check_in_date']}<br><br>
            We apologize for any inconvenience caused.<br><br>
            Regards,<br>
            Hotel X
        ";

        sendEmail($bookingDetails['user_email'], 'Booking Cancellation', $message);
    }
}
?>