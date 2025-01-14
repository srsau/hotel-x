<?php

namespace App\Controllers;

use app\models\Room;
use app\models\Addon;
require_once __DIR__ . '/../helpers/convertPrice.php';

class ApiController
{
    public function convertAmount()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $amount = isset($_GET['amount']) ? (float)$_GET['amount'] : 0;
            $currency = isset($_GET['currency']) ? $_GET['currency'] : 'USD';

            $convertedAmount = convertPrice($amount, $currency);

            echo json_encode(['convertedAmount' => $convertedAmount]);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
    }
}