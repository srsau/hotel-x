<?php

namespace App\Controllers;

use app\models\Addon;
use app\models\Booking;
use app\models\Room;
use Exception;

require_once __DIR__ . '/../helpers/convertPrice.php';
require_once __DIR__ . '/../views/steps/stepper.php';

class BookingController
{

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

    public function initializeBooking()
    {
        $roomId = $_GET['room_id'];
        if (!isset($roomId)) {
            header('Location: /404');
            exit();
        }

        $room = Room::getRoomById($roomId);

        if (!$room) {
            header('Location: /404');
            exit();
        }


        $_SESSION['booking'] = [
            'step' => 1,
            'data' => []
        ];

        $_SESSION['booking']['data']['start-date'] = date('Y-m-d');
        $_SESSION['booking']['data']['room']['id'] = $roomId;
        $_SESSION['booking']['data']['room']['name'] = $room['name'];

        header('Location: /book?step=1');
        exit();
    }

    public function resetBooking()
    {
        unset($_SESSION['booking']);
        header('Location: /book?step=1');
        exit();
    }

    public function book()
    {
        if (!isset($_SESSION['booking'])) {
            $_SESSION['booking'] = [
                'step' => 1,
                'data' => []
            ];
        }

        $step = isset($_GET['step']) ? (int) $_GET['step'] : $_SESSION['booking']['step'];
        $step = max(1, min($step, 5)); 

        // Check if the user has completed the previous steps
        if ($step > $_SESSION['booking']['step']) {
            // If the user tries to skip ahead, redirect them to the current step
            header('Location: /book?step=' . $_SESSION['booking']['step']);
            exit();
        }

        $data = $_SESSION['booking']['data'];
        $error = null;

        $startDate = isset($_SESSION['booking']['data']['start-date']) ? $_SESSION['booking']['data']['start-date'] : null;
        $endDate = isset($_SESSION['booking']['data']['end-date']) ? $_SESSION['booking']['data']['end-date'] : null;
        $guests = isset($_SESSION['booking']['data']['guests']) ? $_SESSION['booking']['data']['guests'] : null;
        $selectedRoomId = isset($_SESSION['booking']['data']['room']['id']) ? $_SESSION['booking']['data']['room']['id'] : null;
        $selectedAddon = isset($_SESSION['booking']['data']['selected_addons']) ? $_SESSION['booking']['data']['selected_addons'] : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentStep = $_POST['current_step'] ?? null;


            if ($currentStep != $step) {
                header('Location: /book?step=' . $step);
                exit();
            }

            if ($step === 1) {
                $validationResult = $this->validateStep1($_POST);
                if ($validationResult !== true) {
                    $error = $validationResult;
                    $data['start-date'] = $_POST['start-date'];
                    $data['end-date'] = $_POST['end-date'];
                } else {
                    $data = array_merge($data, $_POST);
                    $_SESSION['booking']['data'] = $data;
                    $_SESSION['booking']['step'] = 2;
                    header('Location: /book?step=2');
                }
            } elseif ($step === 2) {
                $validationResult = $this->validateStep2($_POST);

                if ($validationResult !== true) {
                    $error = $validationResult;

                    $data['guests'] = $_POST['guests'];
                } else {
                    $data = array_merge($data, $_POST);
                    $_SESSION['booking']['data'] = $data;
                    $_SESSION['booking']['step'] = 3;
                    header('Location: /book?step=3');
                }
            } elseif ($step === 3) {
                $validationResult = $this->validateStep3($_POST);
                if ($validationResult !== true) {
                    $error = $validationResult;
                    $data = $_POST;
                } else {
                    $room = Room::getRoomById($_POST['selected_room'][0]);
                    $data['room']['id'] = $_POST['selected_room'][0];
                    $data['room']['name'] = $room['name'];
                    $_SESSION['booking']['data'] = $data;
                    $_SESSION['booking']['step'] = 4;
                    header('Location: /book?step=4');
                }
            } elseif ($step === 4) {
                $validationResult = $this->validateStep4($_POST);
                if ($validationResult !== true) {
                    $error = $validationResult;
                    $data = $_POST;
                } else {
                    $addons = Addon::getAllAddons();
                    $selectedAddons = [];
                    if (!empty($_POST['selected_addons']) && is_array($_POST['selected_addons'])) {
                        foreach ($_POST['selected_addons'] as $selectedAddonId) {
                            foreach ($addons as $addon) {
                                if ($addon['id'] == $selectedAddonId) {
                                    $selectedAddons[] = [
                                        'id' => $addon['id'],
                                        'name' => $addon['name'],
                                        'price' => $addon['price'],
                                    ];
                                }
                            }
                        }
                    }
                    $data['selected_addons'] = $selectedAddons;
                    $_SESSION['booking']['data'] = $data;
                    $_SESSION['booking']['step'] = 5;
                    header('Location: /book?step=5');
                }
            } elseif ($step === 5) {
                try {
                    if (!isset($_SESSION['user']['id'])) {
                        throw new Exception("User not logged in.");
                    }

                    $userId = $_SESSION['user']['id'];
                    $data = $_SESSION['booking']['data'];

                    if (!isset($data['room']['id'], $data['start-date'], $data['end-date'], $data['selected_addons'])) {

                        throw new Exception("Missing booking data.");
                    }

                    $roomId = $data['room']['id'];
                    $checkInDate = $data['start-date'];
                    $checkOutDate = $data['end-date'];
                    $addonIds = array_column($data['selected_addons'], 'id');

                    $totalPrice = $this->calculateTotalPrice($roomId, $checkInDate, $checkOutDate, $addonIds);

                    Booking::createBooking($userId, $roomId, $checkInDate, $checkOutDate, $addonIds, $totalPrice);

                    unset($_SESSION['booking']);

                    $_SESSION['booking_success'] = "Rezervare facuta cu success!";
                    header('Location: /account');
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        switch ($step) {
            case 1:
                $view = __DIR__ . '/../views/steps/step1.php';
                break;
            case 2:
                $view = __DIR__ . '/../views/steps/step2.php';
                break;
            case 3:
                $rooms = Room::getAvailableRooms($startDate, $endDate, $guests);
                $view = __DIR__ . '/../views/steps/step3.php';
                break;
            case 4:
                $addons = Addon::getAllAddons();
                $view = __DIR__ . '/../views/steps/step4.php';
                break;
            case 5:

                $startDateTime = new \DateTime($startDate);
                $endDateTime = new \DateTime($endDate);
                $interval = $startDateTime->diff($endDateTime);
                $nights = $interval->days;

                $room = Room::getRoomById($selectedRoomId);
                $roomPrice = $room['price_per_night'] * $nights;

                $addonsPrices = array_map(function ($addon) {
                    return $addon['price'];
                }, $selectedAddon);
                $totalAddonsPrice = array_sum($addonsPrices);

                $convertedTotalCost = convertPrice(($roomPrice + $totalAddonsPrice), $_SESSION['preferred_currency']);

                $nights = (new \DateTime($endDate))->diff(new \DateTime($startDate))->days;

                $view = __DIR__ . '/../views/steps/step5.php';
                break;
            default:
                $view = __DIR__ . '/../views/steps/step1.php';
                break;
        }

        $keywords = ['rezerva', 'camera', 'concediu'];
        $description = 'Rezerva acum o camera pentru urmatorul tau concediu!';
        $stepper = renderStepper($step);

        require __DIR__ . '/../views/layout.php';
    }


    private function validateStep1($data)
    {
        $startDate = $data['start-date'] ?? null;
        $endDate = $data['end-date'] ?? null;
        $today = (new \DateTime())->setTime(0, 0, 0);

        if (!$startDate || !$endDate) {
            return 'Selectati data de inceput si data de sfarsit.';
        }

        $startDateTime = \DateTime::createFromFormat('Y-m-d', $startDate)->setTime(0, 0, 0);
        $endDateTime = \DateTime::createFromFormat('Y-m-d', $endDate)->setTime(0, 0, 0);

        $startDateTime->setTime(0, 0, 0);
        $endDateTime->setTime(0, 0, 0);

        if ($startDateTime < $today) {
            return 'Start date cannot be in the past.';
        }

        if ($endDateTime < $startDateTime) {
            return 'End date cannot be before start date.';
        }

        if ($startDateTime == $endDateTime) {
            return 'Check-in date and check-out date cannot be the same.';
        }

        return true;
    }

    private function validateStep2($data)
    {
        $guests = $data['guests'] ?? null;

        if (!$guests) {
            return 'Please select the number of guests.';
        }

        if ($guests < 1 || $guests > 12) {
            return 'Please enter a valid number of guests (1-12).';
        }

        return true;
    }


    private function validateStep3($data)
    {
        if (empty($data['selected_room'])) {
            return 'Please select a room.';
        }

        if (is_array($data['selected_room']) && count($data['selected_room']) > 1) {
            return 'Please select only one room.';
        }

        return true;
    }

    private function validateStep4($data)
    {

        return true;
    }
}
