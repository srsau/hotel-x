<?php

namespace App\Controllers;

use app\Database;
use PDO;
use Exception;

class RoomController
{
    public function details()
    {
        if (!isset($_GET['id'])) {
            header('Location: /404');
            exit();
        }

        $roomId = $_GET['id'];
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT rooms.*, GROUP_CONCAT(facilities.name SEPARATOR ', ') as facilities FROM rooms LEFT JOIN room_facilities ON rooms.id = room_facilities.room_id LEFT JOIN facilities ON room_facilities.facility_id = facilities.id WHERE rooms.id = :id GROUP BY rooms.id");
        $stmt->bindParam(':id', $roomId);
        $stmt->execute();
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            header('Location: /404');
            exit();
        }

        $room['images'] = json_decode($room['images'], true);

        $title = 'Room Details - ' . htmlspecialchars($room['name']);
        $view = __DIR__ . '/../views/room_details.php';
        require __DIR__ . '/../views/layout.php';
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

                $image_url = $this->uploadImage($_FILES['image_url']);
                $images = $this->uploadImages($_FILES['images']);

                if (!$image_url) {
                    throw new Exception("Main image is required.");
                }

                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("INSERT INTO rooms (name, description, image_url, images, price_per_night, capacity, floor, popular) VALUES (:name, :description, :image_url, :images, :price_per_night, :capacity, :floor, :popular)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':image_url', $image_url);
                $stmt->bindParam(':images', $images);
                $stmt->bindParam(':price_per_night', $price_per_night);
                $stmt->bindParam(':capacity', $capacity);
                $stmt->bindParam(':floor', $floor);
                $stmt->bindParam(':popular', $popular);
                $stmt->execute();

                $roomId = $db->lastInsertId();
                $this->updateFacilities($roomId, $facilities);

                header('Location: /');
                exit();
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM facilities");
        $facilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Create Room';
        $view = __DIR__ . '/../views/room_form.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function edit()
    {
        $error = null;

        if (!isset($_GET['id'])) {
            header('Location: /404');
            exit();
        }

        $roomId = $_GET['id'];
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM rooms WHERE id = :id");
        $stmt->bindParam(':id', $roomId);
        $stmt->execute();
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            header('Location: /404');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price_per_night = $_POST['price_per_night'];
                $capacity = $_POST['capacity'];
                $floor = $_POST['floor'];
                $popular = isset($_POST['popular']) ? 1 : 0;
                $facilities = $_POST['facilities'] ?? [];

                $image_url = $this->uploadImage($_FILES['image_url'], $room['image_url']);
                $images = $this->uploadImages($_FILES['images'], $room['images']);

                if (!$image_url) {
                    throw new Exception("Main image is required.");
                }

                $stmt = $db->prepare("UPDATE rooms SET name = :name, description = :description, image_url = :image_url, images = :images, price_per_night = :price_per_night, capacity = :capacity, floor = :floor, popular = :popular WHERE id = :id");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':image_url', $image_url);
                $stmt->bindParam(':images', $images);
                $stmt->bindParam(':price_per_night', $price_per_night);
                $stmt->bindParam(':capacity', $capacity);
                $stmt->bindParam(':floor', $floor);
                $stmt->bindParam(':popular', $popular);
                $stmt->bindParam(':id', $roomId);
                $stmt->execute();

                $this->updateFacilities($roomId, $facilities);

                header('Location: /');
                exit();
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $stmt = $db->query("SELECT * FROM facilities");
        $facilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("SELECT facility_id FROM room_facilities WHERE room_id = :room_id");
        $stmt->bindParam(':room_id', $roomId);
        $stmt->execute();
        $roomFacilities = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $room['images'] = json_decode($room['images'], true);

        $title = 'Edit Room - ' . htmlspecialchars($room['name']);
        $view = __DIR__ . '/../views/room_form.php';
        require __DIR__ . '/../views/layout.php';
    }

    private function uploadImage($file, $existingImage = null)
    {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $targetDir = __DIR__ . '/../../public/images/';
            $targetFile = $targetDir . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $targetFile);
            return '/images/' . basename($file['name']);
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
                $uploadedImages[] = '/images/' . basename($name);
            }
        }
        return json_encode(array_merge(json_decode($existingImages, true) ?? [], $uploadedImages));
    }

    private function updateFacilities($roomId, $facilities)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM room_facilities WHERE room_id = :room_id");
        $stmt->bindParam(':room_id', $roomId);
        $stmt->execute();

        foreach ($facilities as $facilityId) {
            $stmt = $db->prepare("INSERT INTO room_facilities (room_id, facility_id) VALUES (:room_id, :facility_id)");
            $stmt->bindParam(':room_id', $roomId);
            $stmt->bindParam(':facility_id', $facilityId);
            $stmt->execute();
        }
    }
}
