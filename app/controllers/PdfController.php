<?php

namespace App\Controllers;

use app\middleware\AuthMiddleware;
use app\models\Booking;
require_once __DIR__ . '/../fpdf/fpdf.php';

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

        if (!$booking) {
            http_response_code(404);
            echo 'Booking not found or you do not have permission to access this booking';
            return;
        }
        
        $pdf = new \FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Hotel X', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, '123 Fake Street, Faketown, FK1 2AB', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Phone: (123) 456-7890', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Email: info@hotelx.com', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Booking Receipt', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Booking ID:', 0, 0);
        $pdf->Cell(0, 10, $booking['id'], 0, 1);
        $pdf->Cell(50, 10, 'User:', 0, 0);
        $pdf->Cell(0, 10, htmlspecialchars($_SESSION['user']['name']), 0, 1);
        $pdf->Cell(50, 10, 'Room:', 0, 0);
        $pdf->Cell(0, 10, $booking['room_name'], 0, 1);
        $pdf->Cell(50, 10, 'Check-in Date:', 0, 0);
        $pdf->Cell(0, 10, $booking['check_in_date'], 0, 1);
        $pdf->Cell(50, 10, 'Check-out Date:', 0, 0);
        $pdf->Cell(0, 10, $booking['check_out_date'], 0, 1);
        $pdf->Cell(50, 10, 'Addons:', 0, 0);
        $pdf->Cell(0, 10, $booking['addon_names'], 0, 1);
        $pdf->Cell(50, 10, 'Total Price:', 0, 0);
        $pdf->Cell(0, 10, '$' . $booking['total_price'], 0, 1);
        $pdf->Cell(50, 10, 'Status:', 0, 0);
        $pdf->Cell(0, 10, $booking['status'], 0, 1);
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