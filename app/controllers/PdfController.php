<?php

namespace App\Controllers;

use app\middleware\AuthMiddleware;
use app\models\Booking;
require_once __DIR__ . '/../fpdf/fpdf.php';
require_once __DIR__ . '/../helpers/convertPrice.php';

class PdfController
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function generateReceipt()
    {
        if (!isset($_GET['booking_id'])) {
            http_response_code(400);
            echo 'Booking ID is required';
            return;
        }

        $bookingId = $_GET['booking_id'];
        $userId = $_SESSION['user']['id'];
        $booking = Booking::getBookingByIdAndUserId($bookingId, $userId);
        $preferred_currency = $_SESSION['preferred_currency'];

        if (!$booking) {
            http_response_code(404);
            echo 'Booking not found or you do not have permission to access this booking';
            return;
        }

        $pdf = new \FPDF();
        $pdf->AddPage();

        // Use Arial font with UTF-8 encoding
        $pdf->SetFont('Arial', '', 16);

        $pdf->Cell(0, 10, 'Hotel X', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, '123 Fake Street, Faketown, FK1 2AB', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Phone: (123) 456-7890', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Email: info@hotelx.com', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(0, 10, 'Booking Receipt', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Field', 1);
        $pdf->Cell(0, 10, 'Details', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Booking ID:', 1);
        $pdf->Cell(0, 10, $booking['id'], 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'User:', 1);
        $pdf->Cell(0, 10, htmlspecialchars($_SESSION['user']['name']), 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'Room:', 1);
        $pdf->Cell(0, 10, $booking['room_name'], 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'Check-in Date:', 1);
        $pdf->Cell(0, 10, $booking['check_in_date'], 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'Check-out Date:', 1);
        $pdf->Cell(0, 10, $booking['check_out_date'], 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'Addons:', 1);
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $booking['addon_names']), 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'Total Price:', 1);
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', convertPrice($booking['total_price'], $preferred_currency)), 1);
        $pdf->Ln();
        $pdf->Cell(50, 10, 'Status:', 1);
        $pdf->Cell(0, 10, $booking['status'], 1);
        $pdf->Ln(20);

        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, 'Thank you for choosing Hotel X. We hope you enjoy your stay!', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 10, 'If you have any questions, please contact us at info@hotelx.com or (123) 456-7890.', 0, 1, 'C');

        $pdf->Output('D', 'receipt.pdf');
    }
}
?>