<?php

namespace App\Controllers;

use app\models\Room;
use app\models\Facility;
use Exception;

class RoomController
{
    public function details()
    {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception("Room ID is required.");
            }

            $roomId = $_GET['id'];
            $room = Room::getRoomById($roomId);

            if (!$room) {
                throw new Exception("Room not found.");
            }

            $room['images'] = json_decode($room['images'], true);
            $room['facilities'] = Room::getRoomFacilities($roomId);

            if (isset($_SESSION['user']['id'])) {
                $room['available_rooms'] = Room::getCurrentAvailableRoomsCount($roomId);
            }

            $title = 'Room Details - ' . htmlspecialchars($room['name']);
            $view = __DIR__ . '/../views/room_details.php';
            $keywords = ['vacanta', 'hotel', 'camera', htmlspecialchars($room['name'])];
            $description = 'Detaliile camerei ' . htmlspecialchars($room['name']) . ' la Hotel X';
            require __DIR__ . '/../views/layout.php';
        } catch (Exception $e) {
            $error = $e->getMessage();
            $title = 'Error';
            $view = __DIR__ . '/../views/error.php';
            require __DIR__ . '/../views/layout.php';
        }
    }

    public function create()
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price_per_night = $_POST['price_per_night'];
                $capacity = $_POST['capacity'];
                $floor = $_POST['floor'];
                $popular = isset($_POST['popular']) ? 1 : 0;
                $facilities = $_POST['facilities'] ?? [];
                $available_rooms = $_POST['available_rooms'];

                $image_url = $this->uploadImage($_FILES['image_url']);
                $images = $this->uploadImages($_FILES['images']);

                if (!$image_url) {
                    throw new Exception("Main image is required.");
                }

                $roomId = Room::createRoom($name, $description, $image_url, $images, $price_per_night, $capacity, $floor, $popular, $available_rooms);
                Facility::updateFacilities($roomId, $facilities);

                header('Location: /');
                exit();
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $facilities = Facility::getAllFacilities();

        $title = 'Create Room';
        $view = __DIR__ . '/../views/room_form.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function edit()
    {
        $error = null;

        try {
            if (!isset($_GET['id'])) {
                throw new Exception("Room ID is required.");
            }

            $roomId = $_GET['id'];
            $room = Room::getRoomById($roomId);

            if (!$room) {
                throw new Exception("Room not found.");
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price_per_night = $_POST['price_per_night'];
                $capacity = $_POST['capacity'];
                $floor = $_POST['floor'];
                $popular = isset($_POST['popular']) ? 1 : 0;
                $facilities = $_POST['facilities'] ?? [];
                $available_rooms = $_POST['available_rooms'];

                $image_url = $this->uploadImage($_FILES['image_url'], $room['image_url']);
                $images = $this->uploadImages($_FILES['images'], $room['images']);

                if (!$image_url) {
                    throw new Exception("Main image is required.");
                }

                Room::updateRoom($roomId, $name, $description, $image_url, $images, $price_per_night, $capacity, $floor, $popular, $available_rooms);
                Facility::updateFacilities($roomId, $facilities);

                header('Location: /');
                exit();
            }

            $facilities = Facility::getAllFacilities();
            $roomFacilities = Facility::getFacilitiesByRoomId($roomId);

            $room['images'] = json_decode($room['images'], true);

            $title = 'Edit Room - ' . htmlspecialchars($room['name']);
            $view = __DIR__ . '/../views/room_form.php';
            require __DIR__ . '/../views/layout.php';
        } catch (Exception $e) {
            $error = $e->getMessage();
            $title = 'Error';
            $view = __DIR__ . '/../views/error.php';
            require __DIR__ . '/../views/layout.php';
        }
    }

    private function uploadImage($file, $existingImage = null)
    {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $targetDir = __DIR__ . '/../../public/images/';
            $targetFile = $targetDir . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $targetFile);
            return '/images/' . rawurlencode(basename($file['name']));
        }
        return $existingImage;
    }

    private function uploadImages($files, $existingImages = null)
    {
        $uploadedImages = [];
        foreach ($files['name'] as $key => $name) {
            if ($files['error'][$key] === UPLOAD_ERR_OK) {
                $targetDir = __DIR__ . '/../../public/images/';
                $targetFile = $targetDir . basename($name);
                move_uploaded_file($files['tmp_name'][$key], $targetFile);
                $uploadedImages[] = '/images/' . rawurlencode(basename($name));
            }
        }
        return json_encode(array_merge(json_decode($existingImages, true) ?? [], $uploadedImages));
    }
}
?>
